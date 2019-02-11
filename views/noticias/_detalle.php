<?php
use app\helpers\DomainExtractor;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<style media="screen">
    #meneos{
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-md-2" style='padding:70px'>

        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'action' => ['noticias/menear'],
        ]); ?>
            <button type="submit" name="button" class='btn btn-info'>Muévelo</button>
        <?php ActiveForm::end(); ?>

        <?php $contador = 0?>
        <?php foreach ($model->movimientos as $movimiento): ?>
            <?php if ($movimiento->noticia_id === $model->id) {
                $contador++;
            }?>
        <?php endforeach; ?>
        <p id='meneos' class='col-md-offset-3'><?=$contador?> meneos</p>
    </div>
    <div class="col-md-9">
        <h3><?= Html::a($model->titulo, $model->url) ?></h3>
        <p>por <strong><?= $model->usuario->nombre ?></strong> a <strong><?= DomainExtractor::fromUrl($model->url) ?></strong>  publicado <?= Yii::$app->formatter->asDatetime($model->created_at)  ?></p>
        <p><?= $model->descripcion ?></p>
        <p>| <strong>Categoria: <?= $model->categoria->categoria ?></strong></p>
        <p><strong> <?= Html::a($model->numeroComentarios().' comentarios', ['noticias/view', 'id' => $model->id])  ?></strong> </p>

    </div>
</div>
