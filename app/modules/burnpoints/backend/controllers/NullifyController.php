<?php


namespace modules\burnpoints\backend\controllers;


use modules\burnpoints\backend\models\BurnPointWithData;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;

class NullifyController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        /**
         * @var BurnPointWithData $searchModel
         * @var ActiveDataProvider $dataProvider
         */
        $searchModel = Yii::createObject(BurnPointWithData::class);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $this->getGridColumns()
        ]);
    }

    public function getGridColumns(): array
    {
        return [
            'id',
            'profile_id',
            'profile__full_name',
            'profile__phone_mobile',
            'profile__email',
            'amount',
            'transaction_id',
            'transaction__title'
        ];
    }
}