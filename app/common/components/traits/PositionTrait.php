<?php

namespace common\components\traits;

use yii\db\ActiveRecord;

trait PositionTrait
{
    public function actionPositionMovePrev($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);
        $model->movePrev();
        $model->updateAttributes(['positions']);

        return $this->redirect(['index']);
    }

    public function actionPositionMoveNext($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);
        $model->moveNext();
        $model->updateAttributes(['positions']);

        return $this->redirect(['index']);
    }
}