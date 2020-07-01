<?php

namespace modules\actions\common\models;

use common\utils\OLAP;
use marketingsolutions\datetime\DateTimeBehavior;
use modules\actions\common\managers\StatusManager;
use modules\actions\common\managers\Validator;
use modules\actions\common\types\PlanCompletePersonalActionByAmountType;
use modules\actions\common\types\PlanCompletePersonalActionByPriceType;
use modules\profiles\common\managers\RoleManager;
use modules\profiles\common\models\City;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Leader;
use modules\profiles\common\models\Profile;
use modules\profiles\common\models\Region;
use modules\sales\common\models\Category;
use modules\sales\common\models\Group;
use modules\sales\common\models\Product;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleAction;
use ms\loyalty\mobile\common\models\MobileNotification;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yz\admin\mailer\common\lists\ManualMailList;
use yz\admin\mailer\common\models\Mail;
use yz\admin\models\AuthItem;
use yz\admin\models\User;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_actions".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $type_id
 * @property string $start_on
 * @property string $code
 * @property boolean $show_price
 * @property string $end_on
 * @property string $title
 * @property string $personal_plan_formula
 * @property string $short_description
 * @property string $description
 * @property string $bonuses_formula
 * @property integer $bonuses_amount
 * @property integer $plan_amount
 * @property string $pay_type
 * @property integer $pay_threshold
 * @property integer $limit_bonuses
 * @property integer $limit_qty
 * @property integer $has_products
 * @property string $role
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $confirm_period
 * @property boolean $email_is_send
 *
 * @property Validator $validator
 * @property StatusManager $statusManager
 * @property ActionGroup[] $actionGroups
 * @property Group[] $groups
 * @property ActionCategory[] $actionCategories
 * @property Category[] $categories
 * @property ActionProduct[] $actionProducts
 * @property Product[] $products
 * @property ActionRegion[] $actionRegions
 * @property Region[] $regions
 * @property ActionCity[] $actionCities
 * @property City[] $cities
 * @property ActionDealer[] $actionDealers
 * @property Dealer[] $dealers
 * @property ActionProfile[] $actionProfiles
 * @property ActionParticipant[] $actionParticipants
 * @property Profile[] $profiles
 * @property ActionAdmin[] $actionAdmins
 * @property AuthItem[] $admins
 * @property ActionType $type
 * @property Sale[] $sales
 * @property User $admin
 * @property Leader $leader
 */
