<?php if (!empty($model->$field)): ?>
	<div class="row" style="margin-bottom:20px;">
		<div class="col-sm-2"></div>
        <?php $getVal = time(); ?>
		<div class="col-sm-8">
			<a href="<?= Yii::getAlias('@backendWeb/data/products/' . $model->$field) ?>" target="_blank"
			   class="btn btn-default">
				<img src="<?= Yii::getAlias('@backendWeb/data/products/' . $model->$field . "?" . $getVal) ?>"
					 style="max-width:200px; max-height:100px;"/>
			</a>
		</div>
	</div>
<?php endif; ?>