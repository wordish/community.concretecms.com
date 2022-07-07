<?php

namespace PortlandLabs\Skyline\Stripe;

use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\User\UserInfo;
use PortlandLabs\Skyline\Entity\Site;
use Stripe\Customer;
use Stripe\Price;
use Stripe\StripeClient;
use Stripe\Subscription;
use Stripe\TestHelpers\TestClock;

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

    public function createTestCustomer(string $email, TestClock $testClock)
    {
        return $this->stripe->customers->create(
            [
                'email' => $email,
                'test_clock' => $testClock->id,
            ]
        );
    }

    public function getOpenInvoices(Site $site)
    {
        return $this->stripe->invoices->all(
            [
                'subscription' => $site->getSubscriptionId(),
                'status' => 'open',
            ]
        );
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

    public function getProductPrice(string $priceId): ?Price
    {
        return $this->stripe->prices->retrieve($priceId);
    }

    public function createTestClock(int $time)
    {
        return $this->stripe->testHelpers->testClocks->create(['frozen_time' => $time]);
    }

    public function createSubscription(Customer $customer, Price $price): ?Subscription
    {
        $subscription = $this->stripe->subscriptions->create(
            [
                'customer' => $customer->id,
                'collection_method' => 'send_invoice',
                'days_until_due' => $_ENV['SKYLINE_STRIPE_SUBSCRIPTION_DAYS_UNTIL_DUE'],
                'items' => [
                    [
                        'price' => $price->id,
                    ]
                ],
                'trial_period_days' => $_ENV['SKYLINE_SRIPE_TRIAL_PERIOD_DAYS'],
            ]
        );
        return $subscription;
    }

    public function cancelSubscription(string $subscriptionId): void
    {
        $this->stripe->subscriptions->cancel($subscriptionId);
    }


}
