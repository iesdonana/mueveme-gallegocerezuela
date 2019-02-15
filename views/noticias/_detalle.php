<?php
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
<div class="row">
    <div class="col-md-2" style='padding:70px'>
        <?php $contador = 0?>
        <?php foreach ($model->movimientos as $movimiento): ?>
            <?php if ($movimiento->noticia_id === $model->id) {
                $contador++;
            }?>
        <?php endforeach; ?>
        <button type="submit" name="button" class='btn btn-info' data-noticia="<?= $model->id ?>"
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
        }else {

        }
        ?>
        <p name='meneos' id="meneos" class='col-md-offset-3'><?=$contador?> meneos</p>
    </div>
    <div class="col-md-9">
        <h3><?= Html::a($model->titulo, $model->url) ?></h3>
        <p>por <strong><?= $model->usuario->nombre ?></strong> a <strong><?= DomainExtractor::fromUrl($model->url) ?></strong>  publicado <?= Yii::$app->formatter->asDatetime($model->created_at)  ?></p>
        <p><?= $model->descripcion ?></p>
        <p>| <strong>Categoria: <?= $model->categoria->categoria ?></strong></p>
        <p><strong> <?= Html::a($model->numeroComentarios().' comentarios', ['noticias/view', 'id' => $model->id])  ?></strong> </p>

    </div>
</div>
