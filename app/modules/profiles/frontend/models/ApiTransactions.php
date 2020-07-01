<?php

namespace modules\profiles\frontend\models;

use marketingsolutions\finance\models\Transaction;

class ApiTransactions extends Transaction
{
    public function fields()
    {
        $fields = [
            'id',
            'amount',
            'balance_after',
            'balance_before',
            'title' => function(ApiTransactions $model) {
                /* @var $model ApiTransactions */
                return str_replace(' ЭПС', '', $model->title);
            },
            'comment',
            'type',
            'created_at' => function(ApiTransactions $model) {
                /* @var $model ApiTransactions */
                return $model->created_at ? (new \DateTime($model->created_at))->format('d.m.Y H:i:s') : null;
            }
        ];

        return $fields;
    }
}
