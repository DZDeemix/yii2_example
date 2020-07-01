<?php
namespace modules\actions\frontend\controllers\api;

use modules\actions\common\models\ActionParticipant;
use modules\actions\frontend\forms\ActionParticipantForm;
use Yii;
use ms\loyalty\api\frontend\base\ApiController;
use modules\actions\frontend\forms\ActionListForm;
use modules\actions\frontend\forms\ActionViewForm;

class ActionController extends ApiController
{
    /**
     * @api {post} actions/api/action/current-list Список акций
     * @apiDescription Получить список всех доступных для участника акций
     * @apiName ActionsCurrent
     * @apiGroup Actions
     *
     * @apiParam {Integer} profile_id Индификатор пользователя
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *   "profile_id": 1
     * }
     *
     * @apiSuccess {String} result Статус ответа "OK"
     * @apiSuccess {Object} actions Список акций
     * @apiSuccess {Integer} actions.id Индификатор
     * @apiSuccess {String} actions.title Название
     * @apiSuccess {Integer} actions.planForAction План по акции
     * @apiSuccess {String} actions.short_description Короткое описание
     * @apiSuccess {String} actions.description HTML описание
     * @apiSuccess {String} actions.start_on Дата начала
     * @apiSuccess {String} actions.end_on Дата окончания
     * @apiSuccess {String} actions.has_products Отображать или нет каталог продукции
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * {
     *   "result": "OK",
     *   "actions": [
     *     {
     *       "id": 10,
     *       "title": "Название",
     *       "short_description": "Короткое описание",
     *       "planForAction": "План по акции",
     *       "description": "<p>HTML описание акции</p>",
     *       "start_on": "2019-02-28",
     *       "end_on": "2019-02-21",
     *       "has_products": 0
     *     },
     *     {
     *       "id": 11,
     *       "title": "Название другой акции",
     *       "short_description": "Короткое описание",
     *       "description": "<p>HTML описание акции</p>",
     *       "start_on": "2019-02-18",
     *       "end_on": "2019-03-05",
     *       "has_products": 1
     *     }
     *   ],
     * }
     *
     */
    public function actionCurrentList()
    {
        $model = new ActionListForm;

        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            $this->logResponse = false;

            return $this->ok(['actions' => $model->search()], 'Получение списка всех акций');
        }

        return $this->error($model->getFirstErrors(), 'Ошибка при получении списка всех акций');
    }

    /**
     * @api {post} actions/api/action/view Получить акцию
     * @apiDescription Получить конкретную акцию со списками брендов, категорий и товаров
     * @apiName ActionsView
     * @apiGroup Actions
     *
     * @apiParam {Integer} action_id Индификатор акции
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *   "action_id": 2
     * }
     *
     * @apiSuccess {String} result Статус ответа "OK"
     * @apiSuccess {Object} actions Список акций
     * @apiSuccess {Integer} actions.id Индификатор
     * @apiSuccess {String} actions.title Название
     * @apiSuccess {String} actions.short_description Короткое описание
     * @apiSuccess {String} actions.description HTML описание
     * @apiSuccess {String} actions.start_on Дата начала
     * @apiSuccess {String} actions.end_on Дата окончания
     * @apiSuccess {String} actions.has_products Отображать или нет каталог продукции
     * @apiSuccess {Object} actions.groups Список брендов
     * @apiSuccess {Integer} actions.groups.id Идентификатор
     * @apiSuccess {String} actions.groups.name Название
     * @apiSuccess {Object} actions.categories Список категорий
     * @apiSuccess {Integer} actions.categories.id Идентификатор
     * @apiSuccess {String} actions.categories.name Название
     * @apiSuccess {Object} actions.products Список товаров
     * @apiSuccess {Integer} actions.products.id Идентификатор товара
     * @apiSuccess {Integer} actions.products.group_id Идентификатор бренда
     * @apiSuccess {Integer} actions.products.category_id Идентификатор категории
     * @apiSuccess {Integer} actions.products.unit_id Идентификатор единицы измерения
     * @apiSuccess {String} actions.products.name Название
     * @apiSuccess {String} actions.products.code Номенклатура
     * @apiSuccess {Integer} actions.products.enabled Признак активности
     * @apiSuccess {Number} actions.products.weight Фасовка
     * @apiSuccess {Object} actions.products.group Бренд товара
     * @apiSuccess {Integer} actions.products.group.id Индификаторв бренда
     * @apiSuccess {String} actions.products.group.name Название бренда
     * @apiSuccess {Object} actions.products.category Категория товара
     * @apiSuccess {Integer} actions.products.category.id Индификаторв категории
     * @apiSuccess {String} actions.products.category.name Название категории
     * @apiSuccess {Object} actions.products.unit Единица измерения
     * @apiSuccess {Integer} actions.products.unit.id Индификатов единицы измерения
     * @apiSuccess {String} actions.products.unit.name Название единицы измерения
     * @apiSuccess {String} actions.products.unit.short_name Короткое название единицы измерения
     * @apiSuccess {Integer} actions.products.unit.quantity_divider Делитель количества
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * {
     *   "result": "OK",
     *   "action": {
     *   "id": 2,
     *   "title": "123213",
     *   "short_description": "",
     *   "description": "",
     *   "start_on": "2019-02-28",
     *   "end_on": "2019-02-21",
     *   "has_products": 1,
     *   "groups": [
     *     {
     *       "id": 1,
     *       "name": "Finncolor"
     *     },
     *     {
     *       "id": 3,
     *       "name": "TEKS"
     *     }
     *   ],
     *   "categories": [
     *     {
     *       "id": 11,
     *       "name": "Грунт под антисептики"
     *     }
     *   ],
     *   "products": [
     *     {
     *       "id": 199,
     *       "group_id": 3,
     *       "category_id": 2,
     *       "unit_id": 1,
     *       "name": "Антисептик грунтовочный Биотекс Эко Грунт ПРОФИ бесцв 0,8л",
     *       "code": "700006970",
     *       "enabled": 1,
     *       "weight": 0.8,
     *       "group": {
     *         "id": 3,
     *         "name": "TEKS"
     *       },
     *       "category": {
     *         "id": 11,
     *         "name": "Грунт под антисептики"
     *       },
     *       "unit": {
     *         "id": 1,
     *         "name": "Литр",
     *         "short_name": "л.",
     *         "quantity_divider": 100
     *       }
     *     }
     *   ]
     * }
     */
    public function actionView()
    {
        $model = new ActionViewForm;
        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            $this->logResponse = false;
            return $this->ok(['action' => $model->search()], 'Получение акции');
        }

        return $this->error($model->getFirstErrors(), 'Ошибка при получении акции');
    }

    /**
     * @api {post} actions/api/action/take-part Согласие с акцией
     * @apiDescription Отправить признак участия пользователя в акции
     * @apiName ActionsTakePart
     * @apiGroup Actions
     *
     * @apiParam {Integer} action_id Индификатор акции
     * @apiParam {Integer} profile_id Индификатор пользователя
     *
     * @apiParamExample {post} Пример запроса:
     *
     *   "action_id": 2,
     *   "profile_id": 2
     *
     * @apiSuccess {String} result Статус ответа "OK"
    */
    public function actionTakePart()
    {
        $model = new ActionParticipantForm();
        $model->load(Yii::$app->request->post(), '');
        if ($model->process()) {
            return $this->ok([], 'Соглаcие на участие в акции');
        }
        return $this->error($model->getFirstErrors(), 'Ошибка cоглаcия на участие в акции');
    }
}