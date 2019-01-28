<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "movimientos".
 *
 * @property int $usuario_id
 * @property int $noticia_id
 *
 * @property Noticias $noticia
 * @property Usuarios $usuario
 */
class Movimientos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'noticia_id'], 'required'],
            [['usuario_id', 'noticia_id'], 'default', 'value' => null],
            [['usuario_id', 'noticia_id'], 'integer'],
            [['usuario_id', 'noticia_id'], 'unique', 'targetAttribute' => ['usuario_id', 'noticia_id']],
            [['noticia_id'], 'exist', 'skipOnError' => true, 'targetClass' => Noticias::className(), 'targetAttribute' => ['noticia_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Usuario ID',
            'noticia_id' => 'Noticia ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoticia()
    {
        return $this->hasOne(Noticias::className(), ['id' => 'noticia_id'])->inverseOf('movimientos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('movimientos');
    }
}
