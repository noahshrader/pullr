<?php

use yii\helpers\Html;

$this->title = 'User info ' . $user->name;
?>
<div>
    <a href="user" class="btn btn-link"><h3><i class="icon icon-arrowleft"></i> Back to Users</h3></a>
</div>

<h1><?= Html::encode($this->title) ?></h1>

<div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#user-view" data-toggle="tab">Profile</a></li>
        <li><a href="#user-edit" data-toggle="tab">Edit</a></li>
    </ul>
    <div class="tab-content" style="margin-top: 30px" >
        <div class="tab-pane active" id="user-view">
            <?= $this->render('info', ['user' => $user]) ?>
        </div>
        <div class="tab-pane" id="user-edit">
            <?= $this->render('edit', ['user' => $user]) ?>
        </div>
    </div>
</div>