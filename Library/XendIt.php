<?php

namespace Library;

// no direct access

defined( 'ACCESS' ) or die( 'Restricted access' );

class XendIt {

    private $doc;
    private	$api_key = XENDIT_API_KEY;
    private	$currency = CURRENCY;
    private	$unique_id;
    private	$session;

    function __construct() {
        $this->doc =  \Library\Factory::getDocument();

		$session = new \Library\SessionHandler;
		$this->session = $session->get('user_logged');
    }

    function requestInvoice($data, $payment_status_url) {

		switch($data['duration']) {
			case '365': $quantity = 12;		break;
			case '730': $quantity = 24;		break;
			default: $quantity = $data['duration'] / 30; break;
		}

		$data['tax'] = 0;
		$data['tax_total'] = 0;

		if(VAT) {
			$data['cost'] = round($data['cost'] / 1.12, 2);
			$data['tax'] = round($data['cost'] * 0.12, 2);
			$data['tax_total'] = round($data['tax'] * $quantity, 2);
		}

		$data['total_amount'] = ($data['cost'] + $data['tax']) * $quantity;

		$this->unique_id = $data['premium_id']."".$this->session['account_id'];

		$post_data = json_encode([
			"external_id" => "testing_external_id-".$this->unique_id,
			"amount" => $data['total_amount'],
			"currency" => "".$this->currency."",
			"customer" => [
				"given_names" => "".$data['account']['account_name']['firstname']."",
				"surname" => "".$data['account']['account_name']['lastname']."",
				"email" => "".$data['account']['email']."",
				"mobile_number" => "".$data['account']['mobile_number']."",
			],
			"customer_notification_preference" => [
				"invoice_paid" => ["email", "viber"]
			],
			"success_redirect_url" => "$payment_status_url",
			"failure_redirect_url" => "$payment_status_url",
			"items" => [
				[
					"name" => "".nicetrim("[".$data["name"]."] ".$data["details"],100)."",
					"quantity" => $quantity,
					"price" => $data['cost'],
					"category" => "Subscription"
				]
			],
			"payment_methods" => ["7ELEVEN", "CEBUANA", "DD_BPI", "DD_UBP", "DD_RCBC", "DD_BDO_EPAY", "DP_MLHUILLIER", "DP_PALAWAN", "DP_ECPAY_LOAN", "PAYMAYA", "GRABPAY", "GCASH", "SHOPEEPAY", "BDO_ONLINE_BANKING", "BPI_ONLINE_BANKING", "UNIONBANK_ONILNE_BANKING", "BOC_ONLINE_BANKING", "CHINABANK_ONLINE_BANKING", "INSTAPAY_ONLINE_BANKING", "LANDBANK_ONLINE_BANKING", "MAYBANK_ONLINE_BANKING", "METROBANK_ONLINE_BANKING", "PNB_ONLINE_BANKING", "PSBANK_ONLINE_BANKING", "PESONET_ONLINE_BANKING", "RCBC_ONLINE_BANKING", "ROBINSONS_BANK_ONLINE_BANKING", "SECURITY_BANK_ONLINE_BANKING", "QRPH", "CREDIT_CARD"],
			"fees" => [
				[
					"type" => "tax",
					"value" => $data['tax_total']
				]
			],
			"others" => [
				"premium_id" => $data['premium_id'],
				"premium_description" => $data['details'],
				"duration" => $data['duration']
			]
		]);

        $this->doc->addScriptDeclaration(str_replace([PHP_EOL,"\t"], ["",""], "
			const postData = $post_data;
			postData.external_id = rcg('0000000000000000');
			postData.success_redirect_url = postData.success_redirect_url + '?checkout_ref_id=' + postData.external_id;
			postData.failure_redirect_url = postData.failure_redirect_url + '?checkout_ref_id=' + postData.external_id;
			$(document).on('click', '.btn-xendit-checkout', function() {
				setProcessing(true);
				$.post('".url("TransactionsController@xenditCreateInvoce")."', postData,  function(data) {
					response = JSON.parse(data);
					console.log(response);
					if(response.processed_data.transaction_details.id != '') {
						window.location = 'https://checkout-staging.xendit.co/web/' + response.processed_data.transaction_details.id;
					}

				});
			});
		"));

    }

	function createInvoice($data) {

		if(isset($data)) {

			$headers = [
				"Content-Type: application/json",
				"Authorization: Basic ".base64_encode($this->api_key.':').""
			];

			try {
				$order_response = $this->request("POST", "https://api.xendit.co/v2/invoices", $data, $headers);
			} catch(Exception $e) {  
				$api_error = $e->getMessage();  
			}

			if(!empty($order_response)) {

				$order_response = $order_response['data'];

				unset($order_response['available_banks']);
				unset($order_response['available_retail_outlets']);
				unset($order_response['available_ewallets']);
				unset($order_response['available_qr_codes']);
				unset($order_response['available_direct_debits']);
				unset($order_response['available_paylaters']);
				unset($order_response['should_exclude_credit_card']);
				unset($order_response['should_send_email']);
				unset($order_response['success_redirect_url']);
				unset($order_response['failure_redirect_url']);

				$new_data['premium_id'] = $data['others']['premium_id'];
				$new_data['premium_description'] = $data['others']['premium_description'];
				$new_data['premium_price'] = $order_response['amount'];
				$new_data['duration'] = $data['others']['duration'];

				$new_data['created_at'] = strtotime($order_response['created']);
				$new_data['payment_id'] = $data['external_id']; 
				$new_data['payment_status'] = $order_response['status'];

				$new_data['transaction_details'] = $order_response;
				$new_data['transaction_details']['links'] = [
					"href" => "https://checkout-staging.xendit.co/web/".$order_response['id'],
					"rel" => "self",
					"method" => "GET"
				];

				$new_data['merchant_email'] = ""; 
				$new_data['merchant_id'] = ""; 

				$new_data['payment_source'] = "xendit"; 
				$new_data['payer'] = $order_response['customer'];

				$new_data['modified_at'] = 0;

				if(!empty($order_response['id'])) {

					$response = [
						"status" => 1,
						"msg" => 'Invoice Created',
						"payment_id" => base64_encode($new_data['payment_id']),
						"processed_data" => $new_data
					];

				}

			}else {
				$response = [
					"status" => 0,
					"msg" => $api_error
				];
			}

		}else {
			$response = [
				"status" => 0, 
				"msg" => 'Transaction Failed!'
			]; 
		}

		return $response;

	}

	function request($method, $endpoint, $data = null, $headers = array()) {

		$handle = curl_init($endpoint);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		
		switch($method) {
			
			case 'POST':
				curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
				break;
			
			default:
				curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $method);
			
		}
		
		$response = json_decode(curl_exec($handle), true);
		
		$http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$header_info = curl_getinfo($handle, CURLINFO_HEADER_OUT);
		curl_close($handle);

		if ($http_code != 200 && !empty($response['error'])) {
			throw new Exception('Error '.$response['error'].': '.$response['error_description']);
        }

		return array(
			"headers" => $header_info,
			"data" => $response
		);

    }

	function generateAccessToken() {
		
		$url = PAYPAL_ENVIRONMENT === "sandbox" ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com";

		$handle = curl_init($url."/v1/oauth2/token");
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_USERPWD, $this->client_id.":".$this->client_secret);
		curl_setopt($handle, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		
		$response = json_decode(curl_exec($handle), true);
		
		$http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$header_info = curl_getinfo($handle, CURLINFO_HEADER_OUT);
		curl_close($handle);

		if ($http_code != 200 && !empty($response['error'])) {
			throw new Exception('Error '.$response['error'].': '.$response['error_description']);
        }

		return array(
			"headers" => $header_info,
			"data" => $response
		);

	}

}