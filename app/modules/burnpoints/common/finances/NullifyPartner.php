<?php


namespace modules\burnpoints\common\finances;


use marketingsolutions\finance\models\TransactionPartnerInterface;
use modules\profiles\common\models\Profile;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class NullifyPartner extends BaseObject implements TransactionPartnerInterface
{
    public $id;
    protected static $_titles = [];

    public function __construct($id, $config = [])
    {
        $this->id = $id;
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public static function findById($id)
    {
        return \Yii::createObject([
            'class' => self::class,
        ], [$id]);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getTitleForTransaction()
    {
        if (!array_key_exists($this->id, self::$_titles)) {
            self::$_titles[$this->id] = ArrayHelper::getValue(Profile::findOne($this->id), 'id', 'Неизвестно');
        }
        return self::$_titles[$this->id];
    }

    /**
     * @inheritDoc
     */
    public function getTypeForTransaction()
    {
        return 'Сгорание баллов участника';
    }
}