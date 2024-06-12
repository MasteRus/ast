<?php

namespace app\modules\front\controllers;

use yii\web\Controller;

/**
 * Default controller for the `front` module
 */
class DefaultController extends Controller
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
