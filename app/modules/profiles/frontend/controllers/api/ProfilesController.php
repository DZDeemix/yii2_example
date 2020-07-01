<?php

namespace modules\profiles\frontend\controllers\api;

use modules\profiles\frontend\forms\ProfileAvatarForm;
use ms\loyalty\api\frontend\base\ApiController;
use Yii;

class ProfilesController extends ApiController
{
    /**
     * @api {post} /profiles/api/profiles/load-avatar Загрузка аватарки
     * @apiName LoadAvatar
     * @apiGroup Profiles
     *
     * @apiParam {Number} profile_id Идентификатор участника TM или RD
     * @apiParam {String} image Строка Base-64 c изображением
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *     "profile_id": 941,
     *     "image": "data:image/jpg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k="
     * }
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * HTTP/1.1 200 OK
     * {
     *     "result": "OK",
     *     "profile": {
     *         "id": 2,
     *         "profile_id": 2,
     *         "full_name": "Денис Трофимов",
     *         "first_name": "Денис",
     *         "last_name": "Трофимов",
     *         "middle_name": "Михайлович",
     *         "role": "tm",
     *         "gender": null,
     *         "city_id": null,
     *         "dealer_id": null,
     *         "city_local": null,
     *         "specialty": null,
     *         "avatar_url": "https://oos.f/data/photos/profile_2_avatar_5cb9b6d55ebfa.jpg",
     *         "phone_mobile": "+79299004003",
     *         "email": "7binary@list.ru",
     *         "balance": 0,
     *         "birthday_on": null,
     *         "registered_at": "2019-03-27 15:42:27",
     *         "checked_at": null,
     *         "pers_at": null,
     *         "created_at": "2019-03-26 09:22:29",
     *         "blocked_at": null,
     *         "blocked_reason": null,
     *         "banned_at": null,
     *         "banned_reason": null,
     *         "account": null,
     *         "sales_point_ids": [],
     *         "region_ids": [
     *             1
     *         ],
     *         "district_ids": []
     *     }
     * }
     */
    public function actionLoadAvatar()
    {
        $form = new ProfileAvatarForm();
        $form->load(Yii::$app->request->post(), '');

        if ($form->process()) {
            return $this->ok(['profile' => $form->getProfile()], 'Успешная загрузка аватара');
        }

        return $this->error($form->getFirstErrors(), 'Ошибка при загрузке аватара');
    }
}