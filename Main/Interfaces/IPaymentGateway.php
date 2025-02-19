<?php

namespace EO\Interfaces;

interface IPaymentGateway
{
    public function initializePayment(float $amount, string $currency, array $customer_details): array;

    public function processPayment(string $payment_id): bool;

    public function handleCallback(array $callback_data): bool;
}