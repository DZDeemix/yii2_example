<?php

namespace modules\projects;

use modules\burnpoints\backend\controllers\NullifyController;
use modules\burnpoints\backend\models\BurnPointWithData;
use modules\burnpoints\common\commands\NullifyCommand;
use modules\burnpoints\common\models\BurnPoint;
use modules\profiles\backend\controllers\ProfileTransactionsController;
use modules\profiles\backend\models\ProfileTransactionSearch;
use modules\profiles\common\models\Profile;
use modules\projects\backend\models\ProjectBurnPointWithData;
use modules\projects\backend\models\ProjectCardItemSearch;
use modules\projects\backend\models\ProjectCatalogOrderSearch;
use modules\projects\backend\models\ProjectOrderedCardWithProfileSearch;
use modules\projects\backend\models\ProjectPaymentSearch;
use modules\projects\backend\models\ProjectProfileTransactionSearch;
use modules\projects\backend\models\ProjectTransactionForm;
use modules\projects\backend\models\ProjectTransactionSearch;
use modules\projects\common\commands\ProjectExportOrderTo1cCommand;
use modules\projects\common\commands\ProjectNullifyCommand;
use modules\projects\common\commands\ProjectProfileMultipurseCommand;
use modules\projects\common\commands\ProjectSendPaymentCommand;
use modules\projects\common\models\Project;
use modules\projects\common\models\ProjectBurnPoint;
use modules\projects\common\models\ProjectCardItem;
use modules\projects\common\models\ProjectCatalogOrder;
use modules\projects\common\models\ProjectCompanyAccount;
use modules\projects\common\models\ProjectCompanyFinancesWidgetReport;
use modules\projects\common\models\ProjectOrderedCard;
use modules\projects\common\models\ProjectPayment;
use modules\projects\frontend\forms\ProjectApiOrderForm;
use modules\projects\frontend\forms\ProjectCreatePaymentForm;
use ms\loyalty\catalog\backend\controllers\CardItemsController;
use ms\loyalty\catalog\backend\controllers\CatalogOrdersController;
use ms\loyalty\catalog\backend\controllers\OrderedCardsController;
use ms\loyalty\catalog\backend\models\CardItemSearch;
use ms\loyalty\catalog\backend\models\CatalogOrderSearch;
use ms\loyalty\catalog\backend\models\OrderedCardWithProfileSearch;
use ms\loyalty\catalog\common\cards\CardItem;
use ms\loyalty\catalog\common\commands\ExportOrderTo1cCommand;
use ms\loyalty\catalog\common\models\CatalogOrder;
use ms\loyalty\catalog\common\models\OrderedCard;
use ms\loyalty\catalog\frontend\api\v3\forms\ApiOrderForm;
use ms\loyalty\finances\backend\controllers\DashboardController;
use ms\loyalty\finances\backend\controllers\TransactionsController;
use ms\loyalty\finances\backend\forms\TransactionForm;
use ms\loyalty\finances\backend\models\TransactionSearch;
use ms\loyalty\finances\backend\reports\CompanyFinancesWidgetReport;
use ms\loyalty\finances\common\components\CompanyAccount;
use ms\loyalty\prizes\payments\backend\controllers\PaymentsController;
use ms\loyalty\prizes\payments\backend\models\PaymentSearch;
use ms\loyalty\prizes\payments\common\commands\SendPaymentCommand;
use ms\loyalty\prizes\payments\common\models\Payment;
use ms\loyalty\prizes\payments\frontend\forms\CreatePaymentForm;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Controller;
use yz\admin\grid\GridView;

