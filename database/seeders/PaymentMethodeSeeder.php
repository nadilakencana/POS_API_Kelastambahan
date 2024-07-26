<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payments;

class PaymentMethodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payments::insert([
            [
                'payment_method' => 'Cash',
            ],
            [
                'payment_method' => 'Credit Card',
            ],
            [
                'payment_method' => 'Paypal',
            ],
            [
                'payment_method' => 'E-wallet',
            ]
        ]);

    }
}
