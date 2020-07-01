<?php


namespace modules\burnpoints\common\commands;


use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\Transaction;
use modules\burnpoints\common\models\BurnPoint;
use modules\burnpoints\common\models\BurnPointSettings;
use modules\profiles\common\models\Profile;
use modules\sales\common\finances\SalePartner;
use modules\sales\common\finances\SaleRollbackPartner;
use yii\base\Component;
use yii\db\Query as QueryAlias;

/**
 * Class MainBurnCommand
 *
 * @property string $checkDate
 * @property BurnPointSettings $settings
 *
 * @package modules\burnpoints\common\commands
 */
abstract class MainBurnCommand extends Component
{
    const EMAIL_TYPE_WARNING = 'warning';
    const EMAIL_TYPE_BURN = 'burn';
    
    
    /**
     * @var string
     */
    public $checkDate;

    /**
     * @var BurnPointSettings
     */
    protected $settings;
    

    /**
     * BurnCommand constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->settings = BurnPointSettings::get();

        parent::__construct($config);
    }

    /**
     * @return QueryAlias
     */
    private function getQuery(): QueryAlias
    {
        return Transaction::find()
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
            ->orderBy('transaction.created_at ASC');
    }
    
    /**
     * @return array
     */
    public function getTransactionsForBurn()
    {
        $transactions = $this->getQuery()
            ->andWhere(['<=', 'transaction.created_at', $this->checkDate])
            ->all();
        
        return $transactions;
    }
    
    /**
     * @return array
     */
    public function getTransactionsForWarning()
    {
        $transactions = $this->getQuery()
            ->andWhere(['between', 'transaction.created_at', $this->checkDate, $this->getEndDate($this->checkDate)])
            ->all();
        
        return $transactions;
    }
    
    private function getEndDate($dateTime){
        $date = new \DateTime($dateTime);
        return $date->modify("+{$this->settings->days_warning} days")->format('Y-m-d H:i:s');
    }
}