class Action extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    const STATUS_NEW = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_FINISHED = 'finished';
    /***/
    const STATUS_OLAP_CHECKED = 'checked';
    const STATUS_PAID= 'paid';

    /**
     * Type of bonus payments at the end of the action
     */
    const PAY_TYPE_COMPLETED = 'completed';

    /**
     * Type of bonus payments after reaching the threshold
     */
    const PAY_TYPE_THRESHOLD = 'threshold';

    /**
     * Type of bonus payments after reaching the threshold
     */
    const PAY_TYPE_OLAP_CHECKED = 'olap_checked';

    /**
     * @var Validator
     */
    private $_validator;

    /**
     * @var StatusManager
     */
    private $_statusManager;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Акция';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Акции';
    }

    /**
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_ACTIVE => 'Активна',
            self::STATUS_FINISHED => 'Завершена',
            self::STATUS_OLAP_CHECKED => 'Сверка с ОЛАП',
            self::STATUS_PAID=> 'Начислены бонусы'
        ];
    }

    /**
     * @return array
     */
    public static function getPayTypesList()
    {
        return [
            self::PAY_TYPE_OLAP_CHECKED => 'После сверки с ОЛАП',
            //self::PAY_TYPE_COMPLETED => 'По окончанию акции',
            //self::PAY_TYPE_THRESHOLD => 'По достижению количества баллов',

        ];
    }

    public static function getList()
    {
        return self::find()
            ->select(['title', 'id'])
            ->orderBy(['id' => SORT_DESC])
            ->indexBy('id')
            ->column();
    }

    /**
     * @return ActionQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(ActionQuery::class, [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['admin_id', 'integer'],

            ['type_id', 'required'],
            ['type_id', 'integer'],

          //  ['start_on', 'required'],
            ['start_on', 'safe'],

         //   ['end_on', 'required'],
            ['end_on', 'safe'],

            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            ['short_description', 'string', 'max' => 255],

            ['description', 'string'],
            ['personal_plan_formula', 'string'],
            ['code', 'string'],

          //  ['bonuses_formula', 'required'],
            ['bonuses_formula', 'string', 'max' => 255],

            ['bonuses_amount', 'integer'],

            ['plan_amount', 'integer'],

          //  ['pay_type', 'required'],
            ['pay_type', 'string', 'max' => 16],
            ['pay_type', 'in', 'range' => array_keys(self::getPayTypesList())],

            ['pay_threshold', 'integer'],

            ['limit_bonuses', 'integer'],

            ['limit_qty', 'integer'],

            ['has_products', 'boolean'],
            ['has_products', 'default', 'value' => true],

            ['show_price', 'boolean'],
            ['show_price', 'default', 'value' => false],

            ['email_is_send', 'boolean'],
            ['email_is_send', 'default', 'value' => false],

           // ['role', 'required'],
            ['role', 'in', 'range' => array_keys(Profile::getRoleOptions())],

            ['status', 'string', 'max' => 32],
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'N',
            'admin_id' => 'Администратор',
            'code' => 'Код акции',
            'start_on' => 'Дата начала',
            'end_on' => 'Дата завершения',
            'type_id' => 'Тип акции',
            'title' => 'Название',
            'short_description' => 'Краткое описание',
            'description' => 'Описание',
            'bonuses_formula' => 'Бонусная формула',
            'bonuses_amount' => 'Бонусы по акции',
            'personal_plan_formula' => 'Формула расчета индивидуального плана',
            'plan_amount' => 'План (в штуках)',
            'pay_type' => 'Условие начисление баллов',
            'pay_threshold' => 'Порог начисления баллов',
            'limit_bonuses' => 'Ограничение по начисленным баллам для участника',
            'limit_qty' => 'Ограничение по количеству позиций для участника',
            'has_products' => 'Отображать выбор продукции',
            'role' => 'Роль участников',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'confirm_period' => 'Период подтверждения участником (дней)',
            'show_price' => 'Отображать цены в интерфейсе',
            'email_is_send' => 'Приглашения отправлены',

        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],

        ];
    }

    public function beforeDelete()
    {
        ActionGroup::deleteAll(['action_id' => $this->id]);
        ActionCategory::deleteAll(['action_id' => $this->id]);
        ActionProduct::deleteAll(['action_id' => $this->id]);
        ActionDealer::deleteAll(['action_id' => $this->id]);
        ActionProfile::deleteAll(['action_id' => $this->id]);
        ActionRegion::deleteAll(['action_id' => $this->id]);
        ActionCity::deleteAll(['action_id' => $this->id]);
        ActionAdmin::deleteAll(['action_id' => $this->id]);
        SaleAction::deleteAll(['action_id' => $this->id]);
        ActionParticipant::deleteAll(['action_id' => $this->id]);

        return parent::beforeDelete();
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'short_description',
            'description',
            'planForAction',
            'start_on',
            'end_on',
            'has_products',
            'bonuses_formula',
            'is_actual',
            'is_confirmed',
            'show_price',
            'sale_ids',
        ];
    }

    /**
     * @return Validator
     */
    public function getValidator()
    {
        if (null === $this->_validator) {
            $this->_validator = new Validator($this);
        }

        return $this->_validator;
    }

    public function getIs_confirmed($profile_id = false)
    {
        if ($profile_id) {
            $profieId = $profile_id;
        } else {
            $profieId = Yii::$app->request->post('profile_id');
        }
        return ($this->getActionParticipants()->filterWhere(["profile_id" => $profieId])->count() > 0) ? true : false;
    }

    public function getSale_ids()
    {
        $profieId = Yii::$app->request->post('profile_id');
        return $this->getSales()->where(["recipient_id" => $profieId])->select('id')->orderBy(["id" => SORT_DESC])->column();
    }

    public function getIs_Actual(string $now = 'now')
    {
        $now = date_create_immutable($now)->setTime(0, 0, 0)->format('Y-m-d');

        if ($this->start_on <= $now && $this->end_on >= $now && $this->status == self::STATUS_ACTIVE) {
            return true;
        }

        return false;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionParticipants()
    {
        return $this->hasMany(ActionParticipant::class, ['action_id' => 'id']);
    }

    /**
     * @return integer
     */
    public function getActionParticipantsCount()
    {
        return $this->hasMany(ActionParticipant::class, ['action_id' => 'id'])->count();
    }

    public function getPlanForAction()
    {
        if ($this->plan_amount) {
            return $this->plan_amount;
        }
        if ($this->type_id == 3) {
            $profieId = Yii::$app->request->post('profile_id');
            $individualPlan = ActionProfile::findOne(['profile_id' => $profieId, 'action_id' => $this->id]);
            if ($individualPlan) {
                $pos = strpos($this->personal_plan_formula, 'plan');

                if ($pos === false && $this->personal_plan_formula) {
                    return $this->personal_plan_formula;
                } else {
                    $coef = str_replace('plan', '', $this->personal_plan_formula);
                    $coef = str_replace('*', '', $coef);
                    $coef = trim($coef);
                    return $individualPlan->last_year_plan * (int)$coef;
                }
            }
        }
        return [];
    }

    /**
     * @return StatusManager
     */
    public function getStatusManager()
    {
        if (null === $this->_statusManager) {
            $this->_statusManager = new StatusManager($this);
        }

        return $this->_statusManager;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionGroups()
    {
        return $this->hasMany(ActionGroup::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])
            ->via('actionGroups');
    }

    /**
     * @return array
     */
    public function getActionGroupIds()
    {
        return $this->getActionGroups()->select('group_id')->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionCategories()
    {
        return $this->hasMany(ActionCategory::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->via('actionCategories');
    }

    /**
     * @return array
     */
    public function getActionCategoryIds()
    {
        return $this->getActionCategories()->select('category_id')->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionProducts()
    {
        return $this->hasMany(ActionProduct::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->via('actionProducts');
    }

    /**
     * @return array
     */
    public function getActionProductIds()
    {
        return $this->getActionProducts()->select('product_id')->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionRegions()
    {
        return $this->hasMany(ActionRegion::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasMany(Region::class, ['id' => 'region_id'])
            ->via('actionRegions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionCities()
    {
        return $this->hasMany(ActionCity::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::class, ['id' => 'city_id'])
            ->via('actionCities');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionDealers()
    {
        return $this->hasMany(ActionDealer::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealers()
    {
        return $this->hasMany(Dealer::class, ['id' => 'dealer_id'])
            ->via('actionDealers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionProfiles()
    {
        return $this->hasMany(ActionProfile::class, ['action_id' => 'id']);
    }

    /**
     * @param $profile_id
     * @return array
     */
    public function getActionPlan($profile_id)
    {

        $last_year_plan = $this->getActionProfiles()->select("last_year_plan")->where(["profile_id" => $profile_id])->column();
        $last_year_price_plan = $this->getActionProfiles()->select("last_year_price_plan")->where(["profile_id" => $profile_id])->column();
        $total_plan = $this->plan_amount;

        if (count($last_year_plan) > 0 && $this->type->className == PlanCompletePersonalActionByAmountType::class) {
            $plan = (int)$last_year_plan[0];
            $unit = "шт";
        } elseif (count($last_year_price_plan) > 0 && $this->type->className == PlanCompletePersonalActionByPriceType::class) {
            $plan = (int)$last_year_price_plan[0];
            $unit = "руб";
        } else {
            $plan = (int)$total_plan;
            $unit = "шт";
        }

        return ["plan" => $plan, "unit" => $unit];
    }


    /**
     * @param $positions
     * @param $profile_id
     * @return array
     */

    public function getBonuses($positions, $profile_id)
    {
        $bonuses = 0;
        $plan = $this->getActionPlan($profile_id);
        $quantity_total = 0;
        $price_total = 0;

        foreach ($positions as $position) {
            $productId = (int)ArrayHelper::getValue($position, 'product_id');
            $quantity = (int)ArrayHelper::getValue($position, 'quantity');
            $price = Product::findOne($productId)->price;
            $quantity_total += $quantity;
            $product_bonus = $this->getProductBonus($productId);
            $bonuses += $quantity * $product_bonus;
            $price_total += ($price * $quantity);
        }
        if ($quantity_total < $plan["plan"] && ($plan["unit"] == "шт")) {
            $bonuses = 0;
        }
        if ($price_total < $plan["plan"] && ($plan["unit"] == "руб")) {
            $bonuses = 0;
        }
        $bonus_info = [
            "total" => $bonuses,
            "plan" => ($plan["unit"] == "шт") ? $plan["plan"] : number_format($plan["plan"], 2, ".", " "),
            "fact" => ($plan["unit"] == "шт") ? $quantity_total : number_format($price_total, 2, ".", " "),
            "unit" => $plan["unit"]
        ];
        return $bonus_info;
    }

    /**
     * @param $product_id
     * @return integer
     */
    public function getProductBonus($product_id)
    {
        $product_bonus_total = $this->bonuses_formula;
        foreach ($this->actionProducts as $actionProduct) {
            if ($actionProduct->product_id == $product_id) {
                $product_bonus = ($actionProduct->bonus)?$actionProduct->bonus:$product_bonus_total;
            }
        }

        return $product_bonus;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::class, ['id' => 'profile_id'])
            ->via('actionProfiles');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionAdmins()
    {
        return $this->hasMany(ActionAdmin::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmins()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'auth_item_name'])
            ->via('actionAdmins');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ActionType::class, ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::class, ['action_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(Leader::class, ['identity_id' => 'id'])
            ->via('admin');
    }

    public static function getActionName($action_id = null)
    {
        if (!$action_id) {
            return "";
        }

        $action = self::findOne($action_id);

        if ($action) {
            return $action->title;
        }

        return "";
    }

    public static function getIndividualAction()
    {
        $arrReturn = [];

        $actions = self::find()->where(['type_id' => 3])->asArray()->all();
        foreach ($actions as $action) {
            $arrReturn[$action['id']] = $action['title'];
        }
        return $arrReturn;
    }

    public function getAllActionProfiles()
    {

        $profiles = $this->hasMany(Profile::class, ['id' => 'profile_id'])->viaTable(ActionProfile::tableName(),
            ['action_id' => 'id'])->all();
        if (count($profiles) == 0) {
            $profiles = Profile::find()->where(['role' => $this->role])->all();
        }
        return $profiles;

    }

    /**
     * Отправка E-mail приглашений по акции
     * @param $action_id
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public static function sendEmail($action_id)
    {
        $model = self::findOne(['id' => $action_id]);
        if (!$model) {
            return false;
        }

        $profile = $model->getAllActionProfiles();
        $arrUser = [];
        foreach ($profile as $user) {
            $arrUser[] = trim($user->email);
            MobileNotification::createPush('Приглашаем Вас принять участие в призовой акции!', $model->title,
                $user->id);
        }
        $mails_arr = array_chunk($arrUser, 1000);
        foreach ($mails_arr as $mails_group) {
            $theme = $model->title;
            $rules = "";
            $dateFrom = $model->start_on;
            $dateTo = $model->end_on;
            $action_text = $model->description;
            if ($model->description != "") {
                $action_text = "<div class=\"row\">
                    <div class=\"col-md-4\">Описание акции:</div>
                    <div class=\"col-md-8\">" . $action_text . "</div>
            </div>";
            }
            $transaction = \Yii::$app->db->beginTransaction();
            $send = new Mail();
            $send->status = Mail::STATUS_WAITING;
            $send->receivers_provider = ManualMailList::className();
            $send->receivers_provider_data = json_encode(['to' => implode(";", $mails_group)]);
            $send->subject = $model->title;
            $send->body_html = '<div class="row">
                <div class="col-md-12"><h3 align="center">Приглашаем Вас принять участие в призовой акции!</h3></div>
            </div>
            <div class="row">
                <div class="col-md-4">Тема акции:</div>
                <div class="col-md-8">' . $theme . '</div>
            </div>
            <div class="row">
                <div class="col-md-4">Правила акции:</div>
                <div class="col-md-8">' . $rules . '</div>
            </div>' . $action_text . '<div class="row">
                <div class="col-md-4">Период проведения:</div>
                <div class="col-md-8"> C ' . date("d.m.Y", strtotime($dateFrom)) . ' по ' . date("d.m.Y",
                    strtotime($dateTo)) . '</div>
            </div>
            <div class="row">
                
            </div>';
            $send->created_at = date("Y-m-d H:i:s");
            $send->save(false);
            $model->email_is_send = 1;
            $model->update(false);
            $send->updateAttributes(['status' => Mail::STATUS_WAITING]);
            $transaction->commit();


        }
        return true;
    }

    public function getIsOlapExist()
    {

        return false;
    }
}
