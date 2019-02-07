<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<h2>Puedes visitar nuestra página haciendo click abajo en el enlace.</h2>
<?= Html::a('Go to home page', Url::home('http')) ?>
