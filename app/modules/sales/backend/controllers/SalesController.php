<?php

namespace modules\sales\backend\controllers;

use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\Transaction;
use modules\profiles\common\models\LegalPerson;
use modules\profiles\common\models\Profile;
use modules\actions\common\models\Action;
use modules\profiles\common\models\Region;
use modules\projects\common\models\Project;
use modules\sales\backend\models\SaleSearchWithData;
use modules\sales\common\actions\DownloadDocumentAction;
use modules\sales\common\actions\UploadImagesAction;
use modules\sales\common\finances\SalePartner;
use modules\sales\common\finances\SaleRollbackPartner;
use modules\sales\common\models\AddSalePosition;
use modules\sales\common\models\Group;
use modules\sales\common\models\Product;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleHistory;
use modules\sales\common\models\SalePosition;
use modules\sales\common\sales\statuses\Statuses;
use modules\sales\frontend\models\ApiSale;
use ms\loyalty\mobile\common\models\MobileNotification;
use ms\loyalty\prizes\payments\common\finances\RevertPaymentPartner;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\View;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\grid\columns\DataColumn;
use yz\admin\grid\filters\DateRangeFilter;
use yz\admin\mailer\common\models\Mail;
use yz\admin\traits\CheckAccessTrait;
use yz\Yz;
use yii\web\Response;

/**
 * SalesController implements the CRUD actions for Sale model.
 */
