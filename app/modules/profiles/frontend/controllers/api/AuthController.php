<?php
namespace modules\profiles\frontend\controllers\api;

use ms\loyalty\api\frontend\base\ApiController;
use Yii;
use modules\profiles\common\models\Profile;

class AuthController extends ApiController
{
    /**
     * @api {post} /profiles/api/auth/get-profile Получить информацию по участнику
     * @apiName GetProfile
     * @apiGroup Profiles
     *
     * @apiParam {Number} profile_id Идентификатор участника
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *   "profile_id": 12
     * }
     *
     * @apiSuccess {Object} profile Данные по участнику
     * @apiSuccess {String} profile.full_name Полное имя участника
     * @apiSuccess {String} profile.first_name Имя участника
     * @apiSuccess {String} profile.last_name Фамилия участника
     * @apiSuccess {String} profile.middle_name Отчество участника
     * @appSuccess {Number} profile.profile_id Идентификатор участника
     * @apiSuccess {String} profile.role Роль участника (rtt=РТТ, dealer=Дилер)
     * @apiSuccess {String} profile.specialty Должность участника (leader=руководитель или manager=продавец)
     * @apiSuccess {String} profile.avatar_url
     * @apiSuccess {String} profile.phone_mobile
     * @apiSuccess {String} profile.email
     * @appSuccess {Number} profile.balance
     * @appSuccess {Number} profile.dealer_id ID Компания участника
     * @appSuccess {Object} profile.dealer Компания участника (РТТ или дилер)
     * @apiSuccess {String} profile.registered_at
     * @apiSuccess {String} profile.created_at
     * @apiSuccess {String} profile.blocked_at
     * @apiSuccess {String} profile.blocked_reason
     * @apiSuccess {String} profile.banned_at
     * @apiSuccess {String} profile.banned_reason
     * @apiSuccess {Object} profile.account Налоговая анкета
     * @apiSuccess {String} profile.account.status
     * @apiSuccess {String} profile.account.status_label
     * @apiSuccess {String} result OK при успешном запросе
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * HTTP/1.1 200 OK
     * {
     *     "result": "OK",
     *     "profile": {
     *         "profile_id": 12,
     *         "full_name": "Иван Иванов",
     *         "first_name": "Иван",
     *         "last_name": "Иванов",
     *         "middle_name": "Иванович",
     *         "role": null,
     *         "specialty": null,
     *         "avatar_url": "https://polaris.msforyou.ru/images/avatar_blank.png?v=3",
     *         "phone_mobile": "+79299004002",
     *         "email": null,
     *         "balance": 0,
     *         "dealer_id": 11,
     *         "dealer": {
     *             "id": 11,
     *             "name": "Планета",
     *             "address": "426006, Удмуртская Респ, Ижевск г, Клубная ул, дом № 37",
     *             "class": "C",
     *             "inn": "",
     *             "type": "rtt"
     *         },
     *         "registered_at": null,
     *         "checked_at": null,
     *         "pers_at": null,
     *         "created_at": "2019-08-05 21:49:17",
     *         "blocked_at": null,
     *         "blocked_reason": null,
     *         "banned_at": null,
     *         "banned_reason": null,
     *         "account": null
     *     }
     * }
     */
    public function actionGetProfile()
    {
        $profile_id = Yii::$app->request->post('profile_id', null);

        if ($profile = Profile::findOne(['id' => $profile_id])) {
            $this->logResponse = false;
            return $this->ok(['profile' => $profile->toArray()], 'Получение данных по участнику');
        }

        return $this->error('Не найден участник по profile_id=' . $profile_id, 'Ошибка получения данных по участнику');
    }

    public function actionProfileEdit()
    {
        $profile_id = Yii::$app->request->post('profile_id', null);
        $profile = Profile::findOne(['id' => $profile_id]);

        if ($profile == null) {
            return $this->error('Участник не найден', 'Ошибка обновления профиля участника');
        }

        $profile->load(Yii::$app->request->post(), '');

        if ($profile->save()) {
            $profile->refresh();
            return $this->ok(['profile' => $profile], 'Успешное обновление профиля участников');
        }

        return $this->error($profile->getFirstErrors(), 'Ошибка обновления профиля участника');
    }

    public function actionConfirmPers()
    {
        $profile_id = Yii::$app->request->post('profile_id', null);
        $profile = Profile::findOne(['id' => $profile_id]);

        if ($profile == null) {
            return $this->error('Участник не найден', 'Ошибка подтверждения обработки персональных данных');
        }
        if ($profile->pers_at == null) {
            $profile->updatePersAt();
            $profile->refresh();
        }

        return $this->ok(['profile' => $profile], 'Согласие на обработку персональных данных');
    }
}