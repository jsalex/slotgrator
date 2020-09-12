<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use common\models\LotteryResult;
use common\models\User;

class PaymentController extends \yii\console\Controller
{
    public function actionIndex()
    {

      //!!!ВАЖНО в силу того что доступа на апи не предоставленно - то контролерр написан только применро. Так же скорее всего будет вылетать ошибки он не разу не запускался.

     //По хорошему у пользователя должны быть какие-то реквизиты для карты или банка
    $LotteryResult = LotteryResult::find()->joinWith(['prize','user'])
                                            ->where([
                                                'status'=>0,
                                                'prize_value'=>'money',
                                                //Используем базовый статус пользователя 10 - чтоб работать только с теми кому можно отправлять
                                                'user_status'=>10,
                                            ])->limit( Yii::$app->params['QuantityForPay']);

        //запускаем мульти курл, хоть где то можем себе позволить многопоточность :)

        $multi = curl_multi_init();
        $channels = array();

        foreach($LotteryResult as $lot){
            $ch = curl_init();
            //Из данных сформируем урл для отправки в банк или сложим пост параметры
            $urk = 'https://yandex.ru?id='.$lot->id;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_multi_add_handle($multi, $ch);
            //Сложим в ключ наш ид результата
            $channels[$lot->id] = $ch;
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($multi, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($multi) == -1) {
                continue;
            }

            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        foreach ($channels as $key => $channel) {

            //Обрабатываем запросы
            $response = json_decode(curl_multi_getcontent($channel));
            //Скорее всего ответ прийдет в json, я надеюсь и я хочу чтобы остался обьект

            curl_multi_remove_handle($multi, $channel);

            //Если ошибок нету
            if(!$response->error){
                //Ищем нашу лотерею и поменяем ей статус на отправленно
                $Lottery = LotteryResult::findOne($key);
                $Lottery->status = 2;
                $Lottery->update();
                //Можно доаписать логи
            }else{
                //Обрабатываем ошибки
            }
        }

        curl_multi_close($multi);

    }

}