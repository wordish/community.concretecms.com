<?php

namespace PortlandLabs\Skyline\Controller\Stripe;

use Concrete\Core\Http\Request;
use PortlandLabs\Skyline\Neighborhood\Neighborhood;
use Stripe\Webhook as StripeWebhook;
use Symfony\Component\HttpFoundation\Response;

class Webhook
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function receive()
    {
        $payload = $this->request->getContent();
        $secret = $_ENV['SKYLINE_STRIPE_WEBHOOK_SECRET_KEY'];
        $sigHeader = $this->request->server->get('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = StripeWebhook::constructEvent(
                $payload, $sigHeader, $secret
            );
        } catch(\Exception $e) {
            // Invalid payload
            return new Response($e->getMessage(), 400);
        }

        return new Response(t("Event Received"), 200);
    }
}
