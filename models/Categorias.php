<?php

namespace app\models;

/**
 * This is the model class for table "categorias".
 *
 * @property int $id
 * @property string $categoria
 *
 * @property Noticias[] $noticias
 */
class Categorias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoria'], 'required'],
            [['categoria'], 'string', 'max' => 255],
            [['categoria'], 'unique'],
        ];
    }

    public static function categoriasDisponibles()
    {
        return static::find()->select('categoria')->indexBy('id')->column();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoria' => 'Categoria',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoticias()
    {
        return $this->hasMany(Noticias::className(), ['categoria_id' => 'id'])->inverseOf('categoria');
    }
}
