<?php


namespace modules\burnpoints\common\commands;


use common\components\interfaces\CommandInterface;
use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\Transaction;
use modules\burnpoints\common\models\BurnPoint;
use modules\profiles\common\models\Profile;
use modules\sales\common\finances\SalePartner;
use modules\sales\common\finances\SaleRollbackPartner;
use yii\base\Component;

class CalculateCommand extends MainBurnCommand implements CommandInterface
{
    public function handle()
    {
        $transactionsIn = Transaction::find()
            ->from(['transaction' => Transaction::tableName()])
            ->innerJoin(['purse' => Purse::tableName()], 'purse.id = transaction.purse_id')
            ->leftJoin(['burn' => BurnPoint::tableName()], 'burn.transaction_id = transaction.id')
            ->where([
                'purse.owner_type' => Profile::class,
                'transaction.type' => Transaction::INCOMING,
                'burn.amount' => null,
            ])
            ->andWhere(['transaction.partner_type' => SalePartner::class])
            ->andWhere(['>', 'transaction.points_to_burn', 0])
            ->orderBy('transaction.created_at ASC')
            ->all();
        
        foreach ($transactionsIn as $transactionIn) {
            //Проверяем была ли отменена транзакция
            $rollbackTransaction = Transaction::find()->where([
                'partner_type' => SaleRollbackPartner::class,
                'purse_id' => $transactionIn->purse_id,
                'partner_id' => $transactionIn->partner_id,
            ])->one();
            if (!empty($rollbackTransaction)) {
                $transactionIn->points_to_burn = 0;
                $rollbackTransaction->points_to_burn = 0;
                $transactionIn->save();
                $rollbackTransaction->save();
                continue;
            }
        
            //Находим траты к участника за период сгорания балов
            $transactionsOut = Transaction::find()
                ->from(['transaction' => Transaction::tableName()])
                ->where([
                    'transaction.purse_id' => $transactionIn->purse_id,
                    'transaction.type' => Transaction::OUTBOUND,
                ])
                ->andWhere(['>', 'transaction.residue', 0])
                ->andWhere(['between', 'transaction.created_at',
                 $transactionIn->created_at, $this->getEndDate($transactionIn->created_at)])
                /*->andWhere('transaction.created_at between STR_TO_DATE("'.  $transactionIn->created_at .
                    '", "%d-%m-%Y" ) AND   STR_TO_DATE("' .  $this->getEndDate($transactionIn->created_at) .
                    '", "%d-%m-%Y" )' )*/
                ->orderBy('transaction.created_at ASC')
                ->all();
        
            $points_to_burn = $transactionIn->points_to_burn;
        
            foreach ($transactionsOut as $transactionOut) {
                if (($points_to_burn - $transactionOut->residue) >= 0) {
                    //списываем трату с транзакции и помечаем трату
                    $points_to_burn = $points_to_burn - $transactionOut->residue;
                    $transactionOut->residue = 0;
                    $transactionOut->save();
                } else {
                    $points_to_burn = 0;
                    $transactionOut->residue = $transactionOut->residue - $points_to_burn;
                    $transactionOut->save();
                }
            }
            //Обновляем балы для сгорания
            $transactionIn->points_to_burn = $points_to_burn;
            $transactionIn->save();
            
        }
    }
    
    private function getEndDate($dateTime){
        $date = new \DateTime($dateTime);
        return $date->modify("+{$this->settings->days_to_burn} days")->format('Y-m-d H:i:s');
    }
    
    
}
