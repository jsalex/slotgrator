<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user__points}}".
 *
 * @property int $id
 * @property int $user_id
 * @property double $balance
 */
class UserPoints extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user__points}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['user_points'], 'number'],
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
            'user_points' => 'Balance',
        ];
    }
}
