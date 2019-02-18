<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Comentarios;
use yii\web\Controller;

/**
 * ComentariosController implements the CRUD actions for Comentarios model.
 */
class CategoriasController extends Controller
{
    /**
     * Lists all Comentarios models.
     * @return mixed
     * @param null|mixed $q
     * @param null|mixed $id
     */
    public function actionList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($q !== null) {
            $out['results'] = Categorias::find()
                ->select('id, categoria AS text')
                ->where(['ilike', 'categoria', $q])
                ->limit(20)
                ->asArray()
                ->all();
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Categorias::find($id)->categoria];
        }
        return $out;
    }
}
