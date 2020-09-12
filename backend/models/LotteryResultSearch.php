<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LotteryResult;

/**
 * LotteryResultSearch represents the model behind the search form of `common\models\LotteryResult`.
 */
class LotteryResultSearch extends LotteryResult
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'prize_id', 'status', 'change_to_points', 'object_id', 'prize_sum'], 'integer'],
            [['create_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LotteryResult::find()->joinWith(['user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,

            'prize_id' => $this->prize_id,
            'create_date' => $this->create_date,
            'status' => $this->status,
            'change_to_points' => $this->change_to_points,
            'object_id' => $this->object_id,
            'prize_sum' => $this->prize_sum,
        ]);

        return $dataProvider;
    }
}
