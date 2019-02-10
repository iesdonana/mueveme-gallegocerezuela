<?php

/* @var $this \yii\web\View view component instance */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<h2>Email para recordar el nick de su cuenta.</h2>
<?php $form = ActiveForm::begin();?>

<h4>El nick de su cuenta es:</h4>

<?= Html::encode($model->nombre) ?>


<?php ActiveForm::end() ?>
