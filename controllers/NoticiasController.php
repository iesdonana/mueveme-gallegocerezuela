<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Comentarios;
use app\models\Movimientos;
use app\models\Noticias;
use app\models\NoticiasSearch;
use app\models\UploadForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * NoticiasController implements the CRUD actions for Noticias model.
 */
class NoticiasController extends Controller
{
    const MENEOS_MIN_PORTADA = 2;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'accessUpdateDelete' => [
            'class' => \yii\filters\AccessControl::className(),
            'only' => ['update', 'delete'],
            'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $noticia_id = Yii::$app->request->get('id');
                            return (Noticias::findOne($noticia_id))->usuario->id
                            ==
                            Yii::$app->user->identity->id;
                        },
                    ],
                ],
            ],
            'accessCreate' => [
            'class' => \yii\filters\AccessControl::className(),
            'only' => ['create'],
            'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'accessMenear' => [
            'class' => \yii\filters\AccessControl::className(),
            'only' => ['menear'],
            'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionPorCategoria($categoria_id)
    {
        $categoria = $this->buscarCategoria($categoria_id);

        $query = Noticias::find()
        ->filterWhere(['categoria_id' => $categoria->id])
        ->orderBy('created_at DESC');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('listar', [
            'dataProvider' => $provider,
            'titulo' => "Noticias de {$categoria->categoria}",
            'selected' => $categoria_id,
        ]);
    }

    public function actionCandidatas()
    {
        $query = Noticias::find()->orderBy('created_at DESC');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('listar', [
            'dataProvider' => $provider,
            'titulo' => 'Noticias Nuevas',
        ]);
    }

    public function actionPortada()
    {
        $query = Noticias::find()
        ->joinWith('movimientos')
        ->groupBy('id')
        ->having(['>', 'count(noticia_id)', self::MENEOS_MIN_PORTADA])
        ->orderBy('created_at DESC');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('listar', [
            'dataProvider' => $provider,
            'titulo' => 'Portada',
        ]);
    }

    /**
     * Displays a single Noticias model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $comentarios = Comentarios::findConVotos()
            ->where([
                'c.comentario_id' => null,
                'noticia_id' => $id,
            ])
            ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'comentarios' => $comentarios,
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new NoticiasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Noticias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $imagenForm = new UploadForm();
        $model = new Noticias();

        $model->usuario_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $imagenForm->imageFile = UploadedFile::getInstance($imagenForm, 'imageFile');
            if (!$imagenForm->upload($model->id)) {
                Yii::$app->session->setFlash([
                    'error',
                    'Error: La imagen no se ha podido registrar',
                ]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'imagenForm' => $imagenForm,
        ]);
    }

    public function actionSubir()
    {
        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->redirect(['site/index']);
            }
        }
        return $this->render('subir', ['model' => $model]);
    }

    /**
     * Updates an existing Noticias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Noticias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the Noticias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Noticias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Noticias::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function buscarCategoria($id)
    {
        if (($model = Categorias::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionMenear()
    {
        $model = new Movimientos();
        $numero = 4;
        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->redirect(['view', 'id' => $numero]);
    }
}
