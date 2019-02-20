<?php

use app\models\Comentarios;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
    #cuadrado {
        height: 100px;
        width: 100px;
        background-color: #84cbe3;
        border-radius: 7px;
        display: inline-block;
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
<?php if (!Yii::$app->user->isGuest): ?>
    <!-- modal -->
    <?php $respuesta = new Comentarios([
        'usuario_id' => Yii::$app->user->identity->id,
        'noticia_id' => $model->id,
    ]);
    ?>

    <div class="modal fade" id="respuestaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Nueva respuesta</h4>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['comentarios/responder-comentario']),
                        ]) ?>
                    <?= $form->field($respuesta, 'noticia_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($respuesta, 'comentario_id')->hiddenInput(['class' => 'respuesta_id'])->label(false) ?>
                    <?= $form->field($respuesta, 'usuario_id')->hiddenInput()->label(false) ?>
                        <?= $form->field($respuesta, 'texto')->textarea()->label(false) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar respuesta</button>
                </div>
                <?php $form->end() ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$js = <<<EOF
$('#respuestaModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)
    modal.find('.respuesta_id').val(id)
})
EOF;

$this->registerJs($js);
?>
