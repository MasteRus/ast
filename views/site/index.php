<?php

/* @var $this yii\web\View */

/* @var $events \app\models\activeRecord\Event[] */

$this->title = Yii::t('app', 'Events list');
?>
<h1><?= $this->title ?></h1>
<div class="events-container">
    <?php
    foreach ($events as $event): ?>
        <div class="event-card">
            <div class="event-title">
                <a href="<?= Yii::$app->urlManager->createUrl(['site/view', 'id' => $event->id]) ?>">
                    <?= $event->name ?>
                </a>
            </div>
            <div class="event-date"><?= $event->planned_date ?></div>
            <div class="organizers">
                <?php
                foreach ($event->organizators as $organizator): ?>
                    <div class="organizer-card">
                        <div class="organizer-name"><?= $organizator->fullname ?></div>
                    </div>
                <?php
                endforeach; ?>
            </div>
        </div>
    <?php
    endforeach; ?>
</div>

