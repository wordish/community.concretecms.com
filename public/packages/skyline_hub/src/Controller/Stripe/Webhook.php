<?php

namespace PortlandLabs\Skyline\Controller\Stripe;

use Concrete\Core\Http\Request;
use PortlandLabs\Skyline\Stripe\Webhook\WebhookService;
use Stripe\Subscription;
use Stripe\Webhook as StripeWebhook;
use Symfony\Component\HttpFoundation\Response;

class Webhook
{

    protected $request;

    public function __construct(Request $request, WebhookService $webhookService)
    {
        $this->request = $request;
        $this->webhookService = $webhookService;
    }

    public function receive()
    {
        $payload = $this->request->getContent();
        $secret = $_ENV['SKYLINE_STRIPE_WEBHOOK_SECRET_KEY'];
        $sigHeader = $this->request->server->get('HTTP_STRIPE_SIGNATURE');
        try {
            $event = StripeWebhook::constructEvent(
                $payload, $sigHeader, $secret
            );
            switch ($event->type) {
                case 'customer.subscription.updated':
                    $object = $event->data->object;
                    $this->webhookService->updateSubscriptionStatus($object->id, $object->status);
                    break;
            }
        } catch(\Exception $e) {
            // Invalid payload
            return new Response($e->getMessage(), 400);
        }

        return new Response(t("Event Received"), 200);
    }
}
