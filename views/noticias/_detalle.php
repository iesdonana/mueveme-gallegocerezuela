<?php
use app\helpers\BuscaImagen;
use app\helpers\DomainExtractor;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<style media="screen">
    #meneos{
        text-align: center;
    }

    img {
      margin-top: 30px;
      width: 18%;
      height: auto;
    }
</style>
<div class="row">
    <div class="col-md-2" style='padding:70px'>
        <button type="submit" name="button" class='btn btn-info'>Muévelo</button>
        <?php $contador = 0?>
        <?php foreach ($model->movimientos as $movimiento): ?>
            <?php if ($movimiento->noticia_id === $model->id) {
                $contador++;
            }?>
        <?php endforeach; ?>
        <p id='meneos' class='col-md-offset-3'><?=$contador?> meneos</p>
    </div>
    <div class="col-md-7">
        <h3><?= Html::a($model->titulo, $model->url) ?></h3>
        <p>por <strong><?= $model->usuario->nombre ?></strong> a <strong><?= DomainExtractor::fromUrl($model->url) ?></strong>  publicado <?= Yii::$app->formatter->asDatetime($model->created_at)  ?></p>
        <p><?= $model->descripcion ?></p>
        <p>| <strong>Categoria: <?= $model->categoria->categoria ?></strong></p>
        <p><strong> <?= Html::a($model->numeroComentarios().' comentarios', ['noticias/view', 'id' => $model->id])  ?></strong> </p>
    </div>

    <div class="cl-md-3">
        <?php
            $urlImg = ($imagen = BuscaImagen::noticia($model->id))
            ?
            $imagen
            :
            'default.jpg';
        ?>
        <?= Html::img(
            Yii::getAlias('@uploads/') . $urlImg,
            ['class' => 'img-thumbnail embed-responsive-item']
            ) ?>
    </div>
</div>
<br>
<!--Hago esta comprobación para que no me pinte la caja de comentarios
en portada, ni nuevas, tan solo cuando entre a ver los comentarios de una noticia.-->
<?php if (isset($_GET['id'])): ?>

<div class="row">
    <div class="col-md-offset-1">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['comentarios/create'],
    ]); ?>

    <?= Html::textarea('texto','',['cols' => 100, 'rows' => 4]) ?>
    <?= Html::hiddenInput('usuario_id',$model->usuario->id) ?>
    <?= Html::hiddenInput('noticia_id',$model->id) ?>
    <?= Html::hiddenInput('comentario_id',null) ?>

    <div class="form-group">
        <?= Html::submitButton('Comentar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
</div>
<?php endif; ?>
