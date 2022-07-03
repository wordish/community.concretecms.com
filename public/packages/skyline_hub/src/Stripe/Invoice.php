<?php

namespace PortlandLabs\Skyline\Stripe;

use HtmlObject\Element;

class Invoice
{

    /**
     * @var \Stripe\Invoice
     */
    protected $invoice;

    public function __construct(\Stripe\Invoice $invoice)
    {
        $this->invoice = $invoice;
    }


    public function getStatusBadge(): Element
    {
        $badge = new Element('span', '', ['class' => 'badge badge-info']);
        $status = $this->invoice->status;
        if ($status == 'open') {
            $badge->setValue(t('Due'));
        } else if ($status == 'draft') {
            $badge->class('badge badge-light')->setValue(t('Pending'));
        }

        /*if ($this->getStatus() === self::STATUS_INSTALLING) {
            $badge->setValue('Installing...');
        } else {
            if ($this->getStatus() === self::STATUS_INSTALLED) {
                if ($this->getSubscriptionStatus() == self::SUBSCRIPTION_STATUS_TRIALING) {
                    $badge->class('badge badge-warning');
                    $badge->setValue('Trial');
                }
            }
        }*/
        return $badge;
    }



}
