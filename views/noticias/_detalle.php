<?php
use app\helpers\BuscaImagen;
use app\helpers\DomainExtractor;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Movimientos;
use app\models\Comentarios;
use yii\web\View;
?>

<style media="screen">
    #boton-mueveme{
        margin: 10px;
    }
    #meneos{
        text-align: center;
        font-weight: bold;
        color: #ffffff
    }
    .meneos{
          text-align: center;
    }
    #cuadrado {
        height: 90px;
        width: 100px;
        background-color: #84cbe3;
        border-radius: 7px;
        display: inline-block;
    }
</style>
<?php
$sizeCol = ($imagen = BuscaImagen::noticia($model->id)) ? 7 : 9;
?>
<div class="row ">
    <div class="col-md-2" style='padding:70px;'>
        <?php $contador = 0?>
        <?php foreach ($model->movimientos as $movimiento): ?>
            <?php if ($movimiento->noticia_id === $model->id) {
                $contador++;
            }?>
        <?php endforeach; ?>

        <div id="cuadrado">
        <button type="submit" id="boton-mueveme" name="button" class='btn btn-info' data-noticia="<?= $model->id ?>"
            data-contador='<?= $contador ?>'>Muévelo</button>
        <?php

        if (!Yii::$app->user->isGuest) {
            $url = Url::to(['noticias/menear']);
            $usuario_id = Yii::$app->user->identity->id;
            $js = <<<EOF
            $(':submit[data-noticia]').click(function(e){
                e.preventDefault();
                let noticia_id = $(this).data('noticia');
                let contador = $(this).data('contador');
                $.ajax({
                    method: 'GET',
                    url: '$url',
                    data: {usuario_id: $usuario_id, noticia_id: noticia_id, contador: contador},
                    success: function(result){
                        if (result > contador) {
                            $(e.target).next().empty();
                            $(e.target).next().html(result);
                            $(e.target).next().append(' meneos');
                        }else {
                            alert('No se ha podido mover la noticia, ya la has movido!');
                        }
                    }
                });
            });
EOF;

            $this->registerJs($js);
        }
        ?>
        <p name='meneos' id="meneos"><?=$contador?> meneos</p>
    </div>
</div>
    <div class="col-md-<?= $sizeCol  ?>">
        <h3><?= Html::a($model->titulo, $model->url) ?></h3>
        <p>por <strong><?= $model->usuario->nombre ?></strong> a <strong><?= DomainExtractor::fromUrl($model->url) ?></strong>  publicado <?= Yii::$app->formatter->asDatetime($model->created_at)  ?></p>
        <p><?= $model->descripcion ?></p>
        <p>| <strong>Categoria: <?= $model->categoria->categoria ?></strong></p>
        <p><strong> <?= Html::a($model->numeroComentarios().' comentarios', ['noticias/view', 'id' => $model->id])  ?></strong> </p>
    </div>

<?php if ($imagen): ?>
    <div class="col-md-3">
        <?= Html::img(
            Yii::getAlias('@uploads/') . $imagen,
            ['class' => 'img-thumbnail embed-responsive-item']
            ) ?>
    </div>
<?php endif; ?>
</div>
<br>
<!--Hago esta comprobación para que no me pinte la caja de comentarios
en portada, ni nuevas, tan solo cuando entre a ver los comentarios de una noticia.-->
<?php if (isset($_GET['id']) && !Yii::$app->user->isGuest): ?>

<div class="row">
    <div class="col-md-offset-1">
        <?php
        $comentario = new Comentarios();
        $comentario->noticia_id = $model->id;
        $comentario->usuario_id = Yii::$app->user->identity->id;
        $comentario->texto = '';
        $comentario->comentario_id = null;

        $form = ActiveForm::begin([
            'method' => 'POST',
            'action' => Url::to(['comentarios/create']),
            ]) ?>
            <?= $form->field($comentario, 'texto')->textarea()->label('Comentar') ?>
            <?= $form->field($comentario, 'comentario_id')->hiddenInput()->label(false) ?>
            <?= $form->field($comentario, 'noticia_id')->hiddenInput()->label(false) ?>
            <?= $form->field($comentario, 'usuario_id')->hiddenInput()->label(false) ?>
            <button class="btn btn-xs btn-success" type="submit" name="button">Comentar</button>
        <?php ActiveForm::end() ?>

    </div>
</div>
<?php endif; ?>
