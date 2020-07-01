<?php

namespace modules\profiles\backend\reports;

use ms\loyalty\reports\contracts\base\ReportInterface;
use ms\loyalty\reports\contracts\ByQueryInterface;
use ms\loyalty\reports\contracts\ReportSearchModelInterface;
use ms\loyalty\reports\contracts\types\TableReportInterface;
use ms\loyalty\reports\support\SelfSearchModel;
use modules\profiles\common\models\Profile;
use yii\base\Model;
use yii\db\Query;

/**
 * Class BossManagersReport
 */
class NDFLReport extends Model implements ReportInterface, TableReportInterface, ByQueryInterface, ReportSearchModelInterface
{
    use SelfSearchModel;

    public $dealer__name, $boss__name, $manager__name;
    protected $monthNames;
    
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['dealer__name', 'boss__name', 'manager__name'], 'safe'],
        ];
    }

    /**
     * @return Query
     */
    public function query()
    {

        $year = "2016";
        $month = "1";
        $monthes = [];

        while ($month < 13) {
            $startDate = date_create($year . "-" . $month . "-" . "01");
            $endDate = date_create($year . "-" . $month . "-" . "01 23:59:59")->modify("+1 month -1 day");

            $monthName = date('F', strtotime($year . "-" . $month . "-" . "01"));
            $this->monthNames[] = $monthName;
            $monthes = array_merge($monthes, [
                $monthName => "sum(case when ft.created_at between STR_TO_DATE('" . $startDate->format('Y-m-d H:i:s') . "', '%Y-%m-%d %H:%i:%s')
                    AND STR_TO_DATE('" . $endDate->format('Y-m-d H:i:s') . "', '%Y-%m-%d %H:%i:%s') THEN ft.amount end)"
                ]);
            $month += 1;
        }

        $query = (new Query())
            ->select(array_merge([
                'tap__inn' => 'tap.inn',
                'tap__last_name' => 'tap.last_name',
                'tap__first_name' => 'tap.first_name',
                'tap__middle_name' => 'tap.middle_name',
                'tap__birthday_on' => 'tap.birthday_on',
                'tc__number_code' => 'tc.number_code',
                'tdt__number_code' => 'tdt.number_code',
                'tap__document_series_and_number' => 'tap.document_series_and_number',
                'tap__postal_code' => 'tap.postal_code',
                'tap__region_code' => 'tap.region_code',
                'tap__region_with_type' => 'tap.region_with_type',
                'tap__city_with_type' => 'tap.city_with_type',
                'tap__settlement_with_type' => 'tap.settlement_with_type',
                'tap__street_with_type' => 'tap.street_with_type',
                'tap__house' => 'tap.house',
                'tap__block' => 'tap.block',
                'tap__flat' => 'tap.flat',
            ], $monthes))
            ->from([
                'ft' => \ms\loyalty\finances\common\models\Transaction::tableName(),
            ])
            ->leftJoin(['fp' => \marketingsolutions\finance\models\Purse::tableName()], 'fp.id = ft.purse_id')
            ->leftJoin(['ta' => \ms\loyalty\taxes\common\models\Account::tableName()], 'fp.owner_id = ta.profile_id')
            ->leftJoin(['tap' => \ms\loyalty\taxes\common\models\AccountProfile::tableName()], 'tap.account_id = ta.id')
            ->leftJoin(['tdt' => \ms\loyalty\taxes\common\models\DocumentType::tableName()], 'tdt.id = tap.document_type_id')
            ->leftJoin(['tc' => \ms\loyalty\taxes\common\models\Country::tableName()], 'tc.id = tap.citizenship_id')
            ->where([
                'ft.type' => 'out',
                'fp.owner_type' => Profile::class,
            ])
            ->andWhere([
                'between',
                'ft.created_at',
                date_create($year . "-" . "01" . "-" . "01 00:00:00")->format('Y-m-d H:i:s'),
                date_create($year . "-" . "12" . "-" . "31 23:59:59")->format('Y-m-d H:i:s')
                ])
            ->groupBy('ft.purse_id');

        //echo $query->createCommand()->sql;
        
        return $query;
    }

    public function prepareFilters(Query $query)
    {
//        $query->andFilterWhere([
//            'dd.id' => $this->dealer__name,
//            'profiles.role' => $this->profiles__role,
//            'profiles.id' => $this->profiles__full_name,
//        ]);

        $query->andFilterWhere(['like', 'pr.full_name', $this->boss__name]);
        $query->andFilterWhere(['like', 'man.full_name', $this->manager__name]);
        $query->andFilterWhere(['like', 'dd.name', $this->dealer__name]);
    }


    /**
     * Returns title of the report
     * @return string
     */
    public function title()
    {
        return 'НДФЛ по месяцам';
    }

    /**
     * @return array
     */
    public function gridColumns()
    {
        $columns = [
            [
                'attribute' => 'tap__inn',
                'label' => 'ИНН в РФ',
            ],
            [
                'attribute' => 'tap__last_name',
                'label' => 'Фамилия',
            ],
            [
                'attribute' => 'tap__first_name',
                'label' => 'Имя',
            ],
            [
                'attribute' => 'tap__middle_name',
                'label' => 'Отчество',
            ],
            [
                'attribute' => 'tap__birthday_on',
                'label' => 'Дата рождения',
                'format' => ['date', 'php:Y-m-d'],
            ],
            [
                'attribute' => 'tc__number_code',
                'label' => 'Гражданство (Код страны)',
            ],
            [
                'attribute' => 'tdt__number_code',
                'label' => 'Код документа, удостоверяющего личность',
            ],
            [
                'attribute' => 'tap__document_series_and_number',
                'label' => 'Серия и номер документа',
            ],
            [
                'attribute' => 'tap__postal_code',
                'label' => 'Почтовый индекс',
            ],
            [
                'attribute' => 'tap__region_code',
                'label' => 'Код субъекта',
            ],
            [
                'label' => 'Город',
                'value' => function ($data) {
                    $ar_str = explode(" ", $data['tap__city_with_type'], 2);
                    $str = $data['tap__city_with_type'];
                    if (strlen($ar_str[0]) > 0 && strlen($ar_str[0]) < 6 && isset($ar_str[1])) {
                        $str = $ar_str[1] . " " . $ar_str[0];
                    }
                    return $str;
                }
            ],
            [
                'label' => 'Населенный пункт',
                'value' => function ($data) {
                    $ar_str = explode(" ", $data['tap__settlement_with_type'], 2);
                    $str = $data['tap__settlement_with_type'];

                    if (isset($ar_str[0]) && isset($ar_str[1]) && !preg_match('/^[А-Я]/u', $ar_str[0])) {
                        $str = $ar_str[1] . " " . $ar_str[0];
                    }
                    return $str;
                }
            ],
            [
                'label' => 'Улица',
                'value' => function ($data) {
                    $ar_str = explode(" ", $data['tap__street_with_type'], 2);
                    $str = $data['tap__street_with_type'];
                    if (strlen($ar_str[0]) > 0 && strlen($ar_str[0]) < 10 && isset($ar_str[1])) {
                        $str = $ar_str[1] . " " . $ar_str[0];
                    }
                    return $str;
                }
            ],
            [
                'attribute' => 'tap__house',
                'label' => 'Дом',
            ],
            [
                'attribute' => 'tap__block',
                'label' => 'Корпус',
            ],
            [
                'attribute' => 'tap__flat',
                'label' => 'Квартира',
            ],
        ];

        $arrCount = count($this->monthNames);
        $monthNames = $this->monthNames;

        for ($i = 0; $i < $arrCount; $i++) {
            $columns[] = [
                'attribute' => $this->monthNames[$i],
                'label' => $this->monthNames[$i],
//                'format' => 'decimal',
                'value' => function ($data) use ($monthNames, $i) {
                    return number_format($data[$monthNames[$i]], 2, ",", " ");
                }
            ];
        }

        return $columns;
    }
}