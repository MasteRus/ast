<?php

namespace app\tests\unit\fixtures;

use app\models\activeRecord\Organizator;
use yii\test\ActiveFixture;

class OrganizatorFixture extends ActiveFixture
{
    public $modelClass = Organizator::class;
    public $dataFile = '@tests/_data/organizator.php';
}