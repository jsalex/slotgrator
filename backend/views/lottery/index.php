<?php

use yii\helpers\Html;
use \yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\LotteryResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lottery Results';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-result-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lottery Result', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user.username',
            'prize.prize_value',
            'create_date',
            'status',
            'change_to_points',
            'object.prize_name',
            'prize_sum',

            ['class' => 'yii\grid\ActionColumn',
               'template' => '{view} {update} {delete} {mail}',
               'buttons' => [
        'mail' => function ($url, $model, $key) {
        if($model->status == 0 && $model->object_id != 0) {
            $title = 'Отправить подарок';
            $options = [
                'title' => $title,
                'aria-label' => $title,
                'data-pjax' => '0',
                'id' => $key
            ];
            $url = Url::to(['lottery/mail', 'id' => $key]);
            //Для стилизации используем библиотеку иконок
            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-envelope"]);
            return Html::a($icon, $url, $options);
        }elseif( $model->object_id == 0){
            $title = 'Подарок нельзя отправить';
            $options = [
                'title' => $title,
                'aria-label' => $title,
                'data-pjax' => '0',
                'id' => $key
            ];
            $url= '#';
            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-minus"]);
            return Html::a($icon, $url, $options);
        }else{

            $title = 'Подарок уже отправлен';
            $options = [
                'title' => $title,
                'aria-label' => $title,
                'data-pjax' => '0',
                'id' => $key
            ];
            $url= '#';
            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-ok"]);
            return Html::a($icon, $url, $options);
        }
        },
    ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
