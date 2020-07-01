<?php

namespace modules\profiles\common\models;

use Yii;
use yii\db\Query;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_dealers".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $inn
 * @property string $class
 * @property string $type
 * @property string $external_id
 * @property integer $leader_id
 * @property integer $city_id
 * @property integer $company_id
 * @property integer $region_id
 * @property string $address
 * @property string $document
 * @property string $created_at
 * @property string $updated_at
 * @property string $corporation
 *
 * @property Profile[] $profiles
 * @property City $city
 * @property Region $region
 * @property Profile $leader
 */
class Dealer extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    const TYPE_DEALER = 'dealer';
    const TYPE_RTT = 'rtt';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dealers}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     *
     * @return string
     */
    public static function modelTitle()
    {
        return 'Дистрибьютор';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     *
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Дистрибьюторы';
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
            ['name', 'string', 'max' => 255],
            ['name', 'required'],
            ['leader_id', 'integer'],
            ['city_id', 'integer'],
            ['region_id', 'integer'],
            ['company_id', 'integer'],
            ['document', 'string', 'max' => 500],
            ['address', 'string', 'max' => 255],
            ['corporation', 'string', 'max' => 255],
            ['code', 'string', 'max' => 255],
            ['class', 'string', 'max' => 255],
            ['inn', 'string', 'max' => 255],
            ['type', 'string', 'max' => 20],
            ['external_id', 'string'],
            ['created_at', 'safe'],
            ['updated_at', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название РТТ',
            'address' => 'Адрес',
            'document' => 'Документы',
            'code' => 'Код компании',
            'class' => 'Класс компании',
            'inn' => 'ИНН компании',
            'type' => 'Тип компании',
            'external_id' => 'ID компании внешний',
            'city_id' => 'Город',
            'region_id' => 'Регион',
            'leader_id' => 'Руководитель',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
            'company_id' => 'Название Компании',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'type',
            'name',
            'address',
            'class',
            'inn',
        ];
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_DEALER => 'Дилер',
            self::TYPE_RTT => 'РТТ',
        ];
    }

    public function isDealer()
    {
        return $this->type === self::TYPE_DEALER;
    }

    public function isRtt()
    {
        return $this->type === self::TYPE_RTT;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::class, ['dealer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(\ms\loyalty\location\common\models\City::class, ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(\ms\loyalty\location\common\models\Region::class, ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(Profile::class, ['id' => 'leader_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }

    public function getCompanyName()
    {
        return $this->company->name;
    }

    public function getDocuments()
    {
        $documents = empty($this->document) ? [] : explode(';', $this->document);
        $urls = [];
        $baseUrl = Yii::getAlias("@frontendWeb/data/dealer-documents/");
        for ($i = 0; $i < count($documents); $i++) {
            $urls[] = $baseUrl . $documents[$i];
        }
        return $urls;
    }

    /**
     * @param array $filenames
     */
    public function setDocuments($filenames)
    {
        $this->document = implode(';', $filenames);
        $this->save(false);
    }

    public function addDocument($filename)
    {
        $documents = empty($this->document) ? [] : explode(';', $this->document);
        $documents[] = $filename;
        $this->document = implode(';', $documents);
        $this->save(false);
    }

    public function removeDocument($filename)
    {
        $path = Yii::getAlias("@frontendWebroot/data/dealer-documents/$filename");
        if (file_exists($path)) {
            @unlink($path);
        }

        $documents = empty($this->document) ? [] : explode(';', $this->document);
        $documents = array_diff($documents, [$filename]);
        $this->document = implode(';', $documents);
        $this->save(false);
    }

    public static function getOptions()
    {
        $raw = (new Query)
            ->select(['d.id', 'd.code', 'name' => 'd.name', 'city' => 'c.title', 'region' => 'r.title'])
            ->from(['d' => Dealer::tableName()])
            ->leftJoin(['c' => \ms\loyalty\location\common\models\City::tableName()], 'd.city_id = c.id')
            ->leftJoin(['r' => \ms\loyalty\location\common\models\Region::tableName()], 'r.id = c.region_id')
            ->orderBy(['c.title' => SORT_ASC])
            ->all();

        $options = [];

        foreach ($raw as $r) {
            $key = $r['id'];
            $option = [];
            if (!empty($r['code'])) {
                $option[] = $r['code'];
            }
            $option[] = $r['name'];
            if (!empty($r['city'])) {
                $option[] = $r['city'];
            }
            if (!empty($r['region'])) {
                $option[] = $r['region'];
            }
            $options[$key] = implode(', ', $option);
        }

        return $options;
    }

    public static function getNameOptions()
    {
        return self::find()->indexBy('name')->select('name')->orderBy(['name' => SORT_ASC])->column();
    }
}
