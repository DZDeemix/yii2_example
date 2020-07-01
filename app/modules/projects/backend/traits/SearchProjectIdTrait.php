<?php

namespace modules\projects\backend\traits;

use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;

trait SearchProjectIdTrait
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'integer'],
        ]);
    }

    public function getProjectIdField()
    {
        return 'project_id';
    }

    public function getQuery()
    {
        if (!Project::$current) {
            throw new MissingProjectException();
        }

        $query = parent::getQuery();

        if (!Project::$current->is_main) {
            $query->andWhere([$this->getProjectIdField() => Project::$current->id]);
        }

        return $query;
    }

    protected function prepareFilters($query)
    {
        $query->andFilterWhere([$this->getProjectIdField() => $this->project_id]);

        parent::prepareFilters($query);
    }
}