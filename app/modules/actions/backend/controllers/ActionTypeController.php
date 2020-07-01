<?php

namespace modules\actions\backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\FileHelper;
use yz\admin\actions\ToggleAction;
use yz\admin\grid\columns\ToggleColumn;
use yz\Yz;
use yz\admin\actions\ExportAction;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\admin\contracts\AccessControlInterface;
use modules\actions\common\models\ActionType;
use modules\actions\backend\models\ActionTypeSearch;
use modules\actions\common\types\ActionTypeInterface;

/**
 * ActionTypeController implements the CRUD actions for ActionType model.
 */
class ActionTypeController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'toggle' => [
                'class' => ToggleAction::className(),
                'modelClass' => ActionType::className(),
                'attributes' => ['active'],
            ],
            'export' => [
                'class' => ExportAction::class,
                'searchModel' => function($params) {
                    /** @var ActionTypeSearch $searchModel */
                    return Yii::createObject(ActionTypeSearch::class);
                },
                'dataProvider' => function($params, ActionTypeSearch $searchModel) {
                        $dataProvider = $searchModel->search($params);
                        return $dataProvider;
                    },
            ]
        ]);
    }

    /**
     * Lists all ActionType models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var ActionTypeSearch $searchModel */
        $searchModel = Yii::createObject(ActionTypeSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns($searchModel),
        ]);
    }

    public function getGridColumns(ActionTypeSearch $searchModel)
    {
        return [
			'id',
			'title',
			'short_description',
			'className',
			'created_at:datetime',
            [
                'class' => ToggleColumn::className(),
                'attribute' => 'active',
            ]
			// 'updated_at:datetime',
        ];
    }

    public function actionRefresh()
    {
        $result = true;
//        $count = 0;

        foreach (FileHelper::findFiles(Yii::getAlias("@modules/actions/common/types"), ['only' => ['*ActionType.php']]) as $file) {
            $relativePath = basename($file);
            $controllerBaseClassName = substr($relativePath, 0, -4); // Removing .php
            $controllerClassName = ltrim('modules\actions\common\types\\' . $controllerBaseClassName);
            $ref = new \ReflectionClass($controllerClassName);

            if ($ref->implementsInterface(ActionTypeInterface::class)) {
                $actionType = new $controllerClassName;
                echo $actionType->title();

                $model = ActionType::findOne(['className' => $controllerClassName]);

                if (null === $model) {
                    $model = new ActionType;
                }

                $model->title = $actionType->title();
                $model->short_description = $actionType->shortDescription();
                $model->className = $controllerClassName;

                if ($model->save() === false) {
                    Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Не удалось добавить акцию: ' .implode(', ', $model->getFirstErrors()));

                    return $this->redirect(['/actions/action-type/index']);
                }
            }
        }

        Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Акции успешно обновлены');

        return $this->redirect(['/actions/action-type/index']);
    }

    /**
     * Finds the ActionType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActionType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActionType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
