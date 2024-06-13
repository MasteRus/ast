<?php

/* @var $this yii\web\View */

/* @var $event \app\models\activeRecord\Event */

$this->title = $event->name;
?>
<div class="event-view-container">
    <div class="event-view-title"><?= $event->name ?></div>
    <div class="event-view-date"><?= Yii::t('app', 'Planned date') ?> <?= $event->planned_date ?></div>
    <div class="event-description"><?= $event->description ?></div>
    <div class="organizers">
        <h3><?= Yii::t('app', 'Organizers') ?>:</h3>
        <?php foreach ($event->organizators as $organizator): ?>
            <div class="organizer-view-card">
                <div class="organizer-view-name"><?= $organizator->fullname ?></div>
                <div class="organizer-view-info"><?= $organizator->email ?></div>
                <div class="organizer-view-info"><?= $organizator->phone ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
