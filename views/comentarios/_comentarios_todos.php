
<div class="col-md-9 col-md-offset-1">
    <h4>Comentarios:</h4>

    <?php foreach ($comentarios as $comentario): ?>
        <?= $this->render('_comentario', [
            'model' => $comentario,
            ]) ?>

        <?php foreach ($comentario->comentarios as $comentarioHijo): ?>
            <div class="col-md-offset-1">
                    <?= $this->render('_comentario', [
                    'model' => $comentarioHijo,
                    ]) ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

</div>
