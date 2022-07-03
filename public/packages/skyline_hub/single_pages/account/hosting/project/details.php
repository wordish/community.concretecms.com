<?php

defined('C5_EXECUTE') or die('Access Denied.');


View::element('account/breadcrumb', [], 'skyline_hub');

$subscription = $hostingSite->getSubscription();

?>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="page-header">
                    <h1 class="page-title mt-0"><?=$hostingSite->getName()?></h1>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <?=t('Details')?>
                    </div>
                    <div class="card-body">
                        <?php
                        View::element('account/site_table', ['hostingSites' => [$hostingSite]], 'skyline_hub');
                        ?>
                    </div>
                </div>
                <?php if ($subscription) {

                    $invoice = $subscription->latest_invoice;
                    if ($subscription->status === 'trialing') {
                        $upcoming = $hostingSite->getUpcomingInvoice();
                        $amount = number_format($upcoming->amount_due / 100, 2);
                        ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <?=t('Trial')?>
                            </div>
                            <div class="card-body">
                                <p><?=t('This site is in <b>trial</b> mode. Once the trial expires, an invoice for <b>$%s</b> will be created and you will have <b>%s</b> days to pay. Unpaid sites will be suspended and ultimately removed. An invoice for this service will be emailed to <b>%s</b>.',
                                    $amount, $_ENV['SKYLINE_STRIPE_SUBSCRIPTION_DAYS_UNTIL_DUE'], $subscription->customer->email
                                        )?></p>

                                <h4><?=t('Trial Expiration')?></h4>
                                <div class="text-info"><?=(new DateTime())->setTimestamp($subscription->trial_end)->format('F d Y, g:i a')?></div>
                            </div>
                        </div>

                    <?php } else if (isset($subscription->latest_invoice)) { ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <?=t('Latest Invoice')?>
                            </div>
                            <div class="card-body">
                                <?php
                                View::element('account/invoice_table', ['invoices' => [$subscription->latest_invoice]], 'skyline_hub');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php
                } ?>
            </div>
        </div>
    </div>
</main>
