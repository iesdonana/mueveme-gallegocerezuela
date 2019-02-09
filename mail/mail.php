<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<h2>Puedes verificar tu usuario haciendo click abajo en el enlace.</h2>
<?= Html::a(
    'Verifique aquí su usuario',
    Url::to('/usuarios/verificar', true),
    ['data-method' => 'POST',
     'data-params' => [
        'nombre' => $nombre,
        'token' => $token,
    ], ]
);
    var_dump($nombre);
    var_dump($token);
?>
