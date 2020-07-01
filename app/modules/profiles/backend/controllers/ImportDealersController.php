<?php

namespace modules\profiles\backend\controllers;

use modules\profiles\common\models\Companies;
use modules\profiles\common\models\Dealer;
use modules\bonuses\backend\forms\BonusesImportForm;
use ms\loyalty\location\common\models\City;
use ms\loyalty\location\common\models\Region;
use yii\web\Controller;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\import\BatchImportAction;
use yz\admin\import\ImportForm;
use yz\admin\import\InterruptImportException;
use yz\admin\import\SkipRowException;
use yz\admin\traits\CheckAccessTrait;

class ImportDealersController extends Controller implements AccessControlInterface
{
    use CheckAccessTrait;
    
    const DEALER_NAME = 'название';
    const DEALER_TYPE = 'тип';
    const DEALER_REGION = 'регион';
    const DEALER_CITY = 'город';

    public function actions()
    {
        return [
            'index' => [
                'class' => BatchImportAction::class,
                'extraView' => '@modules/profiles/backend/views/import/dealers.php',
                'importConfig' => [
                    'availableFields' => [
                        self::DEALER_NAME => 'Название РТТ / Диллера',
                        self::DEALER_TYPE => 'Тип компании (Дилер/РТТ)',
                    ],
                    'rowImport' => [$this, 'rowImport'],
                    'skipFirstLine' => true,
                ]
            ]
        ];
    }

    public function rowImport(ImportForm $form, array $row)
    {
        if (empty($row[self::DEALER_NAME])) {
            return;
        }

        $row = array_map(function ($value) {
            return preg_replace('/[\s]{2,}|[\r\n]/', ' ', trim($value));
        }, $row);


        $dealer = $this->importDealer($row);
    }

    /**
     * @param $row
     * @return City|null
     */
    private function importCity($row)
    {
        if (empty($row[self::DEALER_REGION]) || empty($row[self::DEALER_CITY])) {
            return null;
        }
        $city = null;
        $regionName = $row[self::DEALER_REGION];
        $cityName = $row[self::DEALER_CITY];

        /** @var Region $region */
        $region = Region::find()->where(['or',
            ['title' => $regionName],
            ['name' => $regionName],
        ])->one();

        if ($region === null) {
            $region = new Region();
            $region->title = $regionName;
            $region->save(false);
            $region->refresh();
        }

        /** @var City $city */
        $city = City::find()
            ->where(['or',
                ['title' => $cityName],
                ['name' => $cityName],
            ])
            ->andWhere(['region_id' => $region->id])
            ->one();

        if ($city === null) {
            $city = new City();
            $city->region_id = $region->id;
            $city->title = $cityName;
            $city->save(false);
            $city->refresh();
        }

        return $city;
    }

    /**
     * @param $row
     * @param City|null $city
     * @return Dealer|null
     * @throws InterruptImportException
     * @throws SkipRowException
     */
    private function importDealer($row, City $city = null)
    {
        if (empty($row[self::DEALER_NAME])) {
            throw new SkipRowException('Дистрибьютор пропущен, пустое поле с именем');
        }
        /*$isDealer = Dealer::findOne(['name' => $row[self::DEALER_NAME]]);
        if($isDealer){
            throw new SkipRowException('Дистрибьютор '.$row[self::DEALER_NAME].' пропущен, т. к. уже существует в системе');
        }*/
        
        $dealerType = mb_strtolower($row[self::DEALER_TYPE], 'utf-8');

        $dealer = new Dealer();
        $dealer->name = $row[self::DEALER_NAME];
        $dealer->type = in_array($dealerType, ['дилер', 'dealer', 'дистрибьютор'])
            ? Dealer::TYPE_DEALER
            : Dealer::TYPE_RTT;

        if ($city) {
            $dealer->city_id = $city->id;
        }

        if ($dealer->save() == false) {
            throw new InterruptImportException('Ошибка при импорте компании: ' . implode(', ', $dealer->getFirstErrors()), $row);
        }
        $dealer->refresh();

        return $dealer;
    }
}
