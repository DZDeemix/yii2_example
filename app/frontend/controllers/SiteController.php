<?php

namespace frontend\controllers;

use frontend\base\Controller;
use marketingsolutions\captcha\algorithms\Numbers;
use marketingsolutions\captcha\CaptchaAction;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Profile;
use ms\loyalty\api\common\models\ApiSettings;
use ms\loyalty\identity\phonesEmails\common\models\Identity;
use ms\loyalty\identity\phonesEmails\frontend\forms\LoginForm;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ViewAction;
use yii\base\DynamicModel;
use ms\loyalty\identity\phonesEmails\common\models\IdentityType;
use Yii;

/**
 * Class SiteController
 *
 * @package \frontend\controllers
 */
class SiteController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $this->layout = 'index';

        return parent::beforeAction($action);
    }

    public function getAccessRules()
    {
        return array_merge([
            [
                'allow' => false,
                'actions' => ['index'],
                'roles' => ['@'],
                'denyCallback' => function () {
                    $this->redirect(['/dashboard/index']);
                }
            ],
            [
                'allow' => true,
            ]
        ], parent::getAccessRules());
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className(),
                'layout' => 'index',
            ],
            'page' => [
                'class' => ViewAction::className(),
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
                'algorithm' => Numbers::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'index';

        return $this->render('index');
    }

    public function actionEmail()
    {
        ApiSettings::sendTestEmail();

        return $this->redirect('/');
    }

    public function actionAccessDenied()
    {
        return $this->render('accessDenied');
    }

    public function actionLogin($id, $hash)
    {
        /** @var Profile $profile */
        $profile = Profile::findOne($id);

        if (!$profile || md5($id) != $hash) {
            Yii::$app->session->setFlash(\yz\Yz::FLASH_INFO, 'Невозможно войти под участником, такой участник не найден');
            return $this->redirect('/');
        }

        @Yii::$app->user->logout();
        Yii::$app->user->login($profile, 0);

        return $this->redirect('/dashboard/index');
    }

    public function actionBanned($id)
    {
        /** @var Profile $profile */
        $bannedProfile = Profile::findOne($id);

        if ($bannedProfile == null) {
            throw new NotFoundHttpException();
        }

        return $this->render('banned', compact('bannedProfile'));
    }
}