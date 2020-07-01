<?php


namespace modules\burnpoints\backend\controllers;


use modules\burnpoints\common\models\BurnPointSettings;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\Yz;

class SettingsController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    /**
     * @return string|Response
     */
    public function actionUpdate()
    {
        $model = BurnPointSettings::get();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, Yii::t('admin/t', 'Record was successfully updated'));
        }

        return $this->render('update', compact('model'));
    }
}