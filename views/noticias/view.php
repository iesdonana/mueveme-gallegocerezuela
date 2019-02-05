<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Noticias */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Noticias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="noticias-view">

    <div class="">
        <?= $this->render('_detalle', [
            'model' => $model,
            ]) ?>
    </div>
    <div style="padding-top:50px">
        <?= $this->render('/comentarios/_comentarios_todos',[
            'comentarios' => $comentarios,
            ])  ?>
    </div>

</div>
