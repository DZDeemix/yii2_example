<?php

namespace modules\actions\backend\controllers;

use modules\actions\common\models\Action;
use modules\actions\common\models\ActionProfile;
use modules\actions\common\models\ActionProfileByDealer;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\ProfileDealer;
use yii\web\Controller;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\import\BatchImportAction;
use yz\admin\import\ImportForm;
use yz\admin\import\InterruptImportException;
use yz\admin\import\SkipRowException;
use yz\admin\traits\CheckAccessTrait;

class DownloadActionsDealerController extends Controller implements AccessControlInterface
{

    use CheckAccessTrait;


    const FIELD_DEALER_ID = 'ID Аптеки';
    const FIELD_PLAN = 'План в штуках';
    const FIELD_PRICE_PLAN = 'План в рублях';

    public function actions()
    {
        return [
            'index' => [
                'class' => BatchImportAction::class,
                'extraView' => '@modules/actions/backend/views/action/actions.php',
                'importConfig' => [
                    'availableFields' => [
                        self::FIELD_DEALER_ID => 'ID Аптеки',
                        self::FIELD_PLAN => 'План в штуках',
                        self::FIELD_PRICE_PLAN => 'План в рублях',
                    ],
                    'rowImport' => [$this, 'rowImport'],
                ]
            ]
        ];
    }


    public function init()
    {
        set_time_limit(600);
        ini_set('memory_limit', '-1');
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


        if (\Yii::$app->request->get('id')) {
            $action_id = \Yii::$app->request->get('id');
        } else {
            throw new InterruptImportException("Необходимо пройти по <a href='/actions/action/index'>ссылке и выбрать акцию для загрузки</a>!");
        }

        $plan = (int)$row[self::FIELD_PLAN];
        $price_plan = (int)$row[self::FIELD_PRICE_PLAN];
        $external_id = (int)$row[self::FIELD_DEALER_ID];

        $dealer = Dealer::findOne([
            'external_id' => $external_id
        ]);

        if (!$dealer) {

            throw new SkipRowException('Точка с номером ' . $external_id . ' не найдена в системе!',
                $row);
        }

        $action = Action::findOne($action_id);
        if (!$action) {
            throw new InterruptImportException('Данной акции не существует', $row);
        }


        if ($action->status == Action::STATUS_FINISHED) {
            throw new InterruptImportException('Нельзя загружать участников в завершенную акцию!', $row);
        }



        $action_profile = ActionProfileByDealer::findOne(['dealer_id' => $dealer->id, 'action_id' => $action_id]);
            $now = date("Y-m-d H:i:s");
        if ($action_profile) {
            throw new SkipRowException('Аптека с номером ' . $row[self::FIELD_DEALER_ID] . ' уже присутствует в данной акции',
                $row);

        } else {
            $action_profile = new ActionProfileByDealer();
            $action_profile->dealer_id = $dealer->id;
            $action_profile->action_id = $action_id;
            $action_profile->created_at = $now;
            $action_profile->updated_at = $now;
            $action_profile->last_year_plan = $plan;
            $action_profile->last_year_price_plan = $price_plan;
            $action_profile->save(false);
        }

        //return $action_profile;
        //}
    }
}