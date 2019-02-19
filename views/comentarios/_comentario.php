<?php
use app\models\Comentarios;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\ActiveForm;
?>
<style media="screen">
.comentario {
    padding: 10px;
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

.votos {
    padding-left: 10px;
    padding-top: 3px;
}
</style>
<div class="row comentario">
    <div class="comentario-cuerpo">
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
        <div class="row votos" style="margin-button:10">

            <div class="col-md-1">
                <?= Html::beginForm(Url::to(['votos/registrar'])) ?>
                <?= Html::hiddenInput('votacion', 'true') ?>
                <?= Html::hiddenInput('comentario_id', $model->id) ?>
                <button class='btn btn-xs btn-success' type="submit">
                    <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?= $model->positivos  ?>
                </button>
                <?= Html::endForm() ?>
            </div>
            <div class="col-md-1">
                <?= Html::beginForm(Url::to(['votos/registrar'])) ?>
                <?= Html::hiddenInput('votacion', 'false') ?>
                <?= Html::hiddenInput('comentario_id', $model->id) ?>
                <button class='btn btn-xs btn-danger' type="submit" >
                    <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?= $model->negativos  ?>
                </button>
                <?= Html::endForm() ?>
            </div>
            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="contestar col-md-offset-9 col-md-1">
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
