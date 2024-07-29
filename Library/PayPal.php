<?php

namespace Library;

// no direct access

defined( 'ACCESS' ) or die( 'Restricted access' );

class PayPal {

    private $doc;
    private	$client_id = PAYPAL_CLIENT_ID;
    private	$client_secret = PAYPAL_CLIENT_SECRET;
    private	$currency = CURRENCY;
    private	$environment = PAYPAL_ENVIRONMENT;

    function __construct() {
        $this->doc =  \Library\Factory::getDocument();
    }

    function createOrder($data, $validation_url, $payment_status_url) {

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

		$data['total_without_tax'] = $data['cost'] * $quantity;
		$data['total_with_tax'] = ($data['cost'] + $data['tax']) * $quantity;

        $this->doc->addScript("https://www.paypal.com/sdk/js?client-id=".$this->client_id."&currency=".$this->currency."&intent=capture&commit=false&vault=false&disable-funding=card");
		$this->doc->addScriptDeclaration(str_replace([PHP_EOL,"\t"], ["",""], "

			var paymentPageLink;
			var order_id;

            $(document).ready(function() {

                paypal.Buttons({
                    style: {
                        layout: 'vertical',
                        color:  'blue',
                        shape:  'rect',
                        label:  'paypal'
                    },

                    createOrder: function( data, actions ) {

						return actions.order.create({
							\"intent\": \"CAPTURE\",
							\"purchase_units\": [{
								\"reference_id\": \"".$data["premium_id"]."\",
								\"description\": \"".nicetrim("[".$data["name"]."] ".$data["details"],100)."\",
								\"items\": [{
									\"name\": \"".$data['name']."\",
									\"description\": \"".nicetrim("[".$data["name"]."] ".$data["details"],100)."\",
									\"quantity\": $quantity,
									\"unit_amount\": {
										\"currency_code\": \"PHP\",
										\"value\": ".$data['cost']."
									},
									\"tax\": {
										\"currency_code\": \"PHP\",
										\"value\": ".$data['tax']."
									},
									\"category\": \"DIGITAL_GOODS\"
								}],
								\"amount\": {
									\"currency_code\": \"".$this->currency."\",
									\"value\": ".$data["total_with_tax"].",
									\"breakdown\": {
										\"item_total\": {
											\"currency_code\": \"PHP\",
											\"value\": ".$data['total_without_tax']."
										},
										\"tax_total\": {
											\"currency_code\": \"PHP\",
											\"value\": ".$data['tax_total']."
										}
									}
								}
							}]
						});

                    },

					onApprove: function( data, actions ) {

						return actions.order.capture().then(function(orderData) {
							setProcessing(true);
							
							var postData = {paypal_order_check: 1, order_id: orderData.id};
							fetch('".$validation_url."', {
								method: 'POST',
								headers: {'Accept': 'application/json'},
								body: encodeFormData(postData)
							})
							.then((response) => response.json())
							.then((result) => {

								if(result.status == 1){
									window.location.href = '".$payment_status_url."?checkout_ref_id='+result.payment_id;
								}else{
									$('.response').html(result.msg);
								}

								setProcessing(false);
							})
							.catch(error => console.log(error));
						});

					},

					onCancel: function( data ) {
						alert('Order cancelled');
					}

                }).render('#paypal-button-container');

            });

			const encodeFormData = (data) => {
				var form_data = new FormData();

				for ( var key in data ) {
					form_data.append(key, data[key]);
				}
				return form_data;   
			};

			/* Show a loader on payment form processing */
			const setProcessing = (isProcessing) => {
				if (isProcessing) {
					$('.loader-container').removeClass('d-none');
					$('.cart-container').addClass('d-none');
				}
			};
			
		"));

    }

	function validatePayment($order_id) {

		if(isset($order_id)) {

			$response = $this->generateAccessToken();
		
			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '. $response['data']['access_token']
			);
			
			try {
				$order_response = $this->request("GET","/v2/checkout/orders/".$order_id, null, $headers);
			} catch(Exception $e) {  
				$api_error = $e->getMessage();  
			}

			if(!empty($order_response)) {

				$intent = $order_response['data']['intent'];
				$order_status = $order_response['data']['status'];

				$new_data['modified_at'] = 0;

				if(!empty($order_response['data']['purchase_units'][0])){ 
					$purchase_unit = $order_response['data']['purchase_units'][0]; 
		
					$new_data['premium_id'] = $purchase_unit['reference_id'];
					$new_data['premium_description'] = $purchase_unit['description'];
					
					$new_data['quantity'] = $purchase_unit['items'][0]['quantity'];

					switch($new_data['quantity']) {
						case 1: $new_data['duration'] = 30; break;
						case 6: $new_data['duration'] = 180; break;
						case 12: $new_data['duration'] = 365; break;
					}
					
					if(!empty($purchase_unit['amount'])){
						$new_data['premium_price'] = $purchase_unit['amount']['value'];
					} 
		
					if(!empty($purchase_unit['payments']['captures'][0])){ 
						$payment_capture = $purchase_unit['payments']['captures'][0]; 
						$new_data['created_at'] = strtotime($payment_capture['create_time']);
						$new_data['payment_id'] = $payment_capture['id']; 
						$new_data['payment_status'] = $payment_capture['status'];
						$new_data['transaction_details'] = $payment_capture;
						$new_data['transaction_details']['seller_receivable_breakdown']['platform_fee'] = [
							"currency_code" => $payment_capture['seller_receivable_breakdown']['paypal_fee']['currency_code'],
							"value" => $payment_capture['seller_receivable_breakdown']['paypal_fee']['value']
						];
					} 
		
					if(!empty($purchase_unit['payee'])){ 
						$payee = $purchase_unit['payee']; 
						$new_data['merchant_email'] = $payee['email_address']; 
						$new_data['merchant_id'] = $payee['merchant_id']; 
					} 
				} 
		
				$new_data['payment_source'] = ''; 
				if(!empty($order_response['data']['payment_source'])){ 
					foreach($order_response['data']['payment_source'] as $key => $value) { 
						$new_data['payment_source'] = $key; 
					} 
				} 

				if(!empty($order_response['data']['payer'])){
					$new_data['payer'] = $order_response['data']['payer'];
				} 

				if(!empty($order_response['data']['id']) && $order_status == 'COMPLETED') {

					$response = [
						"status" => 1,
						"msg" => 'Transaction Completed!',
						"payment_id" => $new_data['payment_id'],
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

        $url = $this->environment === "sandbox" ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com";

		$handle = curl_init($url.$endpoint);
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
		
		$url = $this->environment === "sandbox" ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com";

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