<?php

namespace EO\Handlers\Gatways;

class PaypalGateway implements IPaymentGateway
{
	public function initializePayment(float $amount, string $currency, array $customer_details): array
	{
		return [];
	}

	public function processPayment(string $payment_id): bool
	{
		return true;
	}

	public function handleCallback(array $callback_data): bool
	{
		return true;
	}

}