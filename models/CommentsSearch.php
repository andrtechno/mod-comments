<?php

namespace panix\mod\comments\models;

use Yii;
use yii\base\Model;
use panix\engine\data\ActiveDataProvider;
use panix\mod\comments\models\Comments;

/**
 * CommentsSearch represents the model behind the search form about `panix\mod\comments\models\Comments`.
 */
class CommentsSearch extends Comments
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id'], 'integer'],
            [['user_name', 'user_email', 'handler_hash', 'handler_class', 'text', 'owner_title', 'user_agent', 'ip_create'], 'string'],
            [['text', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Comments::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => static::getSort(),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'text', $this->text]);
        $query->andFilterWhere(['like', 'user_name', $this->user_name]);
        $query->andFilterWhere(['like', 'user_email', $this->user_email]);
        $query->andFilterWhere(['like', 'object_id', $this->object_id]);
        $query->andFilterWhere(['like', 'handler_class', $this->handler_class]);
        $query->andFilterWhere(['like', 'handler_hash', $this->handler_hash]);
        $query->andFilterWhere(['like', 'DATE(created_at)', $this->created_at]);
        $query->andFilterWhere(['like', 'DATE(updated_at)', $this->updated_at]);


        return $dataProvider;
    }

    public static function getSort()
    {
        $sort = new \yii\data\Sort([
            'attributes' => [
                'id',
                'user_email',
                'user_name',
                'handler_class',
                'handler_hash',
                'object_id',
                'created_at',
                'updated_at',
                'ip_create',
                'text',
            ],
        ]);

        $sort->defaultOrder = ['id' => SORT_DESC];
        return $sort;
    }
}
