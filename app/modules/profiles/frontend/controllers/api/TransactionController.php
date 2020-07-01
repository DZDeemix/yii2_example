<?php

namespace modules\profiles\frontend\controllers\api;

use modules\profiles\common\models\Profile;
use Yii;
use ms\loyalty\api\frontend\base\ApiController;
use marketingsolutions\finance\models\Purse;
use modules\profiles\frontend\models\ApiTransactions;

/**
 * Class TransactionController
 */
class TransactionController extends ApiController
{
    /**
     * @api {post} /profiles/api/transaction/list Транзакции участника
     * @apiName TransactionList
     * @apiGroup Profiles
     *
     * @apiParam {Number} profile_id Идентификатор участника
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *   "profile_id": 12
     * }
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * HTTP/1.1 200 OK
     * {
     *     "result": "OK",
     *     "transactions": [
     *         {
     *             "id": 15,
     *             "amount": 3000,
     *             "balance_after": 0,
     *             "balance_before": 3000,
     *             "title": "Откат баллов за бонус #100 от 19.07.2019",
     *             "comment": "",
     *             "type": "out",
     *             "created_at": "06.08.2019 22:01:27"
     *         },
     *         {
     *             "id": 11,
     *             "amount": 3000,
     *             "balance_after": 3000,
     *             "balance_before": 0,
     *             "title": "Зачисление баллов за бонус #100 от 19.07.2019",
     *             "comment": "",
     *             "type": "in",
     *             "created_at": "05.08.2019 21:49:17"
     *         }
     *     ]
     * }
     */
    public function actionList()
    {
        $profile_id = Yii::$app->request->post('profile_id', null);

        /** @var Profile $profile */
        $profile = Profile::findOne($profile_id);

        if(null === $profile) {
            return $this->error("Не найден участник с ID $profile_id");
        }

        $purses = $profile->getPurses();
    
        if(null === $purses) {
            return $this->error("Не найдено ни одного кошелёка участника с ID $profile_id");
        }
        
        $pursesID = [];
        foreach ($purses as $purse) {
            $pursesID[] = $purse['id'];
        }

        /** @var ApiTransactions[] $transactions */
        $transactions = ApiTransactions::find()
            ->where(['purse_id' => $pursesID])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->ok(['transactions' => $transactions], 'Получение истории транзакций участника');
    }
}
