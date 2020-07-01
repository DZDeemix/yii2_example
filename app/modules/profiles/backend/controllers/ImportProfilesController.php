<?php

namespace modules\profiles\backend\controllers;

use libphonenumber\PhoneNumberFormat;
use modules\profiles\common\models\Companies;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Profile;
use modules\bonuses\backend\forms\BonusesImportForm;
use marketingsolutions\phonenumbers\PhoneNumber;
use ms\loyalty\location\common\models\City;
use ms\loyalty\location\common\models\Region;
use yii\web\Controller;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\import\BatchImportAction;
use yz\admin\import\ImportForm;
use yz\admin\import\InterruptImportException;
use yz\admin\traits\CheckAccessTrait;

class ImportProfilesController extends Controller implements AccessControlInterface
{
    use CheckAccessTrait;

    const FIO = 'фио';
    const PHONE = 'телефон';
    const COMPANY = 'компания';

    public function actions()
    {
        return [
            'index' => [
                'class' => BatchImportAction::class,
                'extraView' => '@modules/profiles/backend/views/import/profiles.php',
                'importConfig' => [
                    'availableFields' => [
                        self::FIO => 'ФИО',
                        self::PHONE => 'Телефон',
                        self::COMPANY => 'Название компании',
                    ],
                    'rowImport' => [$this, 'rowImport'],
                    'skipFirstLine' => true,
                ]
            ]
        ];
    }

    public function rowImport(ImportForm $form, array $row)
    {
        if (empty($row[self::PHONE])) {
            return;
        }

        $row = array_map(function ($value) {
            return preg_replace('/[\s]{2,}|[\r\n]/', ' ', trim($value));
        }, $row);

        $this->importProfile($row);
    }

    /**
     * @param $row
     * @return Profile
     * @throws InterruptImportException
     */
    private function importProfile($row)
    {
        $phone = trim($row[self::PHONE]);
        if (PhoneNumber::validate($phone, 'RU') == false) {
            throw new InterruptImportException('Неверный номер телефона: ' . $phone, $row);
        }
        $phoneNumber = PhoneNumber::format($phone, PhoneNumberFormat::E164, 'RU');

        /** @var Profile $profile */
        $profile = Profile::findOne(['phone_mobile' => $phoneNumber]);

        $companyName = trim($row[self::COMPANY]);
        $company = Companies::findOne(['name' => $companyName]);

        if(!$company){
            throw new InterruptImportException('Компания '.$companyName.' не найдена в списке компаний: ' . $phone, $row);
        }


        if ($profile === null) {
            $profile = new Profile();
            $profile->is_uploaded = true;
            $profile->phone_mobile_local = $phone;
            $profile->company_id = $company->id;

            $fio = trim($row[self::FIO]);
            $fioParts = explode(' ', $fio);

            if (!empty($fioParts[2])) {
                $profile->last_name = $fioParts[0];
                $profile->first_name = $fioParts[1];
                $profile->middle_name = $fioParts[2];
            }
            elseif (!empty($fioParts[1])) {
                $profile->last_name = $fioParts[0];
                $profile->first_name = $fioParts[1];
            }
            elseif (!empty($fioParts[0])) {
                $profile->first_name = $fioParts[0];
            }

            if ($profile->save() == false) {
                throw new InterruptImportException('Ошибка при импорте участника: ' . implode(', ', $profile->getFirstErrors()), $row);
            }
            $profile->refresh();
        }

        return $profile;
    }
}