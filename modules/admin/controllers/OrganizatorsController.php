<?php

namespace app\modules\admin\controllers;

use app\models\activeRecord\Organizator;
use app\models\search\OrganizatorSearch;
use app\modules\admin\models\forms\EventForm;
use app\modules\admin\models\forms\OrganizatorForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * OrganizatorsController implements the CRUD actions for Organizator model.
 */
class OrganizatorsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
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

    public function actionIndex()
    {
        $searchModel = new OrganizatorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new OrganizatorForm();

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

    public function actionUpdate(int $id)
    {
        $organizator = $this->findModel($id);
        $model = new OrganizatorForm();
        $model->setAttributes($organizator->attributes);
        $model->id = $organizator->id;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $organizatorId = $model->save();
            if (!empty($organizatorId)) {
                return $this->redirect(['view', 'id' => $organizatorId]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAutocomplete(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [
            'results' => [
                'id' => '',
                'text' => '',
            ],
        ];
        $str = Yii::$app->request->get('str', '');
        if ($str !== '') {
            $query = Organizator::find()
                ->select('id, fullname AS text')
                ->where(['like', 'LOWER(fullname)', mb_strtolower($str)])
                ->limit(20)
                ->asArray();
            $out['results'] = $query->all();
        }
        return $out;
    }

    protected function findModel(int $id): Organizator
    {
        if (($model = Organizator::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
