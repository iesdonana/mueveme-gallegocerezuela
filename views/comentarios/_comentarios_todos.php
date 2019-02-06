<?php

use yii\helpers\Url;

use yii\widgets\ActiveForm;

?>
<div class="col-md-9 col-md-offset-1">
    <h4>Comentarios:</h4>

    <?php foreach ($comentarios as $comentario): ?>
        <div class="row">
        <?= $this->render('_comentario', [
            'model' => $comentario,
            ]) ?>
        </div>

        <?php foreach ($comentario->comentariosHijos() as $comentarioHijo): ?>
            <div class="row">

            <div class="col-md-offset-1">
                    <?= $this->render('_comentario', [
                    'model' => $comentarioHijo,
                    ]) ?>
            </div>
        </div>
        <?php endforeach; ?>


        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="row">

            <div class="col-md-offset-1" style="padding:10px">
                <?php

                $comentario->comentario_id = $comentario->id;
                $comentario->usuario_id = Yii::$app->user->identity->id;
                $comentario->texto = '';
                $comentario->id = null;

                $form = ActiveForm::begin([
                    'method' => 'POST',
                    'action' => Url::to(['comentarios/responder-comentario']),
                    ]) ?>
                    <?= $form->field($comentario, 'texto')->textarea()->label('Contestar') ?>
                    <?= $form->field($comentario, 'comentario_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($comentario, 'noticia_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($comentario, 'usuario_id')->hiddenInput()->label(false) ?>
                    <button class="btn btn-xs btn-success" type="submit" name="button">Contestar</button>
                <?php ActiveForm::end() ?>
            </div>
        </div>
        <?php endif; ?>




    <?php endforeach; ?>
</div>
