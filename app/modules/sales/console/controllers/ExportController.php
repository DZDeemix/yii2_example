<?php

namespace modules\sales\console\controllers;

use console\base\Controller;
use modules\sales\backend\controllers\SalePositionsController;
use modules\sales\backend\controllers\SalesController;
use modules\sales\backend\models\SalePositionSearchWithData;
use modules\sales\backend\models\SaleSearchWithData;
use Yii;
use yii\data\DataProviderInterface;
use yz\admin\grid\GridView;

/**
 * Class ExportController
 */
class ExportController extends Controller
{
    public function actionIndex()
    {
        $this->updateSales();

        $params = [];
        $searchModel = Yii::createObject(SaleSearchWithData::class);
        $dataProvider = $searchModel->search($params);

        $controller = new SalesController('console-controller', null);
        $columns = $controller->getGridColumns();

        $grid = GridView::widget([
            'renderAllPages' => true,
            'layout' => "{items}",
            'showSettings' => false,
            'showTotal' => false,
            'runInConsoleMode' => true,
            'tableOptions' => ['class' => '', 'border' => 1],
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);

        $content = strtr(self::EXPORT_TEMPLATE, [
            '{grid}' => $grid,
        ]);

        $outputFileType = 'Excel2007';
        $outputFileName = Yii::getAlias('@data/sales.xlsx');

        $inputFileType = 'HTML';
        $fileName = Yii::getAlias('@data/sales.html');
        file_put_contents($fileName, $content);

        $objPHPExcelReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objPHPExcelReader->load($fileName);

        $objPHPExcelWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
        $objPHPExcelWriter->save($outputFileName);
    }

    public function actionPositions()
    {
        $params = [];
        $searchModel = Yii::createObject(SalePositionSearchWithData::class);
        $dataProvider = $searchModel->search($params);

        $controller = new SalePositionsController('console-controller', null);
        $columns = $controller->getGridColumns();

        $grid = GridView::widget([
            'renderAllPages' => true,
            'layout' => "{items}",
            'showSettings' => false,
            'showTotal' => false,
            'runInConsoleMode' => true,
            'tableOptions' => ['class' => '', 'border' => 1],
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);

        $content = strtr(self::EXPORT_TEMPLATE, [
            '{grid}' => $grid,
        ]);

        $outputFileType = 'Excel2007';
        $outputFileName = Yii::getAlias('@data/sale_positions.xlsx');

        $inputFileType = 'HTML';
        $fileName = Yii::getAlias('@data/sale_positions.html');
        file_put_contents($fileName, $content);

        $objPHPExcelReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objPHPExcelReader->load($fileName);

        $objPHPExcelWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
        $objPHPExcelWriter->save($outputFileName);
    }

    private function updateSales()
    {
        Yii::$app->db->createCommand("
          UPDATE yz_sales s SET s.sale_products =
              (SELECT GROUP_CONCAT(CONCAT(':', p.product_id, ':') SEPARATOR '-')
              FROM yz_sales_positions p
              WHERE p.sale_id = s.id);
	      ")->execute();

        Yii::$app->db->createCommand("
            UPDATE yz_sales s SET s.sale_qty =
                (SELECT GROUP_CONCAT(CONCAT(':', p.quantity, ':') SEPARATOR '-')
                 FROM yz_sales_positions p
                 WHERE p.sale_id = s.id);
	      ")->execute();

        Yii::$app->db->createCommand("
            UPDATE yz_sales s SET s.sale_groups =
                (SELECT GROUP_CONCAT(CONCAT(':', product.group_id, ':') SEPARATOR '-')
                 FROM yz_sales_positions p
                 INNER JOIN yz_sales_products product ON product.id = p.product_id
                 WHERE p.sale_id = s.id);
	      ")->execute();
    }

    const EXPORT_TEMPLATE = <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8"/>
    <meta name=ProgId content=Excel.Sheet/>
    <meta name=Generator content="Microsoft Excel 11"/>

    <!--[if gte mso 9]>
    <xml>
        <x:excelworkbook>
            <x:excelworksheets>
                <x:excelworksheet>
                    <x:name>{name}</x:name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:excelworksheet>
            </x:excelworksheets>
        </x:excelworkbook>
    </xml>
    <![endif]-->
</head>
<body>
{grid}
</body>
</html>
HTML;
}