<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user__balance}}".
 *
 * @property int $id
 * @property int $user_id
 * @property double $balance
 */
class UserBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user__balance}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['balance'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'balance' => 'Balance',
        ];
    }
}
