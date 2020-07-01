<?php


namespace modules\burnpoints\common\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yz\interfaces\ModelInfoInterface;

/**
 * Class BurnPointSettings
 *
 * @property int $id
 * @property bool $warning_sms
 * @property bool $warning_email
 * @property bool $warning_push
 * @property int $days_to_burn
 * @property int $days_warning
 * @property int $count_warnings
 * @property string $sms_warning
 * @property string $sms_nullify
 * @property string $email_warning_subject
 * @property string $email_warning_template
 * @property string $email_nullify_subject
 * @property string $email_nullify_template
 * @property string $push_warning
 * @property string $push_nullify
 * @property string $created_at
 * @property string $updated_at
 *
 * @package modules\burnpoints\common\models
 */
class BurnPointSettings extends ActiveRecord implements ModelInfoInterface
{
    /**
     * @var self|null
     */
    private static $_instance = null;

    public static function tableName()
    {
        return '{{%burn_points_settings}}';
    }

    /**
     * @inheritDoc
     */
    public static function modelTitle()
    {
        return 'Настройки сгорания баллов';
    }

    /**
     * @inheritDoc
     */
    public static function modelTitlePlural()
    {
        return 'Настройки сгорания баллов';
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warning_sms', 'warning_email', 'warning_push'], 'boolean'],
            [['days_to_burn', 'days_warning', 'count_warnings'], 'integer'],
            [['sms_warning', 'sms_nullify', 'email_warning_subject', 'email_nullify_subject', 'push_warning', 'push_nullify'], 'string', 'max' => 500],
            [['email_warning_template', 'email_nullify_template'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['days_to_burn', 'days_warning', 'count_warnings'], 'required'],
            [['warning_sms', 'warning_email', 'warning_push'], 'default', 'value' => false],
            ['days_to_burn', 'default', 'value' => 365],
            ['days_warning', 'default', 'value' => 10],
            ['count_warnings', 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'warning_sms' => 'Уведомление по СМС',
            'warning_email' => 'Уведомление по E-mail',
            'warning_push' => 'PUSH уведомление',
            'days_to_burn' => 'Срок действия начисленных баллов',
            'days_warning' => 'Количество дней для предупреждения до обнуления',
            'count_warnings' => 'Количество предупреждений',
            'sms_warning' => 'СМС предупреждение',
            'sms_nullify' => 'СМС при обнулении',
            'email_warning_subject' => 'Заголовок e-mail предупреждения',
            'email_warning_template' => 'Шаблон e-mail предупреждения',
            'email_nullify_subject' => 'Заголовок e-mail при обнулении',
            'email_nullify_template' => 'Шаблон e-mail при обнулении',
            'push_warning' => 'PUSH предупреждение',
            'push_nullify' => 'PUSH при обеулении',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления'
        ];
    }

    /**
     * @return static
     */
    public static function get(): self
    {
        if (empty(self::$_instance)) {
            $model = self::find()->limit(1)->one();

            if ($model === null) {
                $model = new self();

                $model->days_warning = 10;

                $model->sms_warning = 'Ваши баллы на счете обнулятся через {days} дней';
                $model->email_warning_subject = 'Предупреждение об обнулении баллов';
                $model->email_warning_template = '<p>Уважаемый участник!</p><p>Ваши баллы на счете в количестве {amount} обнулятся через {days}.</p>';
                $model->push_warning = 'Ваши баллы на счете обнулятся через {days} дней';

                $model->sms_nullify = 'Ваши баллы были обнулены';
                $model->email_nullify_subject = 'Обнуление баллов';
                $model->email_nullify_template = '<p>Уважаемый участник!</p><p>Ваши баллы в количестве {amount} были обнулены.';
                $model->push_nullify = 'Ваши баллы были обнулены';

                $model->save(false);
                $model->refresh();
            }

            self::$_instance = $model;
        }

        return self::$_instance;
    }
}