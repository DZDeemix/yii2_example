<?php

use marketingsolutions\finance\models\Transaction;
use modules\profiles\common\widgets\assets\ProfilePurseAsset;

/**
 * @var \yii\web\View $this
 * @var \modules\profiles\common\models\Profile $profile
 * @var \marketingsolutions\finance\models\Transaction[] $transactions
 */

ProfilePurseAsset::register($this);
?>

<h3 style="margin-top:-15px; color:gray;"><?= $profile->purse->balance ?> <i class="fa fa-rub"></i></h3>

<?php if (!empty($transactions)): ?>
	<table class="table">
		<thead>
		<tr>
			<th style="width:50%">Название</th>
			<th style="width:10%">Баллы</th>
			<th style="width:40%">Тип</th>
		</tr>
		</thead>
		<tbody>
        <?php for ($i = 0; $i < count($transactions); $i++): ?>
			<tr>
				<td><?= $transactions[$i]->title ?></td>
				<td><?= $transactions[$i]->amount ?></td>
				<td>
                    <?php if ($transactions[$i]->type == Transaction::INCOMING): ?>
						<label class="label label-success">входящая</label>
                    <?php else: ?>
						<label class="label label-danger">исходящая</label>
                    <?php endif; ?>
					<div style="margin-top:10px; font-size: 12px;">
                        <?= (new \DateTime($transactions[$i]->created_at))->format('d.m.Y H:i') ?>
					</div>
				</td>
			</tr>
        <?php endfor ?>
		</tbody>
	</table>
<?php endif; ?>
