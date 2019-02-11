<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Votos */

$this->title = $model->usuario_id;
$this->params['breadcrumbs'][] = ['label' => 'Votos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="votos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'usuario_id' => $model->usuario_id, 'comentario_id' => $model->comentario_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'usuario_id' => $model->usuario_id, 'comentario_id' => $model->comentario_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'usuario_id',
            'comentario_id',
            'votacion:boolean',
        ],
    ]) ?>

</div>
