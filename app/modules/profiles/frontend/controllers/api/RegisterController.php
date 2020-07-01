<?php

namespace modules\profiles\frontend\controllers\api;

use libphonenumber\PhoneNumberFormat;
use marketingsolutions\phonenumbers\PhoneNumber;
use modules\profiles\common\models\Profile;
use modules\profiles\frontend\forms\AutocompleteCompanyForm;
use modules\profiles\frontend\forms\RegisterExistingByEmailForm;
use modules\profiles\frontend\forms\RegisterExistingByPhoneForm;
use modules\profiles\frontend\forms\RegisterFreeByEmailForm;
use modules\profiles\frontend\forms\RegisterFreeByPhoneForm;
use modules\profiles\frontend\forms\RegisterUnregisteredByPhoneForm;
use ms\loyalty\api\common\models\ApiSettings;
use ms\loyalty\api\frontend\base\ApiController;
use ms\loyalty\pages\common\models\Page;
use Yii;

class RegisterController extends ApiController
{
    public function actionInfo()
    {
        $phone = Yii::$app->request->post('phone');
        $profile = null;

        if (!empty($phone) && PhoneNumber::validate($phone, 'RU')) {
            $phone = PhoneNumber::format($phone, PhoneNumberFormat::E164, 'RU');
            $profile = Profile::findOne(['phone_mobile' => $phone]);
        }

        $pageRules = Page::findOne(['url' => 'rules']);
        $pagePers = Page::findOne(['url' => 'pers']);

        return $this->ok(compact('pageRules', 'pagePers', 'profile'), 'Получение данных для регистрации');
    }

    /**
     * @api {post} /profiles/api/register/autocomplete-company Поиск компании по названию
     * @apiName AutocompleteCompany
     * @apiGroup Register
     *
     * @apiParam {String} name Название компании
     * @apiParam {Number} [city_id] Идентификатор города, опционально
     * @apiParam {Number} [type] Тип компании: rtt или dealer
     * @apiParam {Number} [limit] Лимит записей, опционально. По умолчанию 20
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *   "name": "Ромашка"
     * }
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * HTTP/1.1 200 OK
     * {
     *   "result": "OK",
     *   "dealers": [
     *     {
     *       "id": "7",
     *       "name": "ООО Ромашка",
     *       "type": "rtt",
     *       "address": "Москва, Ленинградское шоссе, д 52",
     *       "class": "A",
     *       "inn": "123456123456"
     *     },
     *     {
     *       "id": "8",
     *       "name": "Ромашка другая",
     *       "type": "dealer",
     *       "address": "Москва, Беломорская, д 18",
     *       "class": "B",
     *       "inn": "112233445566"
     *     }
     *   ]
     * }
     */
    public function actionAutocompleteCompany()
    {
        $form = new AutocompleteCompanyForm();
        $form->load(Yii::$app->request->post(), '');
        $this->logResponse = false;

        return $this->ok(['dealers' => $form->findAutocomplete()], 'Подсказки компаний');
    }

    /**
     * @api {post} /profiles/api/register Регистрация участника
     * @apiName Index
     * @apiGroup Register
     *
     * @apiParam {String} token Токен регистрации
     * @apiParam {String} phone Номер телефона участника
     * @apiParam {String} [email] E-mail адрес участника
     * @apiParam {String} last_name Фамилия участника
     * @apiParam {String} first_name Имя участника
     * @apiParam {String} [middle_name] Отчество телефона
     * @apiParam {Number} dealer_id Идентификатор компании
     * @apiParam {String} specialty Должность участника. Для РТТ участников: rtt_leader (Руководитель в точке продаж) или rtt_manager (Продавец в точке продаж). Для участников Дилера: dealer_leader (Руководитель отдела продаж) или dealer_manager (Менеджер отдела продаж)
     * @apiParam {String} password Пароль (строго от 6 символов)
     * @apiParam {String} passwordConfirm Подтверждение пароля (строго от 6 символов)
     * @apiParam {Boolean} checkedRules Согласие с правилами акции (обязательно согласие)
     * @apiParam {Boolean} checkedPers Согласие на обработку персональных данных (обязательно согласие)
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *   "token": "abcdefg12345",
     *   "phone": "+79299004008",
     *   "last_name": "Волков",
     *   "first_name": "Сергей",
     *   "middle_name": "Александрович",
     *   "email": "7binary@list.ru",
     *   "dealer_id": 7,
     *   "specialty": "dealer_leader",
     *   "password": "123123",
     *   "passwordConfirm": "123123",
     *   "checkedRules": true,
     *   "checkedPers": true
     * }
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * HTTP/1.1 200 OK
     * {
     *   "result": "OK",
     *   "profile_id": 10,
     *   "login": "+79299004008",
     *   "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9maWxlX2lkIjoxMH0.NDZlNDRhY2U3MGY4N2FlMDhiZGIxZGE2MDg5NTY1NTgyYmM4NjQyODdkM2ExMTA4ZDdmMDEzZjE4MjA4YTFmNA",
     *   "logged_at": "2019-08-05 14:23:09",
     *   "logged_ip": "127.0.0.1"
     * }
     */
    public function actionIndex()
    {
        $model = new RegisterFreeByEmailForm();
        $model->load(Yii::$app->request->post(), '');

        if ($model->process()) {
            return $this->ok($model->getProfile()->generateJwtToken()->toArray(), 'Успешная регистрация участника');
        }

        return $this->error($model->getFirstErrors(), 'Ошибка регистрации участника');
    }
}
