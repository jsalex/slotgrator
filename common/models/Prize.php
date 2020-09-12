<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%prize}}".
 *
 * @property int $id
 * @property int $prize_type
 * @property double $prize_value
 */
class Prize extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%prize}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prize_type'], 'string'],
            [['prize_value'], 'string'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prize_type' => 'Prize Type',
            'prize_value' => 'Prize Value',
        ];
    }
}
