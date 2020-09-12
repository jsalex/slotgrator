<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Response;

use common\models\Prize;
use common\models\PrizeList;
use common\models\LotteryResult;
use common\models\UserPoints;

/**
 * Site controller
 */
class LotteryController extends Controller
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
                        'actions' => [
                            'error',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'take-prize',
                            'change-prize',

                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    private function generateNumber($data){
        $dataLength  = count($data);
        return mt_rand(0, $dataLength-1);
    }
    /**
     * Обработка розыгрыша
     *
     * @return mixed
     */
    public function actionTakePrize()
    {
        if(Yii::$app->request->isAjax) {
            $result = [];

            //Получим все призы из базы
            $PrizeTypies = Prize::find()->all();

            //Сгенерируем тип приза
            $generatePrizeTypeNumber = $this->generateNumber($PrizeTypies);

            //Получим приз
            $PrizeType = $PrizeTypies[$generatePrizeTypeNumber];
            $result['prize_value'] = $PrizeType['prize_value'];

            $LotteryResult = new LotteryResult();

            if ($PrizeType['prize_type'] == 'object') {

                //Получим все обьекты для призов из базы
                $AllObject = PrizeList::find()->all();

                //Сгенерируем тип приза
                $generateObjectNumber = $this->generateNumber($AllObject);

                //Выгранный приз
                $WinPrize = $AllObject[$generateObjectNumber];

                //Отправим на сохранение
                $LotteryResult->object_id = $WinPrize['id'];

                //Запишим для отправки в шаблон
                $result['name'] = $WinPrize['prize_name'];
            } else {

                //Загрузим интервалы в зависимости от типа приза
                $Intervals = Yii::$app->params[$PrizeType['prize_value']];

                //Сгенерируем баллы или деньги в интервале
                $GeneratePrizeSum = mt_rand($Intervals['min'], $Intervals['max']);

                //Отправим на сохранение
                $LotteryResult->prize_sum = $GeneratePrizeSum;

                //Запишим для отправки в шаблон
                $result['prize_sum'] = $GeneratePrizeSum;
                $result['prize_value'] = $PrizeType['prize_value'];

                if ($PrizeType['prize_value'] == 'money') {
                    $result['points_koef'] = Yii::$app->params['points_koef'];
                    $this->ChangePointsBalance($GeneratePrizeSum);
                }
            }
            $LotteryResult->user_id = Yii::$app->user->id;
            $LotteryResult->prize_id = $PrizeType['id'];
            $LotteryResult->create_date = date('Y-m-d H:i:s');

            //Сохраним в базу
            $LotteryResult->save(false);

            $result['lottery_result_id'] = $LotteryResult->id;

            //Вернем шаблон в зависимости от типа приза
            return $this->renderAjax('prize-' . $PrizeType['prize_type'], $result);

        }else{
            throw new \yii\web\NotFoundHttpException('404');
        }
    }
    /**
     * Изменение приза
     *
     * @return mixed
     */
    public function actionChangePrize()
    {
        if(Yii::$app->request->isAjax) {

        $data = Yii::$app->request->post();

        //Получим число
        $data['id'] =  preg_replace("/[^0-9]/", '', $data['id']);

        $UserId =  Yii::$app->user->id;
        $PointsKoef = Yii::$app->params['points_koef'];

        $LotteryResult =  LotteryResult::find()
                            ->where(['user_id'=>$UserId,
                                     'id'=>$data['id']
                            ])->one();
        if(!empty($LotteryResult)) {
            $LotteryResult->change_to_points = 1;
            $LotteryResult->prize_sum *= $PointsKoef;
            //Отправим сумму баллов на счет
            $this->ChangePointsBalance($LotteryResult->prize_sum);
            $LotteryResult->update();
            return $this->renderAjax('point-in-balance',[]);
        }else{
            return $this->renderAjax('error',[]);
        }
        }else{
            throw new \yii\web\NotFoundHttpException('404');
        }
    }
    /**
     * Изменение баланса баллов
     *
     * @return mixed
     */

    private function ChangePointsBalance($sum)
    {
        $UserId = Yii::$app->user->id;
        $UserPoints = UserPoints::findOne(['user_id'=>$UserId]);

        if(empty($UserPoints)){

            $UserPoints = new UserPoints();
            $UserPoints->user_id = $UserId;
            $UserPoints->user_points = $sum;
            $UserPoints->save(false);

        }else{
            $UserPoints->user_points += $sum;
            $UserPoints->update();
        }

    }
}
