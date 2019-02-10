<?php

/* @var $this \yii\web\View view component instance */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<h2>Puedes verificar tu usuario haciendo click abajo en el enlace.</h2>
<?php $form = ActiveForm::begin([
    'action' => Url::to(['usuarios/verificar'], true),
]);?>

<?= $form->field($model, 'nombre')->hiddenInput()->label(false)  ?>
<?= $form->field($model, 'token')->hiddenInput()->label(false)  ?>

<button type="submit" name="button">Verificar email</button>
<?php ActiveForm::end() ?>
