<?php
use \yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
$this->title = 'My Yii Application';

?>
<div class="site-index page">
<p>Баланс баллов лояльности: <?=$points;?></p>
    <div class="jumbotron">
        <h1>Розыгрыша призов!</h1>

        <p class="lead">Нажмите на кнопку и получите приз</p>

        <p style="margin-bottom:20px;"><button class="btn btn-lg btn-success js-take__prize">Получить приз</button></p>
        <div id="result"></div>
    </div>
</div>


<?php

$csrf = Yii::$app->request->csrfToken;
$csrfParam = Yii::$app->request->csrfParam;

$url = Url::toRoute(['lottery/take-prize']);

$js =  <<< EOT_JS_CODE

$(".page").on('click','.js-take__prize',function (e) {
e.preventDefault();
const _this = $(this);
_this.hide();
_this.after('<p class="js-notify">Загружаю...</p>');

	     $.ajax({
                url: "{$url}",
                type: "POST",
                dataType: "html",
                data: {
                	//Узкое место и при кеше полной страницы параметр лучше грузить из head через jquery
                	"{$csrfParam}" : "{$csrf}",
                	},
                success: function(data){
                console.log(data);
                    
                $('#result').html(data);
                $('.js-notify').remove();
                _this.show(); 
                },
                error: function () {
                    
                }
            });
        });

EOT_JS_CODE;
$this->registerJs($js, View::POS_READY, 'take-prize');