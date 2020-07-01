<?php

namespace modules\sales\frontend\widgets;

use modules\sales\common\models\Sale;
use modules\profiles\common\models\Profile;
use modules\sales\SalesModuleTrait;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

/**
 * Class SalesDashboard
 */
class SalesHistory extends Widget
{
    /** @var Profile */
    public $profile;

    use SalesModuleTrait;

    public function run()
    {
        return $this->render('sales-history', [
            'dataProvider' => $this->getModelDataprovider(),
            'profile' => $this->profile,
        ]);
    }

    public function getModelDataprovider()
    {
        $query = Sale::find()
            ->joinWith('positions')
            ->where(['recipient_id' => $this->profile->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        return $dataProvider;
    }
}