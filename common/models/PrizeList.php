<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%prize__list}}".
 *
 * @property int $id Ид
 * @property string $prize_name Приз
 */
class PrizeList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%prize__list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prize_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Ид',
            'prize_name' => 'Приз',
        ];
    }
}
