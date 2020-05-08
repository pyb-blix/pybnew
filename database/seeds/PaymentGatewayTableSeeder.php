<?php

use Illuminate\Database\Seeder;

class PaymentGatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_gateway')->delete();

        DB::table('payment_gateway')->insert([
            array('id' => '1','name' => 'username','value' => 'pvignesh90-facilitator_api1.gmail.com','site' => 'PayPal'),
            array('id' => '2','name' => 'password','value' => '1381798304','site' => 'PayPal'),
            array('id' => '3','name' => 'signature','value' => 'AiPC9BjkCyDFQXbSkoZcgqH3hpacALfsdnEmmarK-6V7JsbXFL2.hoZ8','site' => 'PayPal'),
            array('id' => '4','name' => 'mode','value' => 'sandbox','site' => 'PayPal'),
            array('id' => '5','name' => 'client','value' => 'ASeeaUVlKXDd8DegCNSuO413fePRLrlzZKdGE_RwrWqJOVVbTNJb6-_r6xX9GdsRUVNc8butjTOIK_Xm','site' => 'PayPal'),
            array('id' => '6','name' => 'secret','value' => 'ENCGBUb_QSpHzGIAxjtSehkRIAI9lOELOiZUUjZUTEdjACeILOUUG58ijBNsuzdV-RPyDbHNxYTPkapn','site' => 'PayPal'),
            array('id' => '7','name' => 'publish','value' => 'pk_test_Ah4G3SPQmw26w8hLzeADVWFN00LBtdU3x7','site' => 'Stripe'),
            array('id' => '8','name' => 'secret','value' => 'sk_test_p86LCSnwIC1rjqL9Rl0ZB4Fq00Be8ULmHu','site' => 'Stripe'),
            array('id' => '9','name' => 'client_id','value' => 'ca_FhQtyGctj6A1vWjrtQvNmpagvWM2XRJh','site' => 'Stripe')
    	]);
    }
}
