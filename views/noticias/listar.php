<?php

use app\models\Categorias;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoticiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $titulo;
$this->params['breadcrumbs'][] = $this->title;
?>

<h3>Busca por Categoria</h3>

<?= Html::beginForm(
    Url::to(['noticias/por-categoria']),
    'GET'
    )  ?>
    <div class="btn-group" role="group" aria-label="...">
        <?= Html::dropDownList(
            'categoria_id',
            isset($selected) ? $selected : null ,
            Categorias::categoriasDisponibles(),
            ['class' => 'form-control'])
        ?>
    </div>
    <div class="btn-group" role="group" aria-label="...">
        <?= Html::submitButton('', [
            'class' => 'glyphicon glyphicon-search form-control'
            ]
        ) ?>
    </div>
<?= Html::endForm() ?>

<div class="noticias-index">

    <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_detalle',
        ]) ?>
</div>
