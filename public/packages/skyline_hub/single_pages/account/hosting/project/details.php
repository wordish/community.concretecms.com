<?php

defined('C5_EXECUTE') or die('Access Denied.');


View::element('account/breadcrumb', [], 'skyline_hub');

/**
 * @var $hostingSite \PortlandLabs\Skyline\Site\Site
 */
$subscription = $hostingSite->getSubscription();

?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="page-header">
                <h1 class="page-title mt-0"><?= $hostingSite->getName() ?></h1>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <?= t('Details') ?>
                </div>
                <div class="card-body">
                    <?php
                    View::element('account/site_table', ['hostingSites' => [$hostingSite]], 'skyline_hub');
                    ?>
                </div>
            </div>
            <?php
            if ($subscription) {
                $invoice = $subscription->latest_invoice;
                if ($subscription->status === 'trialing') {
                    $upcoming = $hostingSite->getUpcomingInvoice();
                    $amount = number_format($upcoming->amount_due / 100, 2);
                    ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <?= t('Trial') ?>
                        </div>
                        <div class="card-body">
                            <p class="mb-4"><?= t(
                                    'This site is in <b>trial</b> mode. Once the trial expires, an invoice for <b>$%s</b> will be created and you will have <b>%s</b> days to pay. An invoice for this service will be emailed to <b>%s</b>. If you cancel the trial before this period you will not have to pay. To cancel, click below.',
                                    $amount,
                                    $_ENV['SKYLINE_STRIPE_SUBSCRIPTION_DAYS_UNTIL_DUE'],
                                    $subscription->customer->email
                                ) ?></p>

                            <div class="mb-4">
                                <h4 class="mb-0"><?= t('Trial Expiration') ?></h4>
                                <div class="text-info"><?= (new DateTime())->setTimestamp(
                                        $subscription->trial_end
                                    )->format(
                                        'F d Y, g:i a'
                                    ) ?></div>
                            </div>

                            <!-- Button trigger modal -->
                            <div>
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#cancelTrial">
                                    <?= t('Cancel Trial') ?>
                                </button>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="cancelTrial" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="post"
                                          action="<?= $view->action('cancel_trial', $hostingSite->getId()) ?>">
                                        <?= $token->output('cancel_trial') ?>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"><?= t(
                                                        'Cancel Trial'
                                                    ) ?></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><?= t(
                                                        'To cancel this site, click below. The site\'s content will be removed and you will no longer have access to it.'
                                                    ) ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Confirm Termination
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php
                } else {
                    if (isset($subscription->latest_invoice) && $subscription->latest_invoice->amount_due > 0) { ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <?= t('Latest Invoice') ?>
                            </div>
                            <div class="card-body">
                                <?php
                                View::element(
                                    'account/invoice_table',
                                    ['invoices' => [$subscription->latest_invoice]],
                                    'skyline_hub'
                                );
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                } ?>
                <?php
            } ?>

            <?php
            if (in_array(
                $hostingSite->getStatus(),
                [
                    \PortlandLabs\Skyline\Site\Site::STATUS_USER_TERMINATED,
                    \PortlandLabs\Skyline\Site\Site::STATUS_TRIAL_TERMINATED
                ]
            )) { ?>

                <div class="card mb-3">
                    <div class="card-header">
                        <?= t('Site Suspended') ?>
                    </div>
                    <div class="card-body">
                        <?= t(
                            'This site was been taken offline on <b>%s</b>. After %s days its data will be removed. If you need data from this site or have questions about this process please <a href="%s">contact us</a>.',
                            (new DateTime())->setTimestamp($hostingSite->getCancelledTimestamp())->format(
                                'F d Y \a\t g:i a'
                            ),
                            $_ENV['SKYLINE_DAYS_AFTER_CANCELLING_TO_KEEP_SITE'],
                            $_ENV['SKYLINE_CANCELLATION_QUESTIONS_URL']
                        ) ?>
                    </div>
                </div>

            <?php
            } ?>
        </div>
    </div>
</div>
</main>
