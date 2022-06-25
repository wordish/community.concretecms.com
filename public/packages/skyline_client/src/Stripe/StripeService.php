<?php

namespace PortlandLabs\Skyline\Stripe;

use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\User\UserInfo;
use Stripe\Customer;
use Stripe\StripeClient;

class StripeService
{

    /**
     * @var StripeClient
     */
    protected $stripe;

    /**
     * StripeService constructor.
     * @param StripeClient $stripe
     */
    public function __construct(StripeClient $stripe)
    {
        $this->stripe = $stripe;
    }

    public function getCustomer(UserInfo $user): ?Customer
    {
        $email = $user->getUserEmail();
        $customerId = $user->getAttribute("stripe_customer_id");
        if ($customerId) {
            $customer = $this->stripe->customers->retrieve($customerId);
        } else {
            $customer = $this->stripe->customers->create(
                [
                    'email' => $email,
                ]
            );
            if ($customer) {
                $user->setAttribute('stripe_customer_id', $customer->id);
            }
        }
        return $customer;
    }



}