class SalesController extends Controller implements AccessControlInterface
{
    use CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::class,
                'dataProvider' => function ($params) {
                    $searchModel = Yii::createObject(SaleSearchWithData::class);
                    $dataProvider = $searchModel->search($params);

                    return $dataProvider;
                },
            ],
            'download-document' => [
                'class' => DownloadDocumentAction::class,
            ],
            'download-small-document' => [
                'class' => DownloadDocumentAction::className(),
                'format' => DownloadDocumentAction::TYPE_SMALL,
            ],
            'upload-images' => UploadImagesAction::class,
        ]);
    }

    /**
     * Lists all Sale models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject(SaleSearchWithData::className());
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $bonuses = $dataProvider->query->sum('sale.bonuses');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns(),
            'bonuses' => $bonuses,
        ]);
    }

    public function actionExportAll()
    {
        $data = (new \DateTime('now'))->format('d.m.Y');
        Yii::$app->response->sendFile(Yii::getAlias('@data/sales.xlsx'), "sales_$data.xlsx", ['inline' => false])->send();
    }

    public function getGridColumns()
    {
        $isExport = \Yii::$app->request instanceof \yii\console\Request
            || Yii::$app->request->getPathInfo() == 'sales/sales/export';

        if ($isExport) {
            set_time_limit(600);
            ini_set('memory_limit', '-1');
        }

        return [
            [
                'attribute' => 'id',

            ],
            [
                'attribute' => 'project__id',
                'label' => 'Юрлицо',
                'filter' => Project::getTitleOptions(),
                'titles' => Project::getTitleOptions(),
            ],
            [
                'attribute' => 'status',
                'titles' => Sale::getStatusValues(),
                'filter' => Sale::getStatusValues(),
                'labels' => [
                    //Statuses::DRAFT => DataColumn::LABEL_DEFAULT,
                    Statuses::ADMIN_REVIEW => DataColumn::LABEL_INFO,
                    Statuses::APPROVED => DataColumn::LABEL_SUCCESS,
                    Statuses::PAID => DataColumn::LABEL_SUCCESS,
                    Statuses::DECLINED => DataColumn::LABEL_DANGER,
                ],
            ],
            'bonuses',
            [
                'attribute' => 'position__product_id',
                'label' => 'Состав продажи',
                'contentOptions' => ['style' => 'width:350px; font-size:11px;'],
                'format' => 'raw',
                'value' => function (SaleSearchWithData $model) use ($isExport) {
                    return $isExport
                        ? $this->renderPartial('@modules/sales/backend/views/sales/_positions_export', ['sale' => $model])
                        : $this->renderPartial('@modules/sales/backend/views/sales/_positions', ['sale' => $model]);
                },
                'filter' => Product::getOptions(),
                'titles' => Product::getOptions(),
            ],
            [
                'label' => 'Фото ПРОЕКТ',
                'format' => 'raw',
                'value' => function (Sale $model) use ($isExport) {
                    if ($isExport) {
                        return '';
                    }
                    return $this->renderPartial('@ms/files/attachments/common/views/partial/_files', [
                        'files' => $model->getAttachedFiles(null, Sale::class)
                    ]);
                }
            ],
            [
                'label' => 'Фото РЕАЛИЗАЦИЯ',
                'format' => 'raw',
                'value' => function (Sale $model) use ($isExport) {
                    if ($isExport) {
                        return '';
                    }
                    return $this->renderPartial('@ms/files/attachments/common/views/partial/_files', [
                        'files' => $model->getAttachedFiles(null, ApiSale::class)
                    ]);
                }
            ],
            'address',
            [
                'attribute' => 'created_at',
                'contentOptions' => ['style' => 'text-align:center'],
                'filter' => DateRangeFilter::instance(),
                'value' => function (Sale $model) {
                    return (new \DateTime($model->created_at))->format('d.m.Y H:i');
                }
            ],
            [
                'attribute' => 'updated_at',
                'contentOptions' => ['style' => 'text-align:center'],
                'filter' => DateRangeFilter::instance(),
                'value' => function (Sale $model) {
                    return (new \DateTime($model->updated_at))->format('d.m.Y H:i');
                }
            ],
            [
                'attribute' => 'totalCost',
            ],
            [
                'attribute' => 'totalCount',
            ],
            [
                'attribute' => 'action_id',
                'filter' => Action::getList(),
                'titles' => Action::getList(),
            ],

            'profile__full_name',
            [
                'attribute' => 'dealer_id',
                'label' => 'Компания',
                'value' => function (Sale $model) {
                    if (!empty($model->profile) && !empty($model->profile->dealer)) {
                        return $model->profile->dealer->name;
                    }

                    return '';
                }
            ],
            'profile__phone_mobile',
            'profile__email',
            [
                'attribute' => 'profile__role',
                'label' => 'Роль',
                'filter' => Profile::getRoleOptions(),
                'titles' => Profile::getRoleOptions(),
                'contentOptions' => ['style' => 'width:160px;'],
            ],
            [
                'label' => 'История',
                'contentOptions' => ['style' => 'width:400px; font-size:12px;'],
                'format' => 'raw',
                'value' => function (Sale $model) use ($isExport) {
                    return $isExport
                        ? $this->renderPartial('@modules/sales/backend/views/sales/_history_export', ['sale' => $model])
                        : $this->renderPartial('@modules/sales/backend/views/sales/_history', ['sale' => $model]);
                }
            ],
        ];
    }

    /**
     * Updates an existing Sale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionApp($id)
    {
        $model = $this->findModel($id);

        if ($model->statusManager->adminCanEdit() == false) {
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('app', compact('model'));
    }

    public function actionEdit($id, $position_id = null)
    {

        $model = $this->findModel($id);
        if ($model->statusManager->adminCanEdit() == false) {
            return $this->redirect(['view', 'id' => $id]);
        }
        //Удаление
        if (!empty($position_id)) {
            $salePosition = SalePosition::findone($position_id);
            $salePosition->delete();
            $model->updateBonuses();
            $this->adminEdit($model);
            return $this->redirect(['edit', 'id' => $id]);
        }
        //Редактирование
        $positions = $model->positions;

        if (Model::loadMultiple($positions, Yii::$app->request->post()) && Model::validateMultiple($positions)) {
            foreach ($positions as $position) {
                /** @var Product $product */
                $product = Product::findOne($position->product_id);
                $position->bonuses_primary = $product->bonuses_formula;
                $position->save(false);
            }
            $model->updateBonuses();
            $this->adminEdit($model);
            return $this->redirect(['edit', 'id' => $id]);
        }
        //Добавление
        $addSalePositions = [];
        $addSalePosition = new AddSalePosition();
        $addSalePosition->sale_id = $id;
        $addSalePositions[] = $addSalePosition;

        if (Model::loadMultiple($addSalePositions, Yii::$app->request->post()) && Model::validateMultiple($addSalePositions)) {
            foreach ($addSalePositions as $addSalePosition) {
                $product = Product::findOne($addSalePosition->product_id);
                $addSalePosition->bonuses_primary = $product->bonuses_formula;
                $addSalePosition->save(false);
            }
            $model->updateBonuses();
            $this->adminEdit($model);
            return $this->redirect(['edit', 'id' => $id]);
        }

        return $this->render('edit', ['positions' => $positions, 'model' => $model, 'addSalePositions' => $addSalePositions]);
    }

    public function actionEditSale($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = 200;
        $data = Yii::$app->request->post();

        $model = new SaleEditForm();
        $model->sale_id = $id;
        $model->frontend = false;

        if ($model->load(['SaleEditForm' => $data]) && $model->process()) {
            Yii::$app->session->setFlash('success', 'Продажа успешно обновлена!');
            return ['result' => 'OK', 'redirect' => Url::to(['/sales/sales/view', 'id' => $model->sale_id])];
        }
        else {
            Yii::$app->response->statusCode = 400;
            return ['result' => 'FAIL', 'errors' => array_values($model->getFirstErrors())];
        }
    }

    /**
     * Deletes an existing Sale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(array $id)
    {
        $status = Yz::FLASH_SUCCESS;
        $message = is_array($id) ?
            \Yii::t('admin/t', 'Records were successfully deleted') : \Yii::t('admin/t', 'Record was successfully deleted');
        $id = (array) $id;

        foreach ($id as $id_) {
            $model = $this->findModel($id_);
            if ($model->statusManager->canBeDeleted()) {
                $this->findModel($id_)->delete();
            }
            else {
                $status = Yz::FLASH_WARNING;
                $message = 'Некоторые (или все) из выбранных покупок невозможно удалить, т.к. их статус не равен "Черновик"/"Новая"';
            }
        }

        \Yii::$app->session->setFlash($status, $message);

        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, 'Сохранено!');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionChangeStatus($id = null, $status = null)
    {
        $this->enableCsrfValidation = false;
        if (!$id) {
            $id = Yii::$app->request->post('id');
        }
        if (!$status) {
            $status = Yii::$app->request->post('status');
        }

        $model = $this->findModel($id);

        if ($model->statusManager->adminCanSetStatus($status)) {
            switch ($status) {
                case Statuses::DRAFT:
                    $this->draft($model);
                    Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Статус успешно изменен, Продажа возвращена на доработку участнику');
                    break;
                case Statuses::APPROVED:
                    $this->approved($model);
                    Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Продажа подтверждена, баллы за покупку подсчитаны');
                    break;
                case Statuses::PAID:
                    $this->paid($model);
                    //Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Продажа выплачена, начислены баллы за покупку');
                    break;
                case Statuses::DECLINED:
                    $this->declined($model);
                    Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Продажа отклонена');
                    break;
                default:
                    throw new NotFoundHttpException();
            }
        }
        else {
            Yii::$app->session->setFlash(Yz::FLASH_ERROR, 'Статус не может быть изменен');
        }

        return $this->redirect(\Yii::$app->request->referrer ?: Url::home());
    }

    /**
     * @param Sale $sale
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     */
    private function paid(Sale $sale)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            /** @var Profile $profile */
            $profile = Profile::findOne($sale->recipient_id);
            /** @var Project $profile */
            $project = Project::findOne($sale->project_id);
            $purse = $profile->getMultipurse($project);

            if ($profile == null || empty($purse)) {
                throw new Exception('Не найден участник или его кошелёк');
            }
            $oldStatus = $sale->status;

            $purse->addTransaction(Transaction::factory(
                Transaction::INCOMING,
                $sale->bonuses,
                SalePartner::findById($sale->id),
                'Бонусы за продажу #' . $sale->id . ' ' . $sale->project->title
            ), true, false);

            $sale->bonuses_paid_at = new Expression('NOW()');
            $sale->status = Statuses::PAID;
            $sale->save(false);

            $h = new SaleHistory();
            $h->sale_id = $sale->id;
            $h->status_old = $oldStatus;
            $h->status_new = $sale->status;
            $h->note = 'Продажа подтверждена и выплачена';
            $h->comment = null;
            $h->admin_id = Yii::$app->user->identity->getId();
            $h->type = SaleHistory::TYPE_PAY;
            $h->save(false);

            MobileNotification::createPush('Начисление баллов','Вам начислены  бонусные баллы за продажу  '. $sale->id." (". $sale->bonuses.")",$profile->id);

            $transaction->commit();

            /** @var Profile $profile */
            $profile = Profile::findOne($sale->recipient_id);

            $bodyHtml = (new View())->render('@modules/sales/common/mail/sale_paid', compact('sale', 'profile'));
            Mail::add($profile->email, 'Продажа одобрена, баллы начислены', $bodyHtml, $sale);
        }
        catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash(Yz::FLASH_ERROR, $e->getMessage());
        }

        return true;
    }

    /**
     * @param Sale $sale
     * @return bool
     */
    private function approved(Sale $sale)
    {
        $oldStatus = $sale->status;

        $sale->status = Statuses::APPROVED;
        $sale->save();

        $h = new SaleHistory();
        $h->sale_id = $sale->id;
        $h->status_old = $oldStatus;
        $h->status_new = $sale->status;
        $h->note = 'Продажа одобрена';
        $h->comment = null;
        $h->admin_id = Yii::$app->user->identity->getId();
        $h->type = SaleHistory::TYPE_APPROVE;
        $h->save(false);

        return true;
    }

    private function draft(Sale $sale)
    {
        $comment = Yii::$app->request->post('comment');
        $oldStatus = $sale->status;

        $sale->status = Statuses::DRAFT;
        $sale->save(false);

        $h = new SaleHistory();
        $h->sale_id = $sale->id;
        $h->status_old = $oldStatus;
        $h->status_new = $sale->status;
        $h->note = 'Продажа отправлена на доработку';
        $h->comment = $comment;
        $h->admin_id = Yii::$app->user->identity->getId();
        $h->type = SaleHistory::TYPE_DRAFT;
        $h->save(false);

        /** @var Profile $profile */
        $profile = Profile::findOne($sale->recipient_id);

        try {
            Yii::$app->mailer
                ->compose('@modules/sales/common/mail/sale_draft', [
                    'sale' => $sale,
                    'profile' => $profile,
                    'comment' => $comment,
                ])
                ->setTo($profile->email)
                ->send();
        }
        catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

    private function declined(Sale $sale)
    {
        $comment = Yii::$app->request->post('comment');
        $oldStatus = $sale->status;

        $sale->status = Statuses::DECLINED;
        $sale->save(false);

        $h = new SaleHistory();
        $h->sale_id = $sale->id;
        $h->status_old = $oldStatus;
        $h->status_new = $sale->status;
        $h->note = 'Продажа отклонена';
        $h->comment = $comment;
        $h->admin_id = Yii::$app->user->identity->getId();
        $h->type = SaleHistory::TYPE_DECLINE;
        $h->save(false);

        /** @var Profile $profile */
        $profile = Profile::findOne($sale->recipient_id);

        try {
            Yii::$app->mailer
                ->compose('@modules/sales/common/mail/sale_declined', [
                    'sale' => $sale,
                    'profile' => $profile,
                    'comment' => $comment
                ])
                ->setTo($profile->email)
                ->send();
        }
        catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

    private function adminEdit(Sale $sale)
    {
        $histories = $sale->getHistory()->orderBy('id')->all();
        $history = array_pop($histories);
        if ($history->admin_id === Yii::$app->user->identity->getId()) {
            return $history->save();
        }
        $h = new SaleHistory();
        $h->sale_id = $sale->id;
        $h->status_old = $sale->status;
        $h->status_new = $sale->status;
        $h->note = 'Состав продажи отредактирован администратором';
        $h->admin_id = Yii::$app->user->identity->getId();
        $h->type = SaleHistory::TYPE_UPDATE;
        $h->save(false);

        return true;
    }

    public function actionChangeStatusSelected(array $ids, $status)
    {
        $transaction = Sale::getDb()->beginTransaction();
        foreach ($ids as $id) {
            $model = $this->findModel($id);
            if ($model->statusManager->adminCanSetStatus($status)) {
                $model->statusManager->changeStatus($status);
            }
            else {
                \Yii::$app->session->setFlash(Yz::FLASH_ERROR, 'У ряда закупок статус не может быть изменен. Все изменения отменены');
                $transaction->rollBack();

                return $this->redirect(\Yii::$app->request->referrer ?: Url::home());
            }
        }
        $transaction->commit();
        Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Статус успешно изменен');

        return $this->redirect(\Yii::$app->request->referrer ?: Url::home());
    }

    /**
     * Finds the Sale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Sale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sale::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFileCatalog()
    {
        $path = Yii::getAlias('@modules/sales/common/files/catalog.xlsx');

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }

        throw new NotFoundHttpException();
    }

    public function actionFileSales()
    {
        $path = Yii::getAlias('@modules/sales/common/files/sales.xlsx');

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }

        throw new NotFoundHttpException();
    }

    public function actionRollback($id)
    {
        $model = $this->findModel($id);
        $t = \Yii::$app->db->beginTransaction();

        try {
            $oldStatus = $model->status;
            $model->status = Statuses::DECLINED;
            $model->bonuses_paid_at = null;
            $model->save(false);

            $project = Project::findone($model->project_id);
            /** @var Purse $companyPurse */
            $companyPurse = $project->purse;
            /** @var Profile $profile */
            $profile = Profile::findOne(['id' => $model->recipient_id]);
            $purse = $profile->getMultipurse($project);

            # добавить баллы на счет участника
            $transaction = Transaction::findOne([
                'purse_id' => $purse->id,
                'partner_type' => 'modules\sales\common\finances\SalePartner',
                'partner_id' => $id,
            ]);
            if (empty($transaction)) {
                throw new \Exception('Не найдена транзакция участника');
            }
            # списать со счета пользователя
            $purse->addTransaction(Transaction::factory(
                Transaction::INCOMING,
                -$transaction->amount,
                new SaleRollbackPartner(['id' => $model->id]),
                "Возврат продажи # {$model->id} ({$profile->full_name}) {$project->title}"
            ), true, false);

            # добавить на счет компании
            $transaction = Transaction::findOne([
                'purse_id' => $companyPurse->id,
                'partner_type' => 'modules\sales\common\finances\SalePartner',
                'partner_id' => $id,
            ]);
            if (empty($transaction)) {
                throw new \Exception('Не найдена транзакция у компании');
            }

            $companyPurse->addTransaction(Transaction::factory(
                Transaction::OUTBOUND,
                -$transaction->amount,
                new SaleRollbackPartner(['id' => $model->id]),
                "Откат продажи # {$model->id} ({$profile->full_name})"
            ));

            $h = new SaleHistory();
            $h->sale_id = $model->id;
            $h->status_old = $oldStatus;
            $h->status_new = $model->status;
            $h->note = 'Откат начисленных бонусов';
            $h->comment = null;
            $h->admin_id = Yii::$app->user->identity->getId();
            $h->type = SaleHistory::TYPE_ROLLBACK;
            $h->save(false);

            MobileNotification::createPush('Откат начисленных бонусов','Откат  начисленных за продажу # ' .$model->id.' бонусов ('. $transaction->amount.')',$profile->id);

            $t->commit();
            Yii::$app->session->setFlash(Yz::FLASH_SUCCESS,
                "Откат продажи # {$model->id} ({$profile->full_name})");
        }
        catch (\Exception $e) {
            $t->rollBack();
            Yii::$app->session->setFlash(Yz::FLASH_ERROR, $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
}
