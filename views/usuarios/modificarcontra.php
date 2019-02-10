<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $usuario app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuarios-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'action' => ['usuarios/modificarcontra'],
    ]); ?>

    <?= $form->field($usuario, 'nombre')->textInput(['maxlength' => true])->hiddenInput() ?>

    <?= $form->field($usuario, 'email')->textInput(['maxlength' => true])->hiddenInput() ?>

    <?= $form->field($usuario, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($usuario, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($this->title, ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
