<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NoticiasSearch represents the model behind the search form of `\app\models\Noticias`.
 */
class NoticiasSearch extends Noticias
{
    /*
    2. Agregamos la variable que soportara al atributo en el que se cargara la busqueda.
     */
    public $nMovimientos;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*
            3. Lo agregamos a las reglas para que se pueda cargar de forma masiva
             */
            [['id', 'usuario_id', 'categoria_id', 'nMovimientos'], 'integer'],
            [['titulo', 'descripcion', 'url', 'created_at'], 'safe'],
        ];
    }

    public function attributes()
    {
        /*
        4. Lo agregamos como atributo
         */
        return array_merge(parent::attributes(), ['nMovimientos']);
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        /*
        5. Modificamos la query para poder buscar lo que queremos. Como queremos
        buscar por el numero de movimientos tenemos que conbinar movimientos y
        hacer luego un count() tenemos que agrupar por noticias.id
         */
        $query = Noticias::find()->joinWith('movimientos')->groupBy('noticias.id');

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
            'usuario_id' => $this->usuario_id,
            'categoria_id' => $this->categoria_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['ilike', 'titulo', $this->titulo])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'url', $this->url]);

        /*
        6. Agregamos al andFilterHaving() la condicion.
         */
        $query->andFilterHaving(['count(movimientos.noticia_id)' => $this->nMovimientos]);
        return $dataProvider;
    }
}
