<?php

namespace modules\projects\backend\models;

use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;
use ms\loyalty\catalog\backend\models\CatalogOrderSearch;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectCatalogOrderSearch extends CatalogOrderSearch
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'integer'],
        ]);
    }

    public function getQuery()
    {
        $query = parent::getQuery();

        return $query;
    }

    protected function prepareFilters($query)
    {
        $query->andFilterWhere(['catalogOrder.project_id' => $this->project_id]);

        parent::prepareFilters($query);
    }
}
