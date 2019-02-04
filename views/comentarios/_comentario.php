<?php
use app\models\Comentarios;

    use yii\helpers\Html;
?>
<style media="screen">
    .comentario {
        margin: 15px;
        padding: 5px;
        border: 1px solid #e6e6ff;
        border-radius: 5px;
    }

    .comentario-cuerpo {
        background-color: #fff5ed;
        position: relative;
        padding: 5px 10px 5px 30px;
        border-radius: 2px;
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
    <div class="votos">
    <button class='btn btn-xs btn-success' type="button" name="button">
        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <?= $model->positivos  ?>
     </button>
    <button class='btn btn-xs btn-danger' type="button" name="button">
        <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <?= $model->negativos  ?>
    </button>
</div>
</div>
