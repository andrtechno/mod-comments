<?php

namespace panix\mod\comments\models;

use Yii;
use yii\base\Model;
use panix\engine\data\ActiveDataProvider;
use panix\mod\comments\models\Comments;

/**
 * CommentsSearch represents the model behind the search form about `panix\mod\comments\models\Comments`.
 */
class CommentsSearch extends Comments {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['text','created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Comments::find();

        $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'sort'=> self::getSort(),
                ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);
        $query->andFilterWhere(['like', 'DATE(created_at)', $this->created_at]);



        return $dataProvider;
    }
    public static function getSort() {
        $sort = new \yii\data\Sort([
            'attributes' => [
                'created_at',


            ],
        ]);
        return $sort;
    }
}
