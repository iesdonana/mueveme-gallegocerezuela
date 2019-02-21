<?php
use app\models\Comentarios;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\ActiveForm;
?>
<style media="screen">

.right{
    float: right;
    margin-top: 7px;
}

.comentario {
    padding: 17px;
}

.comentario-cuerpo {
    background-color: #e8edff;
    position: relative;
    padding: 5px 10px 5px 30px;
    border-radius: 6px;
    -webkit-box-shadow: 7px 7px 45px -23px rgba(0,0,0,0.75);
    -moz-box-shadow: 7px 7px 45px -23px rgba(0,0,0,0.75);
    box-shadow: 7px 7px 45px -23px rgba(0,0,0,0.75);
}

.comentario-texto {
    padding-left: 10px;
}


</style>
<div class="row comentario">
    <div class="votos col-md-1">

        <div class="right">
            <?= Html::beginForm(Url::to(['votos/registrar'])) ?>
            <?= Html::hiddenInput('votacion', '1') ?>
            <?= Html::hiddenInput('comentario_id', $model->id) ?>
            <button class='btn btn-xs btn-success' type="submit">
                <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?= $model->positivos  ?>
            </button>
            <?= Html::endForm() ?>

            <?= Html::beginForm(Url::to(['votos/registrar'])) ?>
            <?= Html::hiddenInput('votacion', '-1') ?>
            <?= Html::hiddenInput('comentario_id', $model->id) ?>
            <button class='btn btn-xs btn-danger' type="submit" >
                <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?= $model->negativos  ?>
            </button>
            <?= Html::endForm() ?>
        </div>
    </div>
    <div class="comentario-cuerpo col-md-11">
        <div class="comentario-cabecera">
            <p>
                <?= Html::a($model
                ->usuario
                ->nombre, [
                    'usuarios/view',
                    'id' => $model->usuario->nombre
                    ]) ?>
                    <?= Yii::$app
                    ->formatter
                    ->asDateTime($model->created_at, 'short') ?>
            </p>
        </div>
        <div class="comentario-texto">
            <?= $model->texto  ?>
        </div>
    </div>

    <div class="">

    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="contestar col-md-offset-11 col-md-1">
            <?= Html::button('Contestar', [
                'class' => 'btn btn-primary btn-xs',
                'data-toggle' => 'modal',
                'data-target' => '#respuestaModal',
                'data-id' => $model->id,
                ]) ?>
        </div>
    <?php endif; ?>
    </div>
</div>
