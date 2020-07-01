<?php

use modules\sales\common\models\Sale;
use modules\sales\frontend\widgets\assets\AddSaleAsset;

/**
 * @var \yii\web\View $this
 * @var array $products
 * @var Sale $sale
 * @var boolean $backend
 */

\modules\sales\frontend\widgets\assets\EditSaleAsset::register($this);
$documents = $sale->documents;
?>

<form id="saleForm">
	<!--Добавленные изображения-->
	<p align="center" id="img_add">
        <?php if (!empty($documents)): ?><?php foreach ($documents as $document): ?><?php if ($document->isPdf): ?>
			<a class="uploading_img" href="<?= $document->url ?>" target="_blank">
				<img style="height:60px"
					 src="data:image/png;base64,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"/>
			</a>
        <?php else: ?>
			<a class="uploading_img" href="<?= $document->url ?>" target="_blank">
				<img src="<?= $document->url ?>">
			</a>
        <?php endif; ?>

			<input class="files" type="hidden" name="files[]" value="<?= $document->name ?>"/>
        <?php endforeach; ?><?php endif; ?>
	</p>

	<!--Удаление фотографии-->
	<div class="row">
		<div class="col-md-12 center">
			<p align="center" class="red_link_delete">Удалить фотографии</p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 center">
			<input style="display: none;" name="imageFile[]" id="input_image_file" type="file" multiple="multiple"
				   accept="application/pdf, image/*">
			<div id="upload_files_img" class="btn btn-rain noselect"><span>Добавить фото/скан чека</span></div>
			<div class="ajax-respond"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<ul class="errors" id="upload-errors"></ul>
		</div>
	</div>

	<!--Добавление товаров-->

	<div class="add_new_sales_item">
		<div class="row">
			<div class="<?= $backend ? 'col-md-7' : 'col-md-9' ?>"><label>Наименование</label></div>
			<div class="col-md-1"></div>
			<div class="col-md-2"><label>Количество</label></div>
            <?php if ($backend): ?>
				<div class="col-md-2">
					<label>Стоимость</label>
				</div>
            <?php endif; ?>
		</div>
	</div>

    <?php foreach ($sale->getOrderedPositions() as $position): ?>
		<div class="add_new_sales_item">
			<div class="row">
				<div class="<?= $backend ? 'col-md-7' : 'col-md-9' ?>">
					<select name="positions[]" class="form-control positions products-list">
						<option value="">Выберите продукт...</option>
                        <?php if (!empty($products)): ?><?php foreach ($products as $product): ?>
							<option value="<?= $product['id'] ?>"
                                <?= $position->product_id == $product['id'] ? 'selected="selected"' : '' ?>
									data-bonus="<?= $product['bonuses_formula'] ?>">
                                <?= $product['name'] ?>
                                <?php if (!empty($product['categoryName'])): ?>
									(<?= $product['categoryName'] ?>)
                                <?php endif; ?>
							</option>
                        <?php endforeach; ?><?php endif; ?>
					</select>
				</div>
				<div class="col-md-1">
					<i class="fa fa-times-circle-o remove" style="" title="Удалить"></i>
				</div>
				<div class="col-md-2">
					<input class="form-control count_sale_items" type="number" min="1" max="1000000"
						   name="count_sale_items[]" value="<?= $position->quantity ?>"/>
				</div>
                <?php if ($backend): ?>
					<div class="col-md-2">
						<input class="form-control count_sale_items" type="number" min="1" max="1000000"
							   name="cost_sale_items[]" value="<?= $position->cost_real ?>"/>
					</div>
                <?php endif; ?>
			</div>
		</div>
    <?php endforeach ?>

    <?php for ($i = 0; $i < 10; $i++): ?>
		<div class="add_new_sales_item hidden">
			<div class="row">
				<div class="<?= $backend ? 'col-md-7' : 'col-md-9' ?>">
					<select name="positions[]" class="form-control positions products-list">
						<option value="">Выберите продукт...</option>
                        <?php if (!empty($products)): ?><?php foreach ($products as $product): ?>
							<option value="<?= $product['id'] ?>" data-bonus="<?= $product['bonuses_formula'] ?>">
                                <?= $product['name'] ?>
                                <?php if (!empty($product['categoryName'])): ?>
									(<?= $product['categoryName'] ?>)
                                <?php endif; ?>
							</option>
                        <?php endforeach; ?><?php endif; ?>
					</select>
				</div>
				<div class="col-md-1">
					<i class="fa fa-times-circle-o remove" style="font-size: 35px; color: maroon;" title="Удалить"></i>
				</div>
				<div class="col-md-2">
					<input class="form-control count_sale_items" type="number" min="1" max="1000000"
						   name="count_sale_items[]" value="1"/>
				</div>
                <?php if ($backend): ?>
					<div class="col-md-2">
						<input class="form-control count_sale_items" type="number" min="1" max="1000000"
							   name="cost_sale_items[]" value=""/>
					</div>
                <?php endif; ?>
			</div>
		</div>
    <?php endfor; ?>

	<div id="first_sale_item" class="add_new_sales_item">
		<div class="row">
			<div class="col-md-10">
				<select name="positions[]" class="form-control positions">
					<option value="">Выберите продукт...</option>
                    <?php if (!empty($products)): ?><?php foreach ($products as $product): ?>
						<option value="<?= $product['id'] ?>" data-bonus="<?= $product['bonuses_formula'] ?>">
                            <?= $product['name'] ?>
                            <?php if (!empty($product['categoryName'])): ?>
								(<?= $product['categoryName'] ?>)
                            <?php endif; ?>
						</option>
                    <?php endforeach; ?><?php endif; ?>
				</select>
			</div>
			<div class="col-md-2">
				<input class="form-control count_sale_items" type="number" min="1" max="1000000"
					   name="count_sale_items[]" value="1"/>
			</div>
		</div>
	</div>

	<!--Добавление новой позиции-->
	<div class="row margin_row_add_position">
		<div class="col-md-12 center">
			<div align="center" id="add_new_sale_position" class="btn btn-rain noselect">
				<span>+ Добавить продукцию</span>
			</div>
		</div>
	</div>

	<!--Дата покупки, номер чека, Стоимость в баллах-->
	<div id="total_sale_information">
		<div class="row margin_row_total_sum_sale">
			<div class="col-md-4">
				<label for="sold_on">Дата покупки</label>
				<input id="sold_on" class="form-control" type="text" name="sold_on"
					   value="<?= (new \DateTime($sale->sold_on))->format('d.m.Y') ?>"/>
			</div>
			<div class="col-md-4">
				<label for="check_number">Номер чека</label>
				<input id="check_number" class="form-control" type="text" name="check_number"
					   value="<?= $sale->number ?>"/>
			</div>
			<div class="col-md-4">
				<label for="total_sum_sale">Стоимость в баллах</label>
				<input id="total_sum_sale" class="form-control" type="text" name="total_sum_sale" value=""/>
			</div>
		</div>

		<div class="row" style="margin-top: 10px;">
			<div class="col-md-12">
				<label for="place">Место покупки</label>
				<input id="place" class="form-control" type="text" name="place" value="<?= $sale->place ?>"/>
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
	<div class="row sale_register center">
		<div id="register_new_sale" class=" btn btn-rainbow noselect">
			<span>Сохранить изменения</span>
		</div>
	</div>
</form>

<!--Модалка успешной загрузки-->
<div id="modal_ok" data-showmodal="<?php if (\Yii::$app->request->isGet) {
    echo \Yii::$app->request->get('add');
} ?>"></div>
<div class="modal fade" id="saleOkForm" tabindex="-1" role="dialog" aria-labelledby="saleOkForm" aria-hidden="false">
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



