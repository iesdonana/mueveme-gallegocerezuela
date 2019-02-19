<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['update', 'create', 'banear'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->request->get('id') == Yii::$app->user->id;
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['banear'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->nombre === 'admin';
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Usuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuarios model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuarios(['scenario' => Usuarios::SCENARIO_CREATE]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->email(
                $model,
                'mail',
                'Confirmación de usuario',
                'Se ha enviado un correo de confirmación, por favor, consulte su correo.',
                'Ha habido un error al mandar el correo de confirmación.'
            );
            return;
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Usuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Usuarios::SCENARIO_UPDATE;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }


        $model->password = $model->password_repeat = '';
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Usuarios model.
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
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function email($model, $vista, $asunto, $mensajeEnvio, $mensajeError)
    {
        if (Yii::$app->mailer->compose($vista, [
            'model' => $model,
        ])
            ->setFrom('mueveme.gallego.cerezuela@gmail.com')
            ->setTo($model->email)
            ->setSubject($asunto)
            // ->setTextBody('Esto es una prueba.')
            // ->setHtmlBody('<h1>Esto es una prueba</h1>')
            ->send()) {
            Yii::$app->session->setFlash('success', $mensajeEnvio);
        } else {
            Yii::$app->session->setFlash('error', $mensajeError);
        }
        return $this->redirect(['site/index']);
    }

    public function actionVerificar()
    {
        //extract(Yii::$app->request->post('x_Usuarios'));
        //A jose se le manda por post x_Usuarios y a joni Usuarios.
        //Tenemos que extraerlo de diferente forma cada uno, no sabemos el motivo.
        //Esto de aqui abajo lo resuelve.

        $post = Yii::$app->request->post();
        $keys = preg_grep('/.*Usuarios.*/i', array_keys($post));


        extract(Yii::$app->request->post($keys[1]));

        $usuario = Usuarios::findByUserName($nombre);

        if (isset($usuario)) {
            if ($usuario->token === $token) {
                $usuario->confirmado = true;
                if ($usuario->save()) {
                    Yii::$app->session->setFlash('success', 'Se ha verificado su usuario CORRECTAMENTE, puedes iniciar sesión.');
                } else {
                    // var_dump($usuario->errors);
                    // die();
                    Yii::$app->session->setFlash('error', 'ERROR: No se ha verificado su usuario correctamente1.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'ERROR: No se ha verificado su usuario correctamente.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'ERROR: No se ha verificado.');
        }
        return $this->redirect(['site/index']);
    }

    public function actionRecuperarcontra()
    {
        if ($emailNombre = Yii::$app->request->post('emailNombre')) {
            $usuarioNombre = Usuarios::findByUsername($emailNombre);
            $usuarioEmail = Usuarios::findByEmail($emailNombre);

            if (isset($usuarioNombre) || isset($usuarioEmail)) {
                if (isset($usuarioNombre)) {
                    $this->email(
                        $usuarioNombre,
                        'recuperarcontra',
                        'Recuperación de contraseña',
                        'Se ha enviado un correo para restablecer su contraseña ,porfavor, consulte su correo.',
                        'Ha ocurrido un error al mandar el correo.'
                        );
                } else {
                    $this->email(
                        $usuarioEmail,
                        'recuperarcontra',
                        'Recuperación de contraseña',
                        'Se ha enviado un correo para restablecer su contraseña ,porfavor, consulte su correo.',
                        'Ha ocurrido un error al mandar el correo.'
                        );
                }
            } else {
                Yii::$app->session->setFlash('error', 'El usuario o email no son correctos.');
            }
        }
        return $this->render('recuperarcontra');
    }

    public function actionModificarcontra()
    {
        $post = Yii::$app->request->post();
        $keys = preg_grep('/.*Usuarios.*/i', array_keys($post));

        extract(Yii::$app->request->post($keys[1]));

        $model = $this->findModel($id);
        $model->scenario = Usuarios::SCENARIO_UPDATE;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado su contraseña correctamente.');
            return $this->redirect(['site/index']);
        }


        $model->password = $model->password_repeat = '';
        return $this->render('modificarcontra', [
            'model' => $model,
        ]);
    }

    public function actionRecuperarnick()
    {
        if ($email = Yii::$app->request->post('email')) {
            $usuario = Usuarios::findByEmail($email);

            if (isset($usuario)) {
                $this->email(
                    $usuario,
                    'recuperarnick',
                    'Recuperación del Nick',
                    'Se ha enviado un correo para recordarle su nick ,porfavor, consulte su correo.',
                    'Ha ocurrido un error al mandar el correo.'
                    );
            } else {
                Yii::$app->session->setFlash('error', 'El email no es correcto.');
            }
        }
        return $this->render('recuperarnick');
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionBanear($id)
    {
    }
}
