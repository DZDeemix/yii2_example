<?php

namespace modules\sales\frontend\controllers\api;

use modules\sales\common\models\Product;
use modules\sales\common\models\Category;
use Yii;
use ms\loyalty\api\frontend\base\ApiController;

/**
 * Class ProductController
 */
class ProductController extends ApiController
{
    public function actionListProducts()
    {
        $role = Yii::$app->request->post('role', null);
        $products = Product::find()
            ->where(['enabled' => true])
            ->orderBy(['name' => SORT_ASC]);
        if ($role === 'designer') {
            $products = $products->andWhere(['role' => $role])->all();
        } else {
            $products = $products->andWhere(['<>', 'role', 'designer'])
                ->orWhere(['role' => null])->all();
        }
        
        

        return $this->ok(['products' => $products], 'Получение списка продуктов');
    }

    public function actionView()
    {
        $product_id = Yii::$app->request->post('product_id', null);

        $product = Product::findOne($product_id);

        if (null === $product) {
            return $this->error("Не найден товар с ID $product_id");
        }

        return $this->ok(['product' => $product], 'Получение конкретного товара');
    }

    /**
     * @api {post} /sales/api/product/get-products-from-category Получение списка товаров из категории
     * @apiName GetProductsFromCategory
     * @apiGroup Sales
     *
     * @apiParam {Integer} category_id Идентификатор категории товаров
     *
     * @apiParamExample {json} Пример запроса:
     * {
     *   "category_id": 2
     * }
     *
     * @apiSuccess {Object[]} products Список товаров
     * @apiSuccess {String} result OK при успешном запросе
     *
     * @apiSuccessExample {json} Пример успешного ответа:
     * HTTP/1.1 200 OK
     * {
     *     "result": "OK",
     *     "products": [
     *        {
     *           "id": 530,
     *           "name": "VC2101SCY",
     *           "photo_url": "http://gorenje.f/data/products/",
     *           "bonuses_formula": null
     *        },
     *        {
     *           "id": 531,
     *           "name": "VC2101BKCY",
     *           "photo_url": "http://gorenje.f/data/products/",
     *           "bonuses_formula": null
     *        },
     *        {
     *           "id": 532,
     *           "name": "VC1701GACWCY",
     *           "photo_url": "http://gorenje.f/data/products/",
     *           "bonuses_formula": null
     *        }
     *     ]
     */
    public function actionGetProductsFromCategory()
    {
        $category_id = Yii::$app->request->post('category_id');

        $category = Category::findOne(['id' => $category_id]);
        if(empty($category)) {
            return $this->error("Не найдена категория с ID {$category_id}");
        }

        $products = Product::find()->where(['category_id' => $category_id, 'enabled' => true])->all();
        if(empty($products)) {
            return $this->error("Отсутствуют товары в категории с ID {$category_id}");
        }

        return $this->ok(compact('products'), 'Получение списка продуктов из категории');
    }
}
