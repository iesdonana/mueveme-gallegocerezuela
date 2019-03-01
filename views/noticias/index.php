<?php

use yii\grid\DataColumn;

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoticiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Noticias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="noticias-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Noticias', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'titulo',
            'descripcion:ntext',
            'url:url',
            'usuario_id',
            /*
             1. Aqui hemos agregado una columna más, con DataColumn podemos agregar las columnas que queramos
             */
            [
                'class' => DataColumn::class,
                'header' => 'Numero de Movimientos',
                'attribute' => 'nMovimientos',
                'value' => function ($model, $key, $index, $column){
                    return count($model->movimientos);
                },
            ],

            //'categoria_id',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
