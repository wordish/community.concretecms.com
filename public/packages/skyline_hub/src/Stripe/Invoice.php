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
        if ($status == 'paid') {
            $badge->class('badge badge-success')->setValue(t('Paid'));
        } else if ($status == 'open') {
            $badge->setValue(t('Due'));
        } else if ($status == 'draft') {
            $badge->class('badge badge-light')->setValue(t('Pending'));
        }
        return $badge;
    }



}
