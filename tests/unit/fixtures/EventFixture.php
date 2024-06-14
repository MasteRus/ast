<?php

namespace app\tests\unit\fixtures;

use app\models\activeRecord\Event;
use yii\test\ActiveFixture;

class EventFixture extends ActiveFixture
{
    public $modelClass = Event::class;
    public $dataFile = '@tests/_data/event.php';
}