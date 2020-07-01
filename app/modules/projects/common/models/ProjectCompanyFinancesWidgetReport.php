<?php

namespace modules\projects\common\models;

use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\Transaction;
use modules\projects\common\exceptions\MissingProjectException;
use ms\loyalty\finances\backend\reports\CompanyFinancesWidgetReport;
use ms\loyalty\reports\support\WidgetConfig;
use Yii;
use yii\db\ActiveQuery;
use yz\icons\Icons;

/**
 * @inheritDoc
 */
class ProjectCompanyFinancesWidgetReport extends CompanyFinancesWidgetReport
{
    /**
     * @return WidgetConfig[]
     * @throws MissingProjectException
     */
    public function widgets()
    {
        if (!Project::$current) {
            throw new MissingProjectException();
        }

        $projectId = null;

        if (Project::$current->is_main) {
            $requestParams = Yii::$app->request->get('ProjectTransactionSearch');
            if ($requestParams && !empty($requestParams['purse_id'])) {
                $purseId = (int) $requestParams['purse_id'];
                $purse = Purse::findOne($purseId);
                $projectId = $purse->owner_id;
            }
        }
        else {
            $projectId = Project::$current->id;
        }

        if (!$projectId) {
            return [];
        }

        $project = Project::findOne($projectId);
        $companyPurse = $project->purse;

        $widgets = [
            (new WidgetConfig())
                ->title('Текущий баланс счета')
                ->value(
                    $companyPurse->balance / 100
                )
                ->style('bg-aqua')
                ->icon(Icons::i('rub')),

            (new WidgetConfig())
                ->title('Сумма входящих транзакций')
                ->value(
                    $this->calculateTransactionsSum(Transaction::INCOMING, $companyPurse) / 100
                )
                ->style('bg-green')
                ->icon(Icons::i('rub')),

            (new WidgetConfig())
                ->title('Сумма исходящих транзакций')
                ->value(
                    $this->calculateTransactionsSum(Transaction::OUTBOUND, $companyPurse) / 100
                )
                ->style('bg-red')
                ->icon(Icons::i('rub'))
        ];

        if (is_callable($this->widgets)) {
            $extraWidgets = call_user_func($this->widgets, $this);
        }
        else {
            $extraWidgets = $this->widgets;
        }

        return array_merge($widgets, $extraWidgets);
    }

    public function calculateTransactionsSum($type, Purse $purse)
    {
        /** @var ActiveQuery $query */
        $query = $purse->getPurseTransactions();
        $sum = $query->where(['type' => $type])->sum('amount');

        return $sum ?: 0;
    }
}
