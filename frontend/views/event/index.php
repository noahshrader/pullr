<? 
$this->registerJSFile('@web/js/event/index.js', \common\assets\CommonAsset::className());
?>
<h1>Events</h1>
<div class="report-events content-container">
    <div class="report-row">
        <div class='row  overview'>
            <div class="col-sm-3">
                <div id='piechart' data-amountraised='<?= round($amountRaised)?>' data-goalamount='<?= round($goalAmount) ?>'></div>
            </div>
            <div class='col-sm-9 text-center'>
                <div>
                    <h1>Overview</h1>
                </div>
                <div class='row'>
                    <div class='col-xs-3 '>
                        <div class='report-value'>$<?= number_format($amountRaised) ?></div>
                        <div class='report-label'>Raised</div>
                    </div>
                    <div class='col-xs-3'>
                        <div class='report-value'>$<?= number_format($goalAmount) ?></div>
                        <div class='report-label'>Goal</div>
                    </div>
                    <div class='col-xs-3'>
                        <div class='report-value'><?= number_format($numberOfDonations) ?></div>
                        <div class='report-label'>Donations</div>
                    </div>
                    <div class='col-xs-3'>
                        <div class='report-value'><?= number_format($numberOfUniqueDonors) ?></div>
                        <div class='report-label'>Unique Donors</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? foreach ($events as $event): ?>
        <div class='report-row'>
            <div class='row text-center'>
                <div class='col-sm-3'>
                    <div>
                        <h2><?= $event->name ?></h2>
                    </div>
                    <div>
                        <?= date('M j Y', $event->startDate) ?> - <?= date('M j Y', $event->endDate) ?>
                    </div>
                </div>
                <div class='col-sm-9'>
                    <div class='pullr-table'>
                        <div class='pullr-table-row'>
                            <div class='col-xs-3'>
                            </div>
                            <div class='col-xs-3'>
                                <div class='report-value'>$<?= number_format($event->amountRaised) ?></div>
                                <div class='report-label'>Raised</div>
                            </div>
                            <div class='col-xs-3'>
                                <div class='report-value'><?= number_format($event->numberOfDonations) ?></div>
                                <div class='report-label'>Donations</div>
                            </div>
                            <div class='col-xs-3'>
                                <a href='event/view?id=<?= $event->id ?>' class='btn btn-default'>View event</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='progress-container'>
                <span class='progress-percent'><?= number_format(100*$event->amountRaised / (max($event->goalAmount, 1)))?>%</span>
                <progress max="<?= $event->goalAmount ?>" value="<?= $event->amountRaised ?>"></progress>
            </div>
        </div>
    <? endforeach; ?>
</div>