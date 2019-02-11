<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Votos */

$this->title = 'Update Votos: ' . $model->usuario_id;
$this->params['breadcrumbs'][] = ['label' => 'Votos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usuario_id, 'url' => ['view', 'usuario_id' => $model->usuario_id, 'comentario_id' => $model->comentario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="votos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
