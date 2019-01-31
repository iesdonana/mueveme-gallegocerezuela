<?php
use app\helpers\DomainExtractor;

use yii\helpers\Html;

function saca_dominio($url){

}
?>
<div class="row">
    <div class="col-md-2" style='padding:70px'>
        <button type="submit" name="button" class='btn btn-info'>Movévelo</button>
    </div>
    <div class="col-md-9">
        <h3><?= Html::a($model->titulo, $model->url) ?></h3>
        <p>por <strong><?= $model->usuario->nombre ?></strong> a <strong><?= DomainExtractor::fromUrl($model->url) ?></strong>  publicado <?= Yii::$app->formatter->asDatetime($model->created_at)  ?></p>
        <p><?= $model->descripcion ?></p>
        <p>| <strong>Categoria: <?= $model->categoria->categoria ?></strong></p>
        <p><strong> <?= Html::a($model->numeroComentarios().' comentarios', ['noticias/view', 'id' => $model->id])  ?></strong> </p>

    </div>
</div>
