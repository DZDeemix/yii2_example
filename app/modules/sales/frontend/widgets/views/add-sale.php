<?php

use yii\helpers\Url;
use yii\grid\GridView;
use modules\sales\common\models\Sale;
use modules\sales\frontend\widgets\assets\AddSaleAsset;
use modules\sales\frontend\widgets\AddSale;
use yii\jui\DatePicker;

/**
 * @var \yii\web\View $this
 * @var array $products
 */

\yz\icons\FontAwesomeAsset::register($this);
AddSaleAsset::register($this);
\yii\jui\JuiAsset::register($this);
?>
<div class="row">
	<div class="col-md-12">
		<h1 align="center">Регистрация покупок</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">

	</div>
</div>
<form id="saleForm">
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<input style="display: none;" name="imageFile[]" id="input_image_file" type="file" multiple="multiple"
				   accept="application/pdf, image/*">
			<div id="upload_files_img" class="btn btn-rain noselect full"><span>Загрузите фото/скан чека</span></div>
			<div class="ajax-respond"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8 ">
			<ul class="errors" id="upload-errors"></ul>
		</div>
	</div>
	<!--Добавленные изображения-->
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8 ">
			<p align="center" id="img_add"></p>
			<input type="hidden" id="upload_images_filename" name="upload_images_filename" value=""/>
		</div>
	</div>
	<!--Удаление фотографии-->
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8 ">
			<p align="center" class="red_link_delete">Удалить фотографии</p>
		</div>
	</div>

	<!--Добавление товаров-->
    <?php for ($i = 0; $i < 10; $i++): ?>
		<div class="add_new_sales_item <?= $i ? 'hidden' : '' ?>">
			<div class="row">
				<div class="col-md-9">
					<select name="positions[]" class="form-control positions products-list">
						<option value="">Выберите продукт...</option>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
								<option value="<?= $product['id'] ?>" data-bonus="<?= $product['bonuses_formula'] ?>">
                                    <?= $product['name'] ?>
                                    <?php if (!empty($product['categoryName'])): ?>
										(<?= $product['categoryName'] ?>)
                                    <?php endif; ?>
								</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
					</select>
				</div>
				<div class="col-md-1">
					<i class="fa fa-times-circle-o remove" style="" title="Удалить"></i>
				</div>
				<div class="col-md-2">
					<input class="form-control count_sale_items" type="number" min="1" max="1000000"
						   name="count_sale_items[]" value="1"/>
				</div>
			</div>
		</div>
    <?php endfor; ?>

	<!--Добавление новой позиции-->
	<div class="row margin_row_add_position">
		<div class="col-md-4"></div>
		<div class="col-md-4 ">
			<div align="center" id="add_new_sale_position" class="btn btn-rain noselect full"><span>+ Добавить продукцию</span>
			</div>
		</div>
	</div>
	<!--Дата покупки, номер чека, Стоимость в баллах-->
	<div id="total_sale_information">
		<div class="row margin_row_total_sum_sale">
			<div class="col-md-4">
				<label for="sold_on">Дата покупки</label>
				<input id="sold_on" class="form-control" type="text" name="sold_on" placeholder="дд.мм.гггг"/>
			</div>
			<div class="col-md-4">
				<label for="check_number">Номер чека</label>
				<input id="check_number" class="form-control" type="text" name="check_number" value=""/>
			</div>
			<div class="col-md-4">
				<label for="total_sum_sale">Стоимость в баллах</label>
				<input id="total_sum_sale" class="form-control" type="text" name="total_sum_sale" value=""/>
			</div>
		</div>

		<div class="row" style="margin-top: 10px;">
			<div class="col-md-12">
				<label for="place">Место покупки</label>
				<input id="place" class="form-control" type="text" name="place" value=""/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<ul class="errors" style="color:red;" id="sale-errors"></ul>
		</div>
	</div>
	<!--Кнопка отправки-->
	<div class="row sale_register">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div id="register_new_sale" class=" btn btn-rainbow noselect full">Зарегистрировать покупку</div>
		</div>
	</div>
</form>

<!--Модалка успешной загрузки-->
<div id="modal_ok" data-showmodal="<?php if (\Yii::$app->request->isGet) {
    echo \Yii::$app->request->get('add');
} ?>"></div>
<div class="modal fade" id="saleOkForm" tabindex="-1" role="dialog" aria-labelledby="saleOkForm"
	 aria-hidden="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<div class="modal-title">Продажа успешно добавлена</div>
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<button type="submit" id="close_modal_ok" class="btn btn-rainbow noselect full">Ок</button>
				</div>

			</div>
		</div>
	</div>
</div>



