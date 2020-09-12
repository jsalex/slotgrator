<?php
use \yii\helpers\Url;
use yii\web\View;
?>

<p>Ваш приз: <?=$prize_sum?> (<?=$prize_value;?>)</p>

<?php if($prize_value == 'money'){?>
    <p style="margin-bottom:20px;"><button class="btn btn-lg btn-danger js-change__prize" >Поменять приз на баллы лояльности (<?=$prize_sum*$points_koef?> балла)</button></p>
    <?php

    $csrf = Yii::$app->request->csrfToken;
    $csrfParam = Yii::$app->request->csrfParam;

    $url = Url::toRoute(['lottery/change-prize']);

    $js =  <<< EOT_JS_CODE
$(".page").on('click','.js-change__prize',function (e) {
e.preventDefault();
const _this = $(this);
const lottery_id = {$lottery_result_id};
_this.hide();
_this.after('<p class="js-notify">Загружаю...</p>');

	     $.ajax({
                url: "{$url}",
                type: "POST",
                dataType: "html",
                data: {
                	//Узкое место и при кеше полной страницы параметр лучше грузить из head через jquery
                	"{$csrfParam}" : "{$csrf}",
                	"id":lottery_id,
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
    $this->registerJs($js, View::POS_READY, 'change-prize');

 }?>
