<?php

namespace modules\sales\backend\controllers;

use modules\sales\common\models\Product;
use modules\sales\common\models\Category;
use yii\web\Controller;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\import\BatchImportAction;
use yz\admin\import\ImportForm;
use yz\admin\import\InterruptImportException;
use yz\admin\traits\CheckAccessTrait;

/**
 * Class ImportProductsController
 */
class ImportProductsController extends Controller implements AccessControlInterface
{
    use CheckAccessTrait;

    const FIELD_MODEL = 'Название';
    const FIELD_CATEGORY = 'Категория';

    public function actions()
    {
        return [
            'index' => [
                'class' => BatchImportAction::class,
                'extraView' => '@modules/sales/backend/views/import-products/index.php',
                'importConfig' => [
                    'availableFields' => [
                        self::FIELD_MODEL => 'Название',
                        self::FIELD_CATEGORY => 'Категория',
                    ],
                    'rowImport' => [$this, 'rowImport'],
                    'skipFirstLine' => true,
                ]
            ]
        ];
    }

    public function rowImport(ImportForm $form, array $row)
    {
        if (empty($row[self::FIELD_MODEL])) {
            return;
        }

        $row = array_map(function($value) {
            return preg_replace('/[\s]{2,}|[\r\n]/', ' ', trim($value));
        }, $row);

        $product = $this->importProfile($row);
    }

    /**
     * @param $row
     * @return Product|null|static
     * @throws InterruptImportException
     */
    private function importProfile($row)
    {
        $product = Product::findOne(['name' => $row[self::FIELD_MODEL]]);
        if(empty($product)) {
            $product = new Product();
            $product->name = $row[self::FIELD_MODEL];

            /*$category = Category::findOne(['name' => $row[self::FIELD_CATEGORY]]);
            if(empty($category)) {
                $category = new Category();
                $category->name = $row[self::FIELD_CATEGORY];
                $category->save();
            }
            $product->category_id = $category->id;*/
            $product->save(false);
        }

        return $product;
    }
}
