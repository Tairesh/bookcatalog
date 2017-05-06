<?php

namespace app\controllers;

use Yii;
use app\models\Log;
use app\models\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LogController implements viewing Log models
 */
class LogController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'revert'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'revert' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Reverts catalog to submitted log event
     * @return mixed
     */
    public function actionRevert()
    {
        $eventId = Yii::$app->request->post('eventId');
        $event = Log::findOne($eventId);
        if (is_null($event)) {
            throw new NotFoundHttpException();
        }
        
        $eventsToCancel = Log::find()
                ->where(['>', 'id', $eventId])
                ->orderBy(['id' => SORT_DESC])
                ->all();
        
        foreach ($eventsToCancel as $event) {
            $event->cancel();
        }
        
        return $this->render('reverted', [
            'count' => count($eventsToCancel),
        ]);
    }

}
