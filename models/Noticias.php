<?php

namespace app\models;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "noticias".
 *
 * @property int $id
 * @property string $titulo
 * @property string $descripcion
 * @property string $url
 * @property int $usuario_id
 * @property int $categoria_id
 * @property string $created_at
 *
 * @property Comentarios[] $comentarios
 * @property Movimientos[] $movimientos
 * @property Usuarios[] $usuarios
 * @property Usuarios $usuario
 */
class Noticias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'noticias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'descripcion', 'url', 'usuario_id'], 'required'],
            [['descripcion'], 'string'],
            [['usuario_id', 'categoria_id'], 'default', 'value' => null],
            [['usuario_id', 'categoria_id'], 'integer'],
            [['created_at'], 'safe'],
            [['titulo', 'url'], 'string', 'max' => 255],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Cabecera',
            'descripcion' => 'Descripción',
            'url' => 'Url',
            'categoria_id' => 'Categoria',
            'usuario_id' => 'Usuario',
            'created_at' => 'Creado el',
        ];
    }


    public function numeroComentarios()
    {
        return Comentarios::find()->where(['noticia_id' => $this->id])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['noticia_id' => 'id'])->inverseOf('noticia');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovimientos()
    {
        return $this->hasMany(Movimientos::className(), ['noticia_id' => 'id'])->inverseOf('noticia');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'usuario_id'])->viaTable('movimientos', ['noticia_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('noticias');
    }
    public function getCategoria()
    {
        return $this->hasOne(Categorias::className(), ['id' => 'categoria_id'])->inverseOf('noticias');
    }

    public function verificarPropietario()
    {
        if ($this->usuario->id != Yii::$app->user->id) {
            throw new ForbiddenHttpException('Acción prohibida', 1);
        }

        return $this;
    }
}
