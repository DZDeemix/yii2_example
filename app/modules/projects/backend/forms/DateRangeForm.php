<?php

namespace modules\projects\backend\forms;

use yii\base\Model;

class DateRangeForm extends Model
{
	public $from = null;
	public $to = null;
	public $project_id = null;

	public function rules()
	{
		return [
			[['from', 'to'], 'safe'],
            ['project_id', 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [
			'from' => 'с',
			'to' => 'по',
            'project_id' => 'проект',
		];
	}
}