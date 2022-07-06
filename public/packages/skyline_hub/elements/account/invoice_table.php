<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var $invoice \Stripe\Invoice[]
 */
?>

<table class="table">
    <thead>
    <tr>
        <th class="pt-0 pb-3 border-bottom"><?=t('AMOUNT')?></th>
        <th class="pt-0 pb-3 border-bottom"><?=t('INVOICE NUMBER')?></th>
        <th class="pt-0 pb-3 border-bottom"><?=t('DUE DATE')?></th>
        <th class="pt-0 pt-3 pb-3 border-bottom"><?=t('CREATED')?></th>
        <th class="pt-0 text-center pb-3 border-bottom"><?=t('STATUS')?></th>
        <th class="pt-0 pb-3 border-bottom"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($invoices as $invoice) {
        $invoiceDecorator = new \PortlandLabs\Skyline\Stripe\Invoice($invoice);
        ?>
        <tr>
            <td class="">$<?=$invoice->amount_due / 100?></td>
            <td><?=$invoice->number ?? '<span class="text-muted">' . t('Not Available') . '</span>'?></td>
            <td><?php if ($invoice->due_date) { ?><?=(new DateTime())->setTimestamp($invoice->due_date)->format('F d Y, g:i a')?><?php } else { ?><span class="text-muted"><?=t('None')?></span><?php } ?></td>
            <td><?=(new DateTime())->setTimestamp($invoice->created)->format('F d Y, g:i a')?></td>
            <td class="text-center"><?=$invoiceDecorator->getStatusBadge()?></td>
            <td class="text-nowrap text-right">
                <?php if (!in_array($invoice->status, ['draft'])) { ?>
                    <a href="<?=$invoice->invoice_pdf?>" class="btn btn-sm py-0 px-3 btn-secondary"><?=t('PDF')?></a>
                <?php } ?>
                <?php if (!in_array($invoice->status, ['draft', 'paid'])) { ?>
                    <a href="<?=$invoice->hosted_invoice_url?>" class="btn btn-primary py-0 px-3 btn-sm"><?=t('Pay')?></a>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

