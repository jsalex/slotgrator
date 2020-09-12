<?php

namespace common\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%lottery__result}}".
 *
 * @property int $id Ид
 * @property int $user_id Ид пользователя
 * @property int $prize_id Ид приза
 * @property string $create_date Дата выйгрыша
 * @property int $status Статус 
 * @property int $change_to_points
 */
class LotteryResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lottery__result}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'prize_id', 'object_id', 'prize_sum', 'status', 'change_to_points'], 'integer'],
            [['create_date'], 'datetime'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Ид',
            'user_id' => 'Ид пользователя',
            'prize_id' => 'Ид приза',
            'create_date' => 'Дата выйгрыша',
            'status' => 'Статус ',

            'change_to_points' => 'Change To Points',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getPrize()
    {
        return $this->hasOne(Prize::className(), ['id' => 'prize_id']);
    }
    public function getObject()
    {
        return $this->hasOne(PrizeList::className(), ['id' => 'object_id']);
    }
}
