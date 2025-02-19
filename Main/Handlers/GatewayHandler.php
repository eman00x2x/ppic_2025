<?php

namespace EO\Handlers;

use EO\Interfaces\IPaymentGateway;

class GatewayHandler
{
	private IPaymentGateway $gateway;

    // Inject the appropriate payment gateway
    public function __construct(IPaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function initializePayment(float $amount, string $currency, array $customer_details): array
    {
        return $this->gateway->initializePayment($amount, $currency, $customer_details);
    }

    public function processPayment(string $payment_id): bool
    {
        return $this->gateway->processPayment($payment_id);
    }

    public function handleCallback(array $callback_data): bool
    {
        return $this->gateway->handleCallback($callback_data);
    }
}