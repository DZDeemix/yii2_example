<?php

namespace modules\projects\common\models;

use ms\loyalty\catalog\common\cards\CardItem;
use ms\loyalty\catalog\common\models\OrderedCard;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectOrderedCard extends OrderedCard
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'required'],
            ['project_id', 'integer'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'project_id' => 'Проект'
        ]);
    }

    public function beforeValidate()
    {
        $this->setProjectId();

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->setProjectId();
        }

        return true;
    }

    /**
     * Binds card item to this ordered card
     *
     * @param CardItem $cardItem
     */
    public function bindCardItem($cardItem)
    {
        parent::bindCardItem($cardItem);

        $cardItem->project_id = $this->catalogOrder->project_id;
    }

    protected function setProjectId()
    {
        if (empty($this->project_id)) {
            $this->project_id = $this->catalogOrder->project_id;
        }
    }
}
