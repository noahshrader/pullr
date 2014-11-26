<?php
use common\models\notifications\SystemNotification;

$this->title = 'Notifications';
?>

<div class="admin-content-wrap">

    <a href="notification/add" class="btn btn-primary"><i class="icon icon-add2"></i> Add new</a>

    <table id="notifications-management-table"  class="table dataTable">
        <thead>
        <tr>
            <th>
                Message
            </th>
            <th>
                Status
            </th>
            <th>
                Date
            </th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($notifications as $notice): ?>
            <tr data-id='<?= $notice->id; ?>'>
                <td><a href="notification/edit?id=<?= $notice->id; ?>"><?= $notice->message; ?></a></td>
                <td>
                    <?
                        $sLabelClass = 'label-default';
                        if ($notice->status == 'active') {
                            $sLabelClass = 'label-success';
                        } elseif($notice->status == 'deleted') {
                            $sLabelClass = 'label-danger';
                        }
                    ?>
                    <span class="label <?= $sLabelClass; ?>"><?= $notice->status; ?></span>
                </td>
                <td>
                    <?= date(SystemNotification::DATE_FORMAT, $notice->date); ?>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>

</div>