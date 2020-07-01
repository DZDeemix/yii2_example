<?php

namespace modules\sales\common\models;

use modules\actions\common\models\Action;
use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use modules\sales\common\sales\statuses\Statuses;
use modules\sales\common\sales\statuses\StatusManager;
use marketingsolutions\datetime\DateTimeBehavior;
use modules\sales\frontend\models\ApiSale;
use ms\files\attachments\common\traits\FileAttachmentsTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_sales".
 *
 * @property integer $id
 * @property string $status
 * @property integer $recipient_id
 * @property integer $bonuses
 * @property integer $total_cost
 * @property string $created_at
 * @property string $updated_at
 * @property string $sold_on
 * @property string $bonuses_paid_at
 * @property string $approved_by_admin_at
 * @property string $review_comment
 * @property string $number
 * @property string $sale_products
 * @property string $sale_groups
 * @property string $sale_qty
 * @property string $place
 * @property integer $action_id
 * @property integer $project_id
 * @property integer $role
 * @property string $address
 *
 * @property SalePosition[] $positions
 * @property StatusManager $statusManager
 * @property Project $progect
 * @property SaleDocument[] $documents
 * @property SaleHistory[] $history
 * @property string $status_label
 *
 * @property string $sold_on_local
 * @property Profile $profile
 */
class Sale extends \yz\db\ActiveRecord implements ModelInfoInterface
{
    use FileAttachmentsTrait;

    const DATA_DIR = 'sales';

