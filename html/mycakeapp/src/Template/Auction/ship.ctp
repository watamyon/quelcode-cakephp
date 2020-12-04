<?php if ($login_userid === $bidder_id) : ?>
	<?php if (null == ($shipping_to)) : ?>
		<h2>商品「<?= $biditem['name'] ?>」の発送先情報</h2>
		<h3>※発送先情報</h3>
		<?= $this->Form->create($shipping) ?>
		<?= $this->Form->control('bidder_name'); ?>
		<?= $this->Form->control('address'); ?>
		<?= $this->Form->control('phone_number'); ?>
		<?= $this->Form->button('確定') ?>
		<?= $this->Form->end() ?>
	<?php elseif ($shipping_to['is_shipped'] === false) : ?>
		<h2>商品「<?= $biditem['name'] ?>」の発送先情報</h2>
		<table class="vertical-table">
			<tr>
				<th class="small" scope="row">宛名</th>
				<td><?= h($shipping_to['bidder_name']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">住所</th>
				<td><?= h($shipping_to['address']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">電話番号</th>
				<td><?= h($shipping_to['phone_number']) ?></td>
			</tr>
		</table>
		<h4>※まだ発送されていません。</h4>
	<?php endif; ?>
<?php elseif ($login_userid === $seller_id) : ?>
	<?php if (!isset($shipping_to)) : ?>
		<h2>商品「<?= $biditem['name'] ?>」の発送先詳細</h2>
		<h3>※落札者の発送先情報の入力を待っています。</h3>
	<?php elseif (isset($shipping_to) && $shipping_to['is_shipped'] === false) : ?>
		<!-- 発送先詳細の表示 -->
		<h2>商品「<?= $biditem['name'] ?>」の発送先詳細</h2>
		<table class="vertical-table">
			<tr>
				<th class="small" scope="row">宛名</th>
				<td><?= h($shipping_to['bidder_name']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">住所</th>
				<td><?= h($shipping_to['address']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">電話番号</th>
				<td><?= h($shipping_to['phone_number']) ?></td>
			</tr>
		</table>
		<?= $this->Form->create($shipping) ?>
		<?= $this->Form->button('発送通知を送る', ['type' => 'submit', 'name' => 'is_shipped']) ?>
	<?php elseif ($shipping_to['is_shipped'] === true && $shipping_to['is_received'] === false) : ?>
		<h2>商品「<?= $biditem['name'] ?>」の発送先詳細</h2>
		<table class="vertical-table">
			<tr>
				<th class="small" scope="row">宛名</th>
				<td><?= h($shipping_to['bidder_name']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">住所</th>
				<td><?= h($shipping_to['address']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">電話番号</th>
				<td><?= h($shipping_to['phone_number']) ?></td>
			</tr>
		</table>
		<h3>※発送しました。</h3>
	<?php elseif ($shipping_to['is_received'] === true) : ?>
		<h2>商品「<?= $biditem['name'] ?>」の発送先情報</h2>
		<table class="vertical-table">
			<tr>
				<th class="small" scope="row">宛名</th>
				<td><?= h($shipping_to['bidder_name']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">住所</th>
				<td><?= h($shipping_to['address']) ?></td>
			</tr>
			<tr>
				<th class="small" scope="row">電話番号</th>
				<td><?= h($shipping_to['phone_number']) ?></td>
			</tr>
		</table>
		<h3>※発送されました</h3>
		<h3>※受け取りました</h3>
	<?php endif; ?>
<?php endif; ?>
