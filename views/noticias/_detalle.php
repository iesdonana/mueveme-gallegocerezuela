<?php
use app\helpers\BuscaImagen;
use app\helpers\DomainExtractor;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Movimientos;
use yii\web\View;
?>

<style media="screen">
    #meneos{
        text-align: center;
    }
</style>
<?php
$sizeCol = ($imagen = BuscaImagen::noticia($model->id)) ? 7 : 9;
?>
<div class="row">
    <div class="col-md-2" style='padding:70px'>

        <button type="submit" name="button" class='btn btn-info'>Muévelo</button>

        <?php $contador = 0?>
        <?php foreach ($model->movimientos as $movimiento): ?>
            <?php if ($movimiento->noticia_id === $model->id) {
                $contador++;
            }?>
        <?php endforeach; ?>
        <?php
            // $url = Url::to(['noticias/menear']);
            // $usuario_id = Yii::$app->user->identity->id;
            // $noticia_id = $model->id;
            //
            // $this->registerJs("$(':submit').click(function(){
            //     $.ajax({
            //         url: ".$url.",
            //         type: 'post',
            //         data: {
            //             usuario_id: ".$usuario_id.",
            //             noticia_id: ".$noticia_id.",
            //         },
            //         success: function(data){
            //             $('#meneos').html(". $contador++ .");
            //         }
            //     })
            // });",View::POS_READY); ?>
        <p id='meneos' class='col-md-offset-3'><?=$contador?> meneos</p>
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
