<?php

namespace modules\sales\frontend\models;

use modules\profiles\common\models\LegalPerson;
use modules\profiles\common\models\Profile;
use modules\sales\common\models\Category;
use modules\sales\common\models\Product;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleHistory;
use modules\sales\common\models\SalePosition;
use modules\sales\common\sales\statuses\Statuses;

class ApiSale extends Sale
{
    public $profile_id;
    public $documents;
    public $projectPhotos;
    public $positions;

    /** @var Profile */
    protected $profile = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            //['sold_on_local', 'required', 'skipOnEmpty' => false],
            ['profile_id', 'checkProfile', 'skipOnEmpty' => false],
            ['documents', 'checkDocuments', 'skipOnEmpty' => true],
            ['positions', 'checkPositions', 'skipOnEmpty' => false],
            
        ]);
    }

    public function checkProfile()
    {
        if ($profile = Profile::findOne($this->profile_id)) {
            $this->profile = $profile;
        }
        else {
            $this->addError('profile_id', 'Не найден участник');
        }
    }

    public function checkDocuments()
    {
        $this->uploadFiles($this->documents, Sale::DATA_DIR, Sale::class);
        $this->uploadFiles($this->projectPhotos, Sale::DATA_DIR, ApiSale::class);
    }

    public function checkPositions()
    {
        if (empty($this->positions)) {
            return $this->addError('positions', 'Не указаны позиции');
        }

        foreach ($this->positions as $position) {
            if (empty($position['product_id']) || null == Product::findOne(intval($position['product_id']))) {
                $this->addError('positions', 'У позиции не задана продукция');
                break;
            }
            if (empty($position['quantity'])) {
                $this->addError('positions', 'У позиции не задано количество');
                break;
            }
        }
    }

    public function apiSave()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->status = Statuses::ADMIN_REVIEW;

        if ($this->isNewRecord) {
            $this->recipient_id = $this->profile->id;
            $this->save(false);
            $this->refresh();
            $this->saveFiles();
        }
        else {
            $this->save(false);
            $this->saveFiles();
            $this->deleteMissingFiles();
            $this->deletePositions();
        }

        $this->savePositions();
        $this->updateBonuses();
        $this->saveHistory();

        return true;
    }

    private function deletePositions()
    {
        $positions = $this->getPositions()->all();
        if (!empty($positions)) {
            foreach ($positions as $position) {
                $position->delete();
            }
        }
    }

    private function savePositions()
    {
        foreach ($this->positions as $position) {
            $productId = (int) $position['product_id'];
            $quantity = (int) $position['quantity'];
            /** @var Product $product */
            $product = Product::findOne($productId);

            $salePosition = new SalePosition();
            $salePosition->sale_id = $this->id;
            $salePosition->product_id = $product->id;
            $salePosition->quantity = $quantity;
            $salePosition->bonuses_primary = $product->bonuses_formula;
            $salePosition->save(false);
        }
    }

    private function saveHistory()
    {
        $h = new SaleHistory();
        $h->sale_id = $this->id;
        $h->status_old = empty($this->status) ? null : $this->status;
        $h->status_new = Statuses::ADMIN_REVIEW;
        $h->note = $this->isNewRecord ? 'Продажа внесена участником' : 'Продажа обновлена участником';
        $h->comment = \Yii::$app->request->post('comment') ?? null;
        $h->admin_id = null;
        $h->type = $this->isNewRecord ? SaleHistory::TYPE_CREATE : SaleHistory::TYPE_UPDATE;
        $h->save(false);
    }
}
