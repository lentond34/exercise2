<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\db\Expression;
use yii\filters\VerbFilter;
use app\models\Trip;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {

        $title = 'Trips';
        $dataProvider = new ActiveDataProvider(['query' => Trip::find()->where(['is', 'id', new Expression('NULL')])]);
        $request = Yii::$app->request;

        if ($request->get('tripCorpId') && $request->get('tripServiceId') && $request->get('airPortName')) {
            $tripCorpId = (int)$request->get('tripCorpId');
            $tripServiceId = (int)$request->get('tripServiceId');
            $airPortName = trim($request->get('airPortName'));

            $query = Trip::find()
                ->where(['cbt.trip.corporate_id' => $tripCorpId])
                ->innerJoinWith('tripServices')
                ->andWhere(['cbt.trip_service.service_id' => $tripServiceId])
                ->innerJoinWith('flightSegments')
                ->innerJoinWith('depAirportName')
                ->andWhere(['nemo_guide_etalon.airport_name.value' => $airPortName])
                ->distinct();

            //$raw = $query->createCommand()->getRawSql();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        }


        return $this->render(
            'index',
            [
                'title' => $title,
                'dataProvider' => $dataProvider
            ]
        );
    }



}
