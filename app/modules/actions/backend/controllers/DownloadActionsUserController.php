<?php

namespace modules\actions\backend\controllers;

use modules\actions\common\models\Action;
use modules\actions\common\models\ActionProfile;
use modules\profiles\common\models\Profile;
use yii\web\Controller;
use yii\helpers\Html;
use yz\admin\import\BatchImportAction;
use yz\admin\import\ImportForm;
use yz\admin\import\InterruptImportException;
use yz\admin\import\SkipRowException;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\contracts\AccessControlInterface;
use modules\profiles\common\models\Leader;
use marketingsolutions\phonenumbers\PhoneNumber;
use libphonenumber\PhoneNumberFormat;

class DownloadActionsUserController extends Controller implements AccessControlInterface
{

    use CheckAccessTrait;


    const FIELD_PHONE = 'Телефон участника';
    const FIELD_PLAN = 'План-факт прошлого года';

    public function actions()
    {
        return [
            'index' => [
                'class' => BatchImportAction::class,
                'extraView' => '@modules/actions/backend/views/action/actions.php',
                'importConfig' => [
                    'availableFields' => [
                        self::FIELD_PHONE => 'Телефон участника',
                        self::FIELD_PLAN => 'План-факт прошлого года',
                    ],
                    'rowImport' => [$this, 'rowImport'],
                ]
            ]
        ];
    }

    /**
     * @param ImportForm $form
     * @param array $row
     * @throws InterruptImportException
     */
    public function rowImport(ImportForm $form, array $row)
    {
        $this->importUser($row);
    }

    /**
     * @param array $row
     * @return null|static
     * @throws InterruptImportException
     * @throws SkipRowException
     */
    private function importUser(array $row)
    {
        if (PhoneNumber::validate($row[self::FIELD_PHONE], 'RU') == false) {
            throw new InterruptImportException('Неверный формат номера телефона: ' . $row[self::FIELD_PHONE], $row);
        }

        if(\Yii::$app->request->get('id')) {
            $action_id = \Yii::$app->request->get('id');
        }else{
            throw new InterruptImportException("Необходимо пройти по <a href='/actions/action/index'>ссылке и выбрать акцию для загрузки</a>!");
        }
        $plan = (int) $row[self::FIELD_PLAN];
        if(!$plan){
            throw new InterruptImportException('У участник с номером ' . $row[self::FIELD_PHONE] . ' не найден в системе!', $row);
        }

        $profile = Profile::findOne(['phone_mobile' => PhoneNumber::format($row[self::FIELD_PHONE], PhoneNumberFormat::E164, 'RU')]);

        if(!$profile){
            throw new InterruptImportException('Участник с номером ' . $row[self::FIELD_PHONE] . ' не найден в системе!', $row);
        }

        $action = Action::findOne($action_id);
        if(!$action){
            throw new InterruptImportException('Данной акции не существует', $row);
        }

        if($action->role != $profile->role){
            throw new InterruptImportException('Роль участника не совпадает с ролью разрешенной ролью в текущей акции', $row);
        }

        if($action->status == Action::STATUS_FINISHED){
            throw new InterruptImportException('Нельзя загружать участников в завершенную акцию!', $row);
        }

        $action_profile = ActionProfile::findOne(['profile_id' => $profile->id, 'action_id' => $action_id]);
        if($action_profile){
            throw new SkipRowException('Участник с номером ' . $row[self::FIELD_PHONE] . ' уже присутствует в данной акции', $row);
        }else {
            $now = date("Y-m-d H:i:s");
            $action_profile = new ActionProfile();
            $action_profile->profile_id = $profile->id;
            $action_profile->action_id = $action_id;
            $action_profile->created_at = $now;
            $action_profile->updated_at = $now;
            $action_profile->last_year_plan = $plan;
            $action_profile->save(false);
        }

        return $action_profile;
    }
}