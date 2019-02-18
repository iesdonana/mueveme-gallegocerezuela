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
<style media="screen">
    ul {
        list-style:none;
    }
</style>
<div class="noticias-view">

    <div class="">
        <?= $this->render('_detalle', [
            'model' => $model,
            ]) ?>
    </div>
</div>
<div class="row">
<?php
pintarComentarios($comentarios, $this);

function pintarComentarios($comentarios, $vista)
{
    ?>
    <?php if ($comentarios) : ?>
        <ul>
        <?php foreach ($comentarios as $comentario) : ?>
            <li>
                <?= $vista->render('/comentarios/_comentario',[
                    'model' => $comentario
                    ]) ?>
                <?php pintarComentarios($comentario->comentariosHijos(), $vista)?>
            </li>
        <?php endforeach ?>
        </ul>
<?php endif;
}
?>
</div>
