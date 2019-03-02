<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NoticiasSearch represents the model behind the search form of `\app\models\Noticias`.
 */
class NoticiasSearch extends Noticias
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'usuario_id', 'categoria_id'], 'integer'],
            /*
            2. lo ponemos como seguro.
             */
            [['titulo', 'descripcion', 'url', 'created_at', 'usuario.nombre'], 'safe'],
        ];
    }

    public function attributes()
    {
        /*
        Lo agregamos como atributo
         */
        return array_merge(parent::attributes(), ['usuario.nombre']);
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
        3. Lo combinamos con la tabla usuarios, notese que se coloca el nombre
        de la relacion (usuario) y no el de la tabla (usuarios)
         */
        $query = Noticias::find()->joinWith('usuario');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);
        /*
        4. La ordenacion. Imprtante dentro de $dataProvider->sort->attributes[]
        se coloca el nombre del atributo(relacion.atributo) y en "asc" y "desc"
        se coloca el nombre de la columna de la base de datos(tabla.columna)
        */
        $dataProvider->sort->attributes['usuario.nombre'] = [
            'asc' => ['usuarios.nombre' => SORT_ASC],
            'desc' => ['usuarios.nombre' => SORT_DESC],
        ];

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
            ->andFilterWhere(['ilike', 'url', $this->url])
            /*
            5. Por ultimo agregamos la busqueda. Nota que la contruccion del
            array que va dentro del andFilterWhere() esta compuesto de la
            siguiente manera ['ilike', 'nombreTabla.nombreColumna', $this->getAttribute('nombreRelacion.atributo')]
             */
            ->andFilterWhere(['ilike', 'usuarios.nombre', $this->getAttribute('usuario.nombre')]);

        // var_dump($query->createCommand()->sql);
        // die();

        return $dataProvider;
    }
}
