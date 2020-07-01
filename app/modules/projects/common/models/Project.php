<?php

namespace modules\projects\common\models;

use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\PurseOwnerInterface;
use marketingsolutions\finance\models\PurseOwnerTrait;
use marketingsolutions\finance\models\Transaction;
use ms\loyalty\catalog\common\cards\Card;
use ms\loyalty\prizes\payments\common\models\Settings;
use Yii;
use yii\db\ActiveQuery;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_projects".
 *
 * @property integer $id
 * @property string $title
 * @property string $id1c
 * @property boolean $is_enabled
 * @property boolean $is_main
 * @property string $created_at
 * @property string $updated_at
 */
class Project extends \yii\db\ActiveRecord implements ModelInfoInterface, PurseOwnerInterface
{
    use PurseOwnerTrait;

    /** @var Project|null */
    static $current = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%projects}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     *
     * @return string
     */
    public static function modelTitle()
    {
        return 'Юрлицо';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     *
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Юрлица';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
            ['id1c', 'string', 'max' => 40],
            ['is_enabled', 'safe'],
            ['is_main', 'safe'],
            ['created_at', 'safe'],
            ['updated_at', 'safe'],
            [['title', 'id1c'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название проекта',
            'id1c' => 'ID нашей 1С',
            'is_enabled' => 'Доступен',
            'is_main' => 'Остовной проект',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Returns purse's owner by owner's id
     *
     * @param int $id
     * @return $this
     */
    public static function findPurseOwnerById($id)
    {
        return static::findOne($id);
    }

    protected static function purseOwnerType()
    {
        return self::class;
    }

    public function createPurse()
    {
        if (null === Purse::findOne(['owner_type' => self::class, 'owner_id' => $this->id])) {
            Purse::create(self::class, $this->id, strtr('Счет проекта #{id} {id} ({title})', [
                '{id}' => $this->id,
                '{title}' => $this->title,
                '{id1c}' => $this->id1c
            ]));
        }
    }

    protected function updatePurse()
    {
        $this->purse->updateAttributes([
            'title' => strtr('Счет проекта #{id} {domain} ({title})', [
                '{id}' => $this->id,
                '{title}' => $this->title,
                '{id1c}' => $this->id1c
            ]),
        ]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->createPurse();
        }

        if (!$insert && ($this->isAttributeChanged('title') || $this->isAttributeChanged('domain')  || $this->isAttributeChanged('id1c'))) {
            $this->updatePurse();
        }
    }

    public static function getTitleOptions()
    {
        return self::find()->indexBy('id')->select('title, id')->orderBy(['title' => SORT_ASC])->column();
    }

    public static function getPurseIdOptions()
    {
        $projects = self::find()->indexBy('id');
        $options = [];

        foreach ($projects->each() as $project) {
            $purse = $project->purse;
            $key = $purse->id . '';
            $options[$key] = $project->title;
        }

        return $options;
    }

    public function calculateTransactionsSum($type)
    {
        /** @var ActiveQuery $query */
        $query = $this->purse->getPurseTransactions();
        $sum = $query->where(['type' => $type])->sum('amount');

        return $sum ?: 0;
    }

    public function calculateTransactionsSumIncoming()
    {
        return $this->calculateTransactionsSum(Transaction::INCOMING);
    }

    public function calculateTransactionsSumOutbound()
    {
        return $this->calculateTransactionsSum(Transaction::OUTBOUND);
    }

    public function getReportEmailsTest()
    {
        return $this->getAttributeArray('report_emails_test');
    }

    /**
     * @param string $attr
     * @return array
     */
    protected function getAttributeArray($attr)
    {
        if (empty($this->$attr)) {
            return [];
        }
        return array_map('trim', explode(';', $this->$attr));
    }
}
