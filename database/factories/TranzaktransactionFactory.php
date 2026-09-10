<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TranzaktransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'request_id' => $this->faker->unique()->uuid(), 
            'amount' => 1000, 
            'currency_code' => 'XAF', 
            'purpose' => 'faker_demo', 
            'mobile_wallet_number' => $this->faker->phoneNumber(), 
            'transaction_ref' => $this->faker->uuid(), 
            'app_id' => 'faker_app_id', 
            'transaction_id' => $this->faker->unique()->randomAscii(), 
            'transaction_time' => now(), 
            'payment_method' => 'MOMO', 
            'payer_user_id' => $this->faker->phoneNumber(), 
            'payer_name' => $this->faker->name('male'), 
            'payer_account_id' => $this->faker->phoneNumber(), 
            'merchant_fee' => 0, 
            'merchant_account_id' => $this->faker->phoneNumber(), 
            'net_amount_recieved' => 1000, 
            'payment_id' => 1
        ];
    }
}
