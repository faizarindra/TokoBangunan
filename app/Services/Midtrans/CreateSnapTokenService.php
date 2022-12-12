<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $order;

    public function __construct($order, $product, $customer)
    {
        parent::__construct();

        $this->order = $order;
        $this->product = $product;
        $this->customer = $customer;
    }

    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->order->kode,
                'gross_amount' => (int)$this->order->total,
            ],
            'item_details' => $this->product,
            'customer_details' => [
                'first_name' => $this->customer->name,
                'email' => $this->customer->email,
                'phone' => $this->customer->no_telp,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
