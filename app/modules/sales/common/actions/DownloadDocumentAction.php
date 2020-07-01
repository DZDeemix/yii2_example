<?php

namespace modules\sales\common\actions;

use modules\sales\common\models\SaleDocument;
use marketingsolutions\thumbnails\Thumbnail;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;


/**
 * Class DownloadDocumentAction
 */
class DownloadDocumentAction extends Action
{
    const TYPE_STANDARD = 'standard';
    const TYPE_SMALL = 'small';

    public $format = self::TYPE_STANDARD;
    /**
     * @var callable Callback format is:
     * ```php
     * function (SaleDocument $document) {
     *  return true;
     * }
     * ```
     */
    public $checkAccess;

    public function run($id)
    {
        $document = $this->findModel($id);

        $this->checkAccess($document);

        if ($this->format == self::TYPE_SMALL) {
            return $this->controller->redirect((new Thumbnail())->url($document->getFileName(), Thumbnail::thumb(135, 135)));
        }

        return \Yii::$app->response->sendFile($document->getFileName(), $document->original_name, [
            'inline' => true,
        ]);
    }

    private function checkAccess($document)
    {
        if ($this->checkAccess === null) {
            return;
        }

        if (call_user_func($this->checkAccess, $document) == false) {
            throw new UnauthorizedHttpException();
        }
    }

    /**
     * @param $id
     * @return SaleDocument
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = SaleDocument::findOne($id)) === null) {
            throw new NotFoundHttpException();
        };
        return $model;
    }
}