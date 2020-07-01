<?php

namespace modules\sales\frontend\controllers\api;

use modules\profiles\common\models\LegalPerson;
use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleReport;
use modules\sales\frontend\models\ApiSale;
use modules\sales\common\models\Category;
use modules\actions\common\models\Action;
use Yii;
use ms\loyalty\api\frontend\base\ApiController;

/**
 * Class SaleController
 */
class SaleController extends ApiController
{
    public function actionList()
    {
        $profile_id = Yii::$app->request->post('profile_id', null);
        $profile = Profile::findOne($profile_id);

        if (null === $profile) {
            return $this->error("Не найден участник");
        }

        $sales = array();
        $raw = Sale::find()
            ->where(['recipient_id' => $profile->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        # Добавляем название Категории в позицию и Акции в продажу
        if(!empty($raw)) {
            foreach ($raw as $key => $r) {
                $sales[$key] = $r->toArray();

                if(!empty($r->positions)) {
                    //$r->positions = $r->positions->toArray();
                    foreach ($r->positions as $k => $p) {
                        $sales[$key]['positions'][$k] = $p->toArray();
                        if(!empty($p['category_id'])) {
                            $category = Category::findOne(['id' => $p['category_id']]);
                            if(!empty($category)) {
                                //$p['category_name'] = $category->name;
                                $sales[$key]['positions'][$k]['category_name'] = $category->name;
                            }
                        }
                    }
                }

                /** @var Action $action */
                $action = Action::findOne(['id' => $r->action_id]);
                if(!empty($action)) {
                    $sales[$key]['action_name'] = $action->name;
                }
            }
        }

        return $this->ok(['sales' => $sales], "Получение списка продаж участником");
    }

    public function actionView()
    {
        $profile_id = (int) Yii::$app->request->post('profile_id', null);
        $sale_id = (int) Yii::$app->request->post('sale_id', null);
        $profile = Profile::findOne($profile_id);
        $err = 'Ошибка получения деталей продажи';

        if (null === $profile) {
            return $this->error('Не найден участник', $err);
        }

        $sale = Sale::find()->where(['id' => $sale_id, 'recipient_id' => $profile_id])->one();

        if (null === $sale) {
            return $this->error("У участника не найдена продажа", $err);
        }

        return $this->ok(['sale' => $sale], 'Получение деталей продажи участником');
    }


    public function actionCreate()
    {
        $model = new ApiSale();
        $model->load(Yii::$app->request->post(), '');
        $model->projectPhotos = Yii::$app->request->post('projectPhotos');
        
        if ($model->apiSave()) {
            return $this->ok(['sale' => Sale::findOne($model->id)], 'Успешное добавление продажи');
        }

        return $this->error($model->getFirstErrors(), 'Ошибка внесения новой продажи');
    }

    public function actionUpdate()
    {
        $profile_id = (int) Yii::$app->request->post('profile_id', null);
        $sale_id = (int) Yii::$app->request->post('sale_id', null);
        $profile = Profile::findOne($profile_id);
        $err = 'Ошибка обновления продажи';

        if (null === $profile) {
            return $this->error('Не найден участник', $err);
        }
        /** @var ApiSale $model */
        $model = ApiSale::find()->where(['id' => $sale_id, 'recipient_id' => $profile_id])->one();

        if (null === $model) {
            return $this->error("У участника не найдена продажа", $err);
        }

        $model->load(Yii::$app->request->post(), '');
        
        if ($model->apiSave()) {
            return $this->ok(['sale' => Sale::findOne($model->id)], 'Успешное обновление продажи');
        }

        return $this->error($model->getFirstErrors(), $err);
    }

    public function actionUploadReport()
    {
        $err = 'Ошибка добавления отчета о продажах';

        $sale_report = new SaleReport();
        $sale_report->load(Yii::$app->request->post(), '');
        if ($sale_report->apiSave()) {
            return $this->ok(['saleReport' => SaleReport::findOne($sale_report->id)], 'Успешное добавление отчета о продажах');
        }

        return $this->error($sale_report->getFirstErrors(), $err);
    }
    
    public function actionGetRulesPath()
    {
        $rules = [
            'sales_rtt' => yii::getAlias('@frontendWeb/media/uploads/sales_rtt.pdf'),
            'sales_designer' => yii::getAlias('@frontendWeb/media/uploads/sales_designer.pdf'),
        ];
        return $this->ok(['rules' => $rules], '');
    }
    
    public function actionGetProjectsList()
    {
        $err = 'Ошибка получения юрлиц';
        $projects = Project::getTitleOptions();
        $res = [];
        
        foreach ($projects as $k => $v) {
            $res[] = [
                'name' => $v,
                'value' => $k,
            ];
        }
        
        if (!empty($res)) {
            return $this->ok(['list' => $res], 'Успешное получение юрлиц');
        }
    
        return $this->error('Не найдены фирмы', $err);
    }
}
