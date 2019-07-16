<?php

namespace app\controllers;

use app\models\FileForm;
use app\models\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFiles()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        return $this->render('files', [
            'model' => new FileForm()
        ]);
    }

    /**
     * @return array|string
     * @throws \yii\base\Exception
     */
    public function actionUpload()
    {
        $model = new FileForm();
        $model->setScenario(FileForm::SCENARIO_ADD);

        if ($model->upload(UploadedFile::getInstance($model, 'image'))) {
            return Json::encode([
                'files' => [
                    [
                        'name' => $model->getFilename(),
                        'size' => $model->getSize(),
                        'url' => $model->getFilepath(),
                        'thumbnailUrl' => $model->getThumbFilepath(),
                        'deleteUrl' => 'delete?id=' . $model->getId(),
                        'deleteType' => 'POST',
                    ],
                ],
            ]);
        }

        return [
            'success' => false,
            'errors' => $model->errors
        ];
    }

    public function actionLoad($id, $like = '')
    {
        return Json::encode([
            'files' => (new FileForm)->loadFiles($id, $like)
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = new FileForm();
        $model->setScenario(FileForm::SCENARIO_DEL);
        $model->attributes = ['fileId' => $id];
        $model->delete();

        return $this->actionLoad(\Yii::$app->user->id);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['files']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_REGISTER;

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    Yii::$app->session->setFlash('success', 'Регистрация прошла успешно!');
                    return $this->redirect(['files']);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
}
