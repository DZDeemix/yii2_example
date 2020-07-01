<?php

namespace modules\sales\common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yz\interfaces\ModelInfoInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use ms\files\attachments\common\traits\FileAttachmentsTrait;
use modules\actions\common\models\Action;
use modules\profiles\common\models\Profile;

/**
 * This is the model class for table "yz_sales_reports".
 *
 * @property integer $id
 * @property string $report
 * @property integer $profile_id
 * @property integer $action_id
 * @property string $created_at
 * @property string $updated_at
 */
class SaleReport extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    use FileAttachmentsTrait;

    const DATA_DIR = 'sale-reports';

    public $documents;
    public $report_local;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales_reports}}';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Отчет о продажах';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Отчеты о продажах';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['report', 'string', 'max' => 255],
            ['report_local', 'file', 'extensions' => ['xls', 'xlsx', 'pdf'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Максимальный размер загружаемого файла - 10 МБ'],
            ['documents', 'checkDocuments', 'skipOnEmpty' => false],
            ['profile_id', 'integer'],
            ['profile_id', 'required'],
            ['action_id', 'integer'],
            ['action_id', 'required'],
            ['created_at', 'safe'],
            ['updated_at', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'report' => 'Отчет',
            'profile_id' => 'Участник',
            'action_id' => 'Акция',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    public function fields()
    {
        $fields = [
            'id',
            'report',
            'profile_id',
            'action_id',
            'created_at',
        ];

        return $fields;
    }

    public function checkDocuments()
    {
        $this->uploadFiles($this->documents, SaleReport::DATA_DIR, SaleReport::class);
        if (empty($this->uploadedFiles)) {
            $this->addError('documents', 'Необходимо прикрепить хотя бы один документ (изображение или PDF-файл)');
        }
    }

    public function apiSave()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->isNewRecord) {
            $this->save(false);
            $this->refresh();
            $this->saveFiles();
        }
        else {
            $this->save(false);
            $this->saveFiles();
            $this->deleteMissingFiles();
        }

        return true;
    }


    public function getReport_url()
    {
        return empty($this->report) ? null : $_ENV['FRONTEND_WEB'] ?? null . '/data/sale-reports/' . $this->report;
    }

    public function getAction()
    {
        return $this->hasOne(Action::class, ['id' => 'action_id']);
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['id' => 'profile_id']);
    }

}