/**
 * Class Bootstrap
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     * @throws \yii\base\ExitException
     */
    public function bootstrap($app)
    {
        $isBackend = preg_match('/backend$/', basename(Yii::getAlias('@app'))) > 0;

        $this->bindProfiles($isBackend);
        $this->bindFinances($isBackend);
        $this->bindCatalog($isBackend);
        $this->bindPayments($isBackend);
        $this->bindBurnpoints($isBackend);
    }

    protected function bindProfiles($isBackend)
    {
        Event::on(Profile::class, Profile::EVENT_AFTER_INSERT, function (Event $event) {
            /** @var Profile $profile */
            $profile = $event->sender;
            $command = new ProjectProfileMultipurseCommand(['profile' => $profile]);
            $command->handle();
        });
        Event::on(Project::class, Project::EVENT_AFTER_INSERT, function (Event $event) {
            $command = new ProjectProfileMultipurseCommand();
            $command->handle();
        });
    }

    protected function bindFinances($isBackend)
    {
        Yii::$container->set(TransactionSearch::class, ProjectTransactionSearch::class);
        Yii::$container->set(TransactionForm::class, ProjectTransactionForm::class);
        Yii::$container->set(CompanyAccount::class, ProjectCompanyAccount::class);
        Yii::$container->set(CompanyFinancesWidgetReport::class, ProjectCompanyFinancesWidgetReport::class);

        if ($isBackend) {
            # Транзакции движения баллов участников
            Yii::$container->set(ProfileTransactionSearch::class, ProjectProfileTransactionSearch::class);
            Event::on(ProfileTransactionsController::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
                Event::on(
                    GridView::class,
                    GridView::EVENT_SETUP_GRID,
                    function (Event $event) {
                        /** @var GridView $grid */
                        $grid = $event->sender;
                        $grid->columns = array_merge(array_slice($grid->columns, 0, -1), [
                            [
                                'attribute' => 'purse__project_id',
                                'label' => 'Проект',
                                'filter' => Project::getTitleOptions(),
                                'titles' => Project::getTitleOptions(),
                                'contentOptions' => ['style' => 'width:260px; font-size:12px;'],
                            ],
                        ], array_slice($grid->columns, -1));
                    }
                );
            });

            # Транзакции по счету клиента
            Event::on(TransactionsController::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
                if (Yii::$app->request->pathInfo === 'finances/transactions/create') {
                    Yii::$app->response->redirect('/projects/transactions/create');
                    Yii::$app->end();
                }
                Event::on(
                    GridView::class,
                    GridView::EVENT_SETUP_GRID,
                    function (Event $event) {
                        /** @var GridView $grid */
                        $grid = $event->sender;
                        $grid->columns = array_merge(array_slice($grid->columns, 0, -1), [
                            [
                                'attribute' => 'purse_id',
                                'label' => 'Проект',
                                'filter' => Project::getPurseIdOptions(),
                                'titles' => Project::getPurseIdOptions(),
                                'contentOptions' => ['style' => 'width:260px; font-size:12px;'],
                            ],
                        ], array_slice($grid->columns, -1));
                    }
                );
            });

            Event::on(DashboardController::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
                if (Yii::$app->request->pathInfo === 'finances/dashboard/index') {
                    Yii::$app->response->redirect('/finances/transactions/index');
                    Yii::$app->end();
                }
            });

            Event::on(CatalogOrdersController::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
                if (Yii::$app->request->pathInfo === 'catalog/catalog-orders/rollback') {
                    Yii::$app->response->redirect(
                        array_merge(['/projects/project-catalog-orders/rollback'],
                            Yii::$app->request->getQueryParams())
                    );
                    Yii::$app->end();
                }
            });

            Event::on(PaymentsController::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
                if (Yii::$app->request->pathInfo === 'payments/payments/rollback') {
                    Yii::$app->response->redirect(
                        array_merge(['/projects/project-payments/rollback'],
                            Yii::$app->request->getQueryParams())
                    );
                    Yii::$app->end();
                }
            });
        }
    }

    protected function bindCatalog($isBackend)
    {
        Yii::$container->set(CardItem::class, ProjectCardItem::class);
        Yii::$container->set(CatalogOrder::class, ProjectCatalogOrder::class);
        Yii::$container->set(OrderedCard::class, ProjectOrderedCard::class);
        Yii::$container->set(ApiOrderForm::class, ProjectApiOrderForm::class);
        Yii::$container->set(ExportOrderTo1cCommand::class, ProjectExportOrderTo1cCommand::class);

        if ($isBackend) {


            Yii::$container->set(OrderedCardWithProfileSearch::class, ProjectOrderedCardWithProfileSearch::class);
            Event::on(OrderedCardsController::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
                Event::on(
                    GridView::class,
                    GridView::EVENT_SETUP_GRID,
                    function (Event $event) {
                        /** @var GridView $grid */
                        $grid = $event->sender;
                        $grid->columns = array_merge(array_slice($grid->columns, 0, 9), [
                            [
                                'attribute' => 'project_id',
                                'label' => 'Проект',
                                'filter' => Project::getTitleOptions(),
                                'titles' => Project::getTitleOptions(),
                                //'contentOptions' => ['style' => 'width:200px; font-size:12px;'],
                            ],
                            [
                                'attribute' => 'profile__role',
                                'label' => 'Роль',
                                'filter' => Profile::getRoleOptions(),
                                'titles' => Profile::getRoleOptions(),
                                'contentOptions' => ['style' => 'width:160px;'],
                            ],

                        ], array_slice($grid->columns, 9));
                    }
                );
            });
            $this->gridEvent(
                CatalogOrdersController::class,
                CatalogOrderSearch::class,
                ProjectCatalogOrderSearch::class
            );

            $this->gridEvent(
                CardItemsController::class,
                CardItemSearch::class,
                ProjectCardItemSearch::class
            );
        }
    }
    
    protected function bindBurnpoints($isBackend)
    {
        Yii::$container->set(NullifyCommand::class, ProjectNullifyCommand::class);
        Yii::$container->set(BurnPoint::class, ProjectBurnPoint::class);

        if ($isBackend) {
            $this->gridEvent(
                NullifyController::class,
                BurnPointWithData::class,
                ProjectBurnPointWithData::class
            );

        }
    }

    protected function bindPayments($isBackend)
    {
        Yii::$container->set(Payment::class, ProjectPayment::class);
        Yii::$container->set(SendPaymentCommand::class, ProjectSendPaymentCommand::class);
        Yii::$container->set(CreatePaymentForm::class, ProjectCreatePaymentForm::class);

        if ($isBackend) {
            Yii::$container->set(PaymentSearch::class, ProjectPaymentSearch::class);
            Event::on(PaymentsController::class, Controller::EVENT_BEFORE_ACTION, function (Event $event) {
                Event::on(
                    GridView::class,
                    GridView::EVENT_SETUP_GRID,
                    function (Event $event) {
                        /** @var GridView $grid */
                        $grid = $event->sender;
                        $grid->columns = array_merge(array_slice($grid->columns, 0, 3), [
                            [
                                'attribute' => 'project_id',
                                'label' => 'Проект',
                                'filter' => Project::getTitleOptions(),
                                'titles' => Project::getTitleOptions(),
                                //'contentOptions' => ['style' => 'width:200px; font-size:12px;'],
                            ],
                            [
                                'attribute' => 'profile__full_name',
                                'label' => 'Участник'
                            ],
                            [
                                'attribute' => 'profile__phone_mobile',
                                'label' => 'Телефон'
                            ],
                            [
                                'attribute' => 'profile__role',
                                'label' => 'Роль',
                                'filter' => Profile::getRoleOptions(),
                                'titles' => Profile::getRoleOptions(),
                                'contentOptions' => ['style' => 'width:160px;'],
                            ],

                        ], array_slice($grid->columns, 3));
                    }
                );
            });
        }
    }

    protected function gridEvent($controllerClass, $baseClass, $replaceClass)
    {
        Event::on(
            $controllerClass,
            Controller::EVENT_BEFORE_ACTION,
            function (Event $event) use ($baseClass, $replaceClass) {
                Yii::$container->set($baseClass, $replaceClass);
                $this->gridAddColumnProjectId();
            }
        );
    }

    protected function gridAddColumnProjectId()
    {
        Event::on(
            GridView::class,
            GridView::EVENT_SETUP_GRID,
            function (Event $event) {
                /** @var GridView $grid */
                $grid = $event->sender;
                $grid->columns = array_merge(array_slice($grid->columns, 0, -1), [
                    [
                        'attribute' => 'project_id',
                        'label' => 'Проект',
                        'filter' => Project::getTitleOptions(),
                        'titles' => Project::getTitleOptions(),
                        'contentOptions' => ['style' => 'width:200px; font-size:12px;'],
                    ],
                ], array_slice($grid->columns, -1));
            }
        );
    }
}