    /**
     * @var StatusManager
     */
    private $_statusManager;
    private $_documents;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     *
     * @return string
     */
    public static function modelTitle()
    {
        return 'Продажа';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     *
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Список зарегистрированных Участниками продаж';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
            'datetime' => [
                'class' => DateTimeBehavior::class,
                'attributes' => [
                    \yz\db\ActiveRecord::EVENT_BEFORE_INSERT => ['sold_on'],
//                    'sold_on' => /*[
//                        'value' => */new Expression('NOW()'),
////                        'targetAttribute' => 'sold_on_local',
////                        'originalFormat' => ['date', 'yyyy-MM-dd'],
////                        'targetFormat' => ['date', 'dd.MM.yyyy'],
//                    //]
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['sold_on_local', 'required'],
            ['status', 'string'],
            ['recipient_id', 'integer'],
            ['bonuses', 'integer'],
            ['total_cost', 'integer'],
            ['sale_products', 'string'],
            ['sale_groups', 'string'],
            ['sale_groups', 'safe'],
            ['role', 'string'],
            ['role', 'safe'],
            ['role', 'required'],
            ['number', 'string'],
            ['sale_qty', 'string'],
            ['place', 'string'],
            ['action_id', 'integer'],
            [['created_at', 'updated_at', 'sold_on', 'bonuses_paid_at',
                'approved_by_admin_at', 'comment', 'address', 'project_id'], 'safe'],
            ['project_id', 'default', 'value' => Project::find()->select('id')
                ->where(['is_main' => 1])->asArray()->one()['id'],]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'recipient_id' => 'Получатель приза',
            'total_cost' => 'Стоимость материалов',
            'bonuses' => 'Бонусы',
            'created_at' => 'Дата внесения',
            'updated_at' => 'Дата изменения',
            'sold_on' => 'Дата продажи',
            'bonuses_paid_at' => 'Дата начисления бонусов',
            'approved_by_admin_at' => 'Дата одобрения администратором',
            'review_comment' => 'Комментарий',
            'number' => 'Номер',
            'place' => 'Место продажи',
            'sale_products' => 'Продукция',
            'sale_groups' => 'Бренды',
            'sale_qty' => 'Сумма в чеке',
            'action_id' => 'Акция',
            'comment' => 'Комментарий',
            'address' => 'Адрес',
            'project_id' => 'Юридическое лицо',
            'totalCost' => 'Сумма в чеке',
            'totalCount' => 'Количество',
        ];
    }

    public function fields()
    {
        $fields = [
            'id',
            'status',
            'status_label',
            'bonuses',
            //'sold_on_local',
            'created_at' => function (Sale $model) {
                return $model->created_at ? (new \DateTime($model->created_at))->format('d.m.Y') : null;
            },
            'number',
            'documents' => function (Sale $model) {
                return $model->getAttachedFiles(null, Sale::class)->orderBy('id')->all();
            },
            'projectPhoto' => function (Sale $model) {
                return $model->getAttachedFiles(null, ApiSale::class)->orderBy('id')->all();
            },
            'positions',
            'history',
            'action_id',
            'comment',
            'address',
        ];

        return $fields;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasMany(SaleHistory::class, ['sale_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositions()
    {
        return $this->hasMany(SalePosition::class, ['sale_id' => 'id'])->orderBy('id');
    }

    public static function getStatusValues()
    {
        return Statuses::statusesValues();
    }

    public function getStatusText()
    {
        $statuses = self::getStatusValues();

        return empty($statuses[$this->status]) ? null : $statuses[$this->status];
    }

    public static function getStatusLabel($status)
    {
        $statuses = self::getStatusValues();

        return empty($statuses[$status]) ? null : $statuses[$status];
    }

    public function getStatus_label()
    {
        $statuses = self::getStatusValues();

        return empty($statuses[$this->status]) ? null : $statuses[$this->status];
    }

    public function getProducts()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id'])->via('positions');
    }

    /**
     * Рассчитываем количество бонусов по формуле из позиции из акции
     *
     * @param bool $save
     * @return bool
     */
    public function updateBonuses($save = true)
    {
        $bonuses = 0;
        $sale_qty = 0;
        
        foreach ($this->getPositions()->each() as $position) {
            /** @var SalePosition $position */
            $bonuses += $position->updateBonuses($save, $this->role);
            $sale_qty += $position->quantity;
        }
        $this->bonuses = $bonuses;
        $this->sale_qty = $sale_qty;

        if ($save) {
            $this->updateAttributes(['bonuses', 'sale_qty']);
        }
        
        return true;
    }

    public function updateTotalCost()
    {
        $this->total_cost = $this->getPositions()->sum('cost');
        $this->updateAttributes(['total_cost']);
    }

    public function getStatusManager()
    {
        if ($this->_statusManager === null) {
            $this->_statusManager = new StatusManager($this);
        }
        return $this->_statusManager;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        SaleHistory::deleteAll(['sale_id' => $this->id]);
        SalePosition::deleteAll(['sale_id' => $this->id]);

        return true;
    }

    public function getProfile()
    {
        return $this->getRecipient();
    }

    public function getRecipient()
    {
        return $this->hasOne(Profile::class, ['id' => 'recipient_id']);
    }

    /**
     * Вывод позиций покупки в таблицу
     *
     * @param $saleId
     * @return array
     */
    public static function getSaleDataTable($saleId)
    {
        $arrItem = [];
        $arrReturn = [];
        $model = SalePosition::find()
            ->select('{{%sales_products}}.name as product_name, {{%sales_positions}}.quantity as product_quantity, {{%sales_positions}}.bonuses as product_bonuses')
            ->leftJoin('{{%sales_products}}', '{{%sales_products}}.id={{%sales_positions}}.product_id')
            ->where(['{{%sales_positions}}.sale_id' => $saleId])
            ->asArray()
            ->all();

        foreach ($model as $item) {
            $arrItem['product_name'] = $item['product_name'];
            $arrItem['product_quantity'] = $item['product_quantity'];
            $arrItem['product_bonuses'] = $item['product_bonuses'];
            $arrReturn[] = $arrItem;
        }
        return $arrReturn;
    }

    /**
     * @return SaleHistory[]
     */
    public function getOrderedHistory()
    {
        return SaleHistory::find()
            ->where(['sale_id' => $this->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }

    /**
     * @return SalePosition[]
     */
    public function getOrderedPositions()
    {
        return SalePosition::find()
            ->where(['sale_id' => $this->id])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    /**
     * @return bool
     */
    public function isDraft()
    {
        return $this->status == Statuses::DRAFT;
    }

    /**
     * @return bool
     */
    public function userCanEdit()
    {
        return $this->status == Statuses::DRAFT || $this->status == Statuses::ADMIN_REVIEW;
    }
    
    public function getTotalCost () {
        if ($this->role === Profile::ROLE_DESIGNER) {
            return $this->sale_qty;
        }
        return '';
    }
    
    public function getTotalCount () {
        if ($this->role === Profile::ROLE_DEALER || $this->role === Profile::ROLE_RTT) {
            return $this->sale_qty;
        }
        return '';
    }
}
