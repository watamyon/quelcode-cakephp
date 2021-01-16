<h2>「<?= $biditem->name ?> 」の情報</h2>
<table class="vertical-table">
	<tr>
		<th class="small" scope="row">出品者</th>
		<td><?= $biditem->has('user') ? $biditem->user->username : '' ?></td>
	</tr>
	<tr>
		<th scope="row">商品名</th>
		<td><?= h($biditem->name) ?></td>
	</tr>
	<tr>
		<th scope="row">商品ID</th>
		<td><?= $this->Number->format($biditem->id) ?></td>
	</tr>
	<tr>
		<th scope="row">商品詳細</th>
		<td><?= h($biditem->detail) ?></td>
	</tr>
	<tr>
		<th scope="row">商品画像</th>
		<td><?php echo $this->Html->image('/img/auction/' . h($biditem->file_name)); ?></td>
	</tr>
	<tr>
		<th scope="row">終了時間</th>
		<td><?= h($biditem->endtime) ?></td>
	</tr>
	<tr>
		<th scope="row">残り時間</th>
		<!-- <td><?= h($biditem->endtime) ?></td> -->
		<td id="limit"></td>
	</tr>
	<tr>
		<th scope="row">投稿時間</th>
		<td><?= h($biditem->created) ?></td>
	</tr>
	<tr>
		<th scope="row"><?= __('終了した？') ?></th>
		<td><?= $biditem->finished ? __('Yes') : __('No'); ?></td>
	</tr>
</table>
<div class="related">
	<h4><?= __('落札情報') ?></h4>
	<?php if (!empty($biditem->bidinfo)) : ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th scope="col">落札者</th>
				<th scope="col">落札金額</th>
				<th scope="col">落札日時</th>
			</tr>
			<tr>
				<td><?= h($biditem->bidinfo->user->username) ?></td>
				<td><?= h($biditem->bidinfo->price) ?>円</td>
				<td><?= h($biditem->endtime) ?></td>
			</tr>
		</table>
	<?php else : ?>
		<p><?= '※落札情報は、ありません。' ?></p>
	<?php endif; ?>
</div>
<div class="related">
	<h4><?= __('入札情報') ?></h4>
	<?php if (!$biditem->finished) : ?>
		<h6><a href="<?= $this->Url->build(['action' => 'bid', $biditem->id]) ?>">《入札する！》</a></h6>
		<?php if (!empty($bidrequests)) : ?>
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th scope="col">入札者</th>
						<th scope="col">金額</th>
						<th scope="col">入札日時</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($bidrequests as $bidrequest) : ?>
						<tr>
							<td><?= h($bidrequest->user->username) ?></td>
							<td><?= h($bidrequest->price) ?>円</td>
							<td><?= $bidrequest->created ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p><?= '※入札は、まだありません。' ?></p>
		<?php endif; ?>
	<?php else : ?>
		<p><?= '※入札は、終了しました。' ?></p>
	<?php endif; ?>
</div>
<?php
// 現在時刻の取得
$year_c = intval(date('Y'));
$month_c = intval(date('m'));
$day_c = intval(date('d'));
$hour_c = intval(date('H'));
$minute_c = intval(date('i'));
$second_c = intval(date('s'));

// 終了時刻の取得
$end_date = $biditem->endtime;
$year_e = intval($end_date->format('Y'));
$month_e = intval($end_date->format('m'));
$day_e = intval($end_date->format('d'));
$hour_e = intval($end_date->format('H'));
$minute_e = intval($end_date->format('i'));
$second_e = intval($end_date->format('s'));
?>
<script>
	const calculate = () => {
		diff -= 1000;
		if (diff > 0) {
			let dDays = diff / (1000 * 60 * 60 * 24); // 日数
			let diff_c = diff % (1000 * 60 * 60 * 24);
			let dHour = diff_c / (1000 * 60 * 60); // 時間
			diff_c = diff_c % (1000 * 60 * 60);
			let dMin = diff_c / (1000 * 60); // 分
			diff_c = diff_c % (1000 * 60);
			let dSec = diff_c / 1000; // 秒'
			let msg2 = Math.floor(dDays) + "日" +
				Math.floor(dHour) + "時間" +
				Math.floor(dMin) + "分" +
				Math.floor(dSec) + "秒";
			document.getElementById('limit').innerHTML = msg2;
		} else {
			clearInterval(do_cal);
			document.getElementById('limit').innerHTML = 'オークションは終了しました。';
		}
	}
	// 現在時刻の取得
	const year_c = <?php echo $year_c ?>;
	const month_c = <?php echo $month_c ?>;
	const day_c = <?php echo $day_c ?>;
	const hour_c = <?php echo $hour_c ?>;
	const minute_c = <?php echo $minute_c ?>;
	const second_c = <?php echo $second_c ?>;
	const current_date = new Date(year_c, month_c, day_c, hour_c, minute_c, second_c);
	// 終了時刻の取得
	const year_e = <?php echo $year_e ?>;
	const month_e = <?php echo $month_e ?>;
	const day_e = <?php echo $day_e ?>;
	const hour_e = <?php echo $hour_e ?>;
	const minute_e = <?php echo $minute_e ?>;
	const second_e = <?php echo $second_e ?>;
	const end_date = new Date(year_e, month_e, day_e, hour_e, minute_e, second_e);
	// 差の計算
	let diff = end_date - current_date;
	diff += 1000;
	// 関数の実行
	const do_cal = setInterval(calculate, 1000);
	calculate();
</script>
