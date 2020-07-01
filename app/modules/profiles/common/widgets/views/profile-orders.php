<?php

/**
 * @var \yii\web\View $this
 * @var \modules\profiles\common\models\Profile $profile
 * @var array $orders
 */

?>

<?php if (!empty($orders)): ?>
	<table class="table">
		<thead>
		<tr>
			<th>Заказ</th>
			<th>Сумма</th>
			<th>Тип</th>
			<th>Дата</th>
			<th>Статус</th>
		</tr>
		</thead>
		<tbody>
        <?php for ($i = 0; $i < count($orders); $i++): ?>
			<tr>
				<td><?= $orders[$i]['title'] ?></td>
				<td><?= $orders[$i]['amount'] ?></td>
				<td><?= $orders[$i]['type'] ?></td>
				<td><?= (new \DateTime($orders[$i]['created_at']))->format('d.m.Y') ?></td>
				<td><?= $orders[$i]['status'] ?></td>
			</tr>
        <?php endfor ?>
		</tbody>
	</table>
<?php endif; ?>
