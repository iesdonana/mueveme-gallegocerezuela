<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2

/* @var $this yii\web\View */
/* @var $model app\models\Noticias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="noticias-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuario_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'categoria_id')->widget(Select2::classname(), [
    'data' => \app\models\Categorias::categoriasDisponibles(),
    'options' => ['placeholder' => 'Selecciona una categoria...'],
    'pluginOptions' => [
        'allowClear' => true
        ],
    ]);
?>


    <?= $form->field($imagenForm, 'imageFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
