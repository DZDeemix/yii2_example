<?php

namespace modules\sales\console\controllers;

use common\utils\Notifier;
use console\base\Controller;
use modules\sales\common\models\Sale;
use modules\sales\common\sales\statuses\Statuses;
use modules\sales\common\models\SaleHistory;
use yii\db\Expression;

/**
 * Class SalesController
 */
class SalesController extends Controller
{
    /**
     * Если покупку в течение 2-х недель не одобрили, одобряем автоматически
     */
    public function actionSaleApprove()
    {
        $now = (new \DateTime('-14 days'))->format('Y-m-d H:i:s');

        $sales = Sale::find()->where(['status' => Statuses::DRAFT])->andWhere("(created_at IS NULL OR created_at <= '$now')")->all();
        if(!empty($sales)) {
            foreach ($sales as $sale) {
                /** @var Sale $sale */
                $oldStatus = $sale->status;
                $sale->status = Statuses::APPROVED;
                $sale->save(false);

                $h = new SaleHistory();
                $h->sale_id = $sale->id;
                $h->status_old = $oldStatus;
                $h->status_new = $sale->status;
                $h->note = 'Продажа одобрена';
                $h->comment = "Продажа одобрена автоматически по истечении 14-ти дней";
                $h->type = SaleHistory::TYPE_APPROVE;
                $h->save(false);
            }
        }
    }
}
