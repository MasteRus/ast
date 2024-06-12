<?php

namespace app\modules\admin\controllers;

use app\models\activeRecord\Event;
use app\models\search\EventSearch;
use app\modules\admin\models\forms\EventForm;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EventsController implements the CRUD actions for Event model.
 */
class EventsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Event models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Event model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new EventForm();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $id = $model->save();
            if (!empty($id)) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $event = $this->findModel($id);
        $model = new EventForm();
        $model->setAttributes($event->attributes);
        $model->id = $event->id;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $eventId = $model->save();
            if (!empty($eventId)) {
                return $this->redirect(['view', 'id' => $eventId]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Event
    {
        if (($model = Event::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
