<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Guardadas;
use Yii;

/**
 * GuardadasSearch represents the model behind the search form of `app\models\Guardadas`.
 */
class GuardadasSearch extends Guardadas
{
    public $buscar;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'usuario_id', 'publicacion_id'], 'integer'],
            [['buscar'], 'safe'],
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
        $query = Guardadas::find()->where(['usuario_id' => Yii::$app->user->identity->id])->orderBy(['id' => SORT_DESC]);

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
            // 'id' => $this->id,
            // 'usuario_id' => $this->usuario_id,
            // 'direccion_id' => $this->direccion_id,
            'fecha' => $this->buscar,
            // ->orFilterWhere(['ilike', 'nombre', $this->buscar]);
        ]);
        return $dataProvider;
    }
}
