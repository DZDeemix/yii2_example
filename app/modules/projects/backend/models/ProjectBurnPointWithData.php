<?php


namespace modules\projects\backend\models;


use modules\burnpoints\backend\models\BurnPointWithData;

class ProjectBurnPointWithData extends BurnPointWithData
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'safe'],
        ]);
    }
    
    protected function prepareFilters($query)
    {
        $query->andFilterWhere(['project_id' => $this->project_id]);
        
        parent::prepareFilters($query);
    }
}
