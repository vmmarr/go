<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Publicaciones;
use DateTime;
use Yii;

/**
 * PublicacionesSearch represents the model behind the search form of `app\models\Publicaciones`.
 */
class PublicacionesSearch extends Publicaciones
{
    /**
     * {@inheritdoc}
     */
    public $buscar;
    public function rules()
    {
        return [
            [['id', 'usuario_id'], 'integer'],
            [['descripcion', 'buscar', 'created_at'], 'safe'],
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
        $query = Publicaciones::find()->orderBy(['created_at' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
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

        // $query->andFilterWhere(['ilike', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
