<?php

namespace modules\sales\frontend\widgets;

use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleHistory;
use Yii;
use yii\base\Widget;

/**
 * Class SaleHistoryWidget
 */
class SaleHistoryWidget extends Widget
{
    /** @var Sale */
    public $sale;

    /** @var string */
    public $redirectSuccess;

    public function run()
    {
        $model = new SaleHistory();
        $model->sale_id = $this->sale->id;
        $model->status_old = $this->sale->status;
        $model->status_new = $this->sale->status;
        $model->admin_id = Yii::$app->user->identity->getId();
        $model->note = 'Комментарий от администратора';

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                if (!empty($model->comment)) {
                    $model->save(false);
                    Yii::$app->response->redirect($this->redirectSuccess);
                    Yii::$app->end();
                }
            }
        }

        return $this->render('sale-history', ['sale' => $this->sale, 'model' => $model]);
    }
}
