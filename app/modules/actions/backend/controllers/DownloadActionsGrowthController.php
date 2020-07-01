<?php

namespace modules\actions\backend\controllers;

use modules\actions\common\models\Action;
use modules\actions\common\models\ActionGrowthBonus;
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

class DownloadActionsGrowthController extends Controller implements AccessControlInterface
{

    use CheckAccessTrait;

    const FIELD_GROWTH_FROM = 'Прирост от';
    const FIELD_GROWTH_TO = 'Прирост до';
    const FIELD_BONUS = 'Бонусы';

    public function actions()
    {
        return [
            'index' => [
                'class' => BatchImportAction::class,
                'extraView' => '@modules/actions/backend/views/action/actions_growth.php',
                'importConfig' => [
                    'availableFields' => [
                        self::FIELD_GROWTH_FROM => 'Прирост от',
                        self::FIELD_GROWTH_TO => 'Прирост до',
                        self::FIELD_BONUS => 'Бонусы',
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
        $this->importGrowth($row);
    }

    /**
     * @param array $row
     * @return null|static
     * @throws InterruptImportException
     * @throws SkipRowException
     */
    private function importGrowth(array $row)
    {
        if(!\Yii::$app->request->get('action_id')){
            throw new InterruptImportException("Акция не выбрана! Необходимо пройти по <a href='/actions/action/index'>ссылке и выбрать акцию для загрузки</a>!");
        }
        $actio_id = \Yii::$app->request->get('action_id');

        $isGrow = ActionGrowthBonus::findOne([
            'action_id' => $actio_id,
            'growth_from' => $row[self::FIELD_GROWTH_FROM],
            'growth_to' => $row[self::FIELD_GROWTH_TO]
        ]);

        if($isGrow){
            throw new InterruptImportException('Настройка с такими параметрами уже существует в загружаемом файле');
        }

        $newGrow = new ActionGrowthBonus();
        $newGrow->action_id = $actio_id;
        $newGrow->growth_from = trim($row[self::FIELD_GROWTH_FROM]);
        $newGrow->growth_to = trim($row[self::FIELD_GROWTH_TO]);
        $newGrow->bonus = trim($row[self::FIELD_BONUS]);
        if($newGrow->save() === false){
            throw new InterruptImportException('Ошибка при импорте настроек акции: ' . implode(', ', $newGrow->getFirstErrors()), $row);
        }

        return $newGrow;
    }
}