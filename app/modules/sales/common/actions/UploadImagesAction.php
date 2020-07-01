<?php

namespace modules\sales\common\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;
use modules\sales\common\models\UploadSaleForm;

/**
 * Class UploadImagesAction
 */
class UploadImagesAction extends Action
{

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = 200;

        $documents = [];
        $model = new UploadSaleForm();

        try {
            if (Yii::$app->request->isPost) {
                //Максимум 10 файлов
                for ($i = 0; $i <= 10; $i++) {
                    if (UploadedFile::getInstanceByName($i)) {
                        $model->imageFile = UploadedFile::getInstanceByName($i);
                        if ($filename = $model->upload()) {
                            $documents[] = $filename;
                        }
                    }
                }

                if (empty($documents)) {
                    Yii::$app->response->statusCode = 400;
                    return ['result' => 'FAIL', 'errors' => ['Пожалуйста, загрузите файл формата: .png .jpg .gif .jpeg .pdf. Максимальный размер файла: 20 МБ']];
                }
                if (count($documents) > 0 && count($documents) <= 10) {
                    return $documents;
                }
                else {
                    Yii::$app->response->statusCode = 400;
                    return ['result' => 'FAIL', 'errors' => ['Можно прикрепить макимум 10 файлов за 1 покупку!']];
                }
            }

            Yii::$app->response->statusCode = 400;
            return ['result' => 'FAIL', 'errors' => ['Пожалуйста, загрузите файл формата: .png .jpg .gif .jpeg .pdf. Максимальный размер файла: 20 МБ']];
        }
        catch (\Exception $e) {
            return ['result' => 'FAIL', 'errors' => ['Пожалуйста, загрузите файл формата: .png .jpg .gif .jpeg .pdf. Максимальный размер файла: 20 МБ']];
        }
    }


}