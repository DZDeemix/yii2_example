<?php

namespace modules\profiles\frontend\forms;

use modules\profiles\common\models\Dealer;
use yii\base\Model;

class AutocompleteCompanyForm extends Model
{
    /** @var string */
    public $name;

    /** @var integer|null */
    public $city_id = null;

    /** @var string|null */
    public $type = null;

    /** @var integer */
    public $limit = 20;

    public function rules()
    {
        return [
            ['name', 'string'],
            ['name', 'required'],
            ['city_id', 'integer'],
            ['type', 'string'],
            ['limit', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название компании',
            'type' => 'Тип компании',
            'city_id' => 'Идентификатор города',
            'limit' => 'Лимит записей',
        ];
    }

    public function findAutocomplete()
    {
        $query = Dealer::find()->orderBy('name')
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere(['city.id' => $this->city_id])
            ->andFilterWhere(['type' => $this->type])
            ->limit($this->limit);

        $dealers = $query->all();

        return $dealers;
    }
}