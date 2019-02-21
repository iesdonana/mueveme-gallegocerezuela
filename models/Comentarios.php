<?php

namespace app\models;

/**
 * This is the model class for table "comentarios".
 *
 * @property int $id
 * @property string $texto
 * @property int $usuario_id
 * @property int $noticia_id
 * @property int $comentario_id
 * @property string $created_at
 *
 * @property Comentarios $comentario
 * @property Comentarios[] $comentarios
 * @property Noticias $noticia
 * @property Usuarios $usuario
 * @property Votos[] $votos
 * @property Usuarios[] $usuarios
 */
class Comentarios extends \yii\db\ActiveRecord
{
    public $_positivos;
    public $_negativos;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comentarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['texto', 'usuario_id', 'noticia_id'], 'required'],
            [['texto'], 'string'],
            [['usuario_id', 'noticia_id', 'comentario_id'], 'default', 'value' => null],
            [['usuario_id', 'noticia_id', 'comentario_id', 'positivos', 'negativos'], 'integer'],
            [['created_at'], 'safe'],
            [['comentario_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['comentario_id' => 'id']],
            [['noticia_id'], 'exist', 'skipOnError' => true, 'targetClass' => Noticias::className(), 'targetAttribute' => ['noticia_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['positivos', 'negativos']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'texto' => 'Texto',
            'usuario_id' => 'Usuario ID',
            'noticia_id' => 'Noticia ID',
            'comentario_id' => 'Comentario ID',
            'positivos' => 'Votos positivos',
            'negativos' => 'Votos negativos',
            'created_at' => 'Created At',
        ];
    }


    public function getPositivos()
    {
        return $this->_positivos;
    }
    public function getNegativos()
    {
        return $this->_negativos;
    }
    public static function findConVotos()
    {
        // $dataProvider = new \yii\data\SqlDataProvider([
        //     'sql' => 'select c.*, (select count(v.comentario_id) from votos v  where v.votacion = true and v.comentario_id = c.id) as votos_positivos,(select count(comentario_id) from votos v where v.votacion = false and v.comentario_id = c.id) as votos_negativos from comentarios c left join votos v  on c.comentario_id = v.comentario_id group by c.id',
        // ]);

        /*
        No sé hacerlo sin subconsultas, ya que al final lo que busco es contabilizar
        cuantos votos positivos hay y cuantas votos negativas hay, por separado.
        No me interesa saber el computo total tras sumar los positivos a los
        negativos.
         */

        $select = <<<'EOF'
        c.*, (SELECT COUNT(v.comentario_id)
                FROM votos v
               WHERE v.votacion = 1
                 AND v.comentario_id = c.id)
                  AS positivos,(SELECT COUNT(comentario_id)
                                  FROM votos v
                                 WHERE v.votacion = -1
                                   AND v.comentario_id = c.id)
                                   AS negativos
EOF;

        return self::find()
            ->select($select)
            ->from('comentarios c')
            ->leftJoin('votos v', 'c.id = v.comentario_id')
            ->groupBy('c.id');
    }

    public function comentariosHijos()
    {
        return $this
            ->findConVotos()
            ->where(['c.comentario_id' => $this->id])
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentario()
    {
        return $this->hasOne(self::className(), ['id' => 'comentario_id'])->inverseOf('comentarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(self::className(), ['comentario_id' => 'id'])->inverseOf('comentario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoticia()
    {
        return $this->hasOne(Noticias::className(), ['id' => 'noticia_id'])->inverseOf('comentarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('comentarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotos()
    {
        return $this->hasMany(Votos::className(), ['comentario_id' => 'id'])->inverseOf('comentario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'usuario_id'])->viaTable('votos', ['comentario_id' => 'id']);
    }
}
