<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container">
    <div class="row">
        <h1>Recuperar Contraseña</h1>
    </div>
    <div class="row">
        <h4>Para recuperar la contraseña, debes proporcionar su email o nick:</h4>
    </div>
<br>
<div class="row">

    <?php $form = ActiveForm::begin([
        'action' => ['usuarios/recuperarcontra'],
    ]); ?>

    <div class="row">
    <?= Html::input('text','emailNombre') ?>
    </div>
    <br>
    <div class="form-group">
        <?= Html::submitButton('Recuperar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

</div>
