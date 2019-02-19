<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuarios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Usuarios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nombre',

            ['class' => 'yii\grid\ActionColumn', 'buttons' => [
                'bann' => function ($url, $model, $key) {
                    return
                    Yii::$app->user->identity->nombre === 'admin'
                    ? Html::a('Banear', Url::to(['usuarios/banear', 'id'=>$model->id]))
                    : '';
                },
    ], 'template' => '{view} {update} {delete} {bann}'],
        ],
    ]); ?>
</div>
