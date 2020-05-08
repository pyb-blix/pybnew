<?php

/**
 * Stripe Payment
 *
 * @package     Makent
 * @subpackage  Stripe Payment
 * @category    Payment
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Repositories;

class StripePayment
{
	// Set Formatted Return Data With Default Values
	protected $return_data = array('status' => 'success', 'status_message' => '', 'transaction_id' => '');
	/**
     * Intialize Stripe with Secret key
     *
     */	
    public function __construct()
    {
    	$secret_key = view()->shared('stripe_secret_key');
        \Stripe\Stripe::setApiKey($secret_key);
    }

    /**
     * Create New Payment Method
     *
     * @param array $stripe_card Card Details
     *
     * @return Object $return_data With Status, error message or payment method id
     */
    public function createPaymentMethod($stripe_card)
    {
    	try {
            $token_response = \Stripe\PaymentMethod::create(array(
                "card" => $stripe_card,
                'type' => 'card'
            ));
        }
        catch(\Exception $e) {
            $this->updateReturnData('status', 'Failed');
        	$this->updateReturnData('status_message', $e->getMessage());
            return $this->getReturnData();
        }

        $this->updateReturnData('payment_method_id', $token_response->id);
        return $this->getReturnData();
    }

    /**
     * Create New Payment Intent
     *
     * @param array $purchaseData Related Purchase Data such as currency, amount, etc.,
     *
     * @return Object $return_data With Status, error message or payment intent id
     */
    public function CreatePayment($purchaseData)
    {
        // Create New PaymentIntent
        try {
            $payment_intent = \Stripe\PaymentIntent::create($purchaseData);
            $this->updatePaymentIntent($payment_intent);
        }
        catch(\Exception $e) {
        	$this->updateReturnData('status', 'Failed');
        	$this->updateReturnData('status_message', $e->getMessage());
        }

        return $this->getReturnData();
    }

    /**
     * Complete Payment Intent Based on Payment Intent Id
     *
     * @param String $payment_intent_id Id
     *
     * @return Object $return_data With Status, error message or transaction_id id
     */
    public function CompletePayment($payment_intent_id)
    {
        try {
            // Retrieve the PaymentIntent
            $payment_intent = $this->getPaymentIntent($payment_intent_id);
            $this->updatePaymentIntent($payment_intent);
        }
        catch(\Exception $e) {
        	$this->updateReturnData('status', 'Failed');
        	$this->updateReturnData('status_message', $e->getMessage());
        }

        return $this->getReturnData();
    }

    /**
     * Get Payment Intent Object by id
     *
     * @param String $payment_intent_id Payment Intent Id
     *
     * @return Object $paymentIntent
     */
    public function getPaymentIntent($payment_intent_id)
    {
    	$intent = \Stripe\PaymentIntent::retrieve(
            $payment_intent_id
        );
        return $intent;
    }

    /**
     * update Payment Intent, If Payment Intent Need confirmation then confirm payment
     *
     * @param Object $payment_intent Payment Intent
     *
     */
    protected function updatePaymentIntent($payment_intent)
    {
        if($payment_intent->status == 'succeeded') {
            $this->updateReturnData('status', 'success');
	    	$this->updateReturnData('transaction_id', $payment_intent->id);
	    	return $this->getReturnData();
        }

        $intent = $this->getPaymentIntent($payment_intent->id);
        $intent->confirm();

        $this->updatePaymentResponse($intent);
    }

    /**
     * update Payment Intent Response
     *
     * @param Object $intent Stripe PaymentIntentDetails
     *
     */
    protected function updatePaymentResponse($intent)
    {
	    # Note that if your API version is before 2019-02-11, 'requires_action'
	    # appears as 'requires_source_action'.
    	if ($intent->status == 'requires_action' && $intent->next_action->type == 'use_stripe_sdk') {
      		# Tell the client to handle the action
      		$this->updateReturnData('status', 'requires_action');
      		$this->updateReturnData('payment_intent_client_secret', $intent->client_secret);
    	}
    	else if ($intent->status == 'succeeded') {
			# The payment didn’t need any additional actions and completed!
			# Handle post-payment fulfillment
	    	$this->updateReturnData('status', 'success');
	    	$this->updateReturnData('transaction_id', $intent->id);
	    }
	    else {
	      	# Invalid status
	    	$this->updateReturnData('status', 'failed');
	    	$this->updateReturnData('status_message', 'Something went wrong with Secure Payment, Please Try again later.');
	    	// $this->updateReturnData('status_message', $intent->status);
	    }
	}

	/**
     * Create New Payment Method
     *
     * @param String $key Key in Array
     * @param String $value Value in Array
     *
     * @return null
     */
    protected function updateReturnData($key, $value = '')
    {
    	$this->return_data[$key] = $value;
    }

    /**
     * Get Formatted Return Data
     *
     * @return Object $return_data With return_data Array 
     */
    protected function getReturnData()
    {
    	return json_decode(json_encode($this->return_data));
    }
}