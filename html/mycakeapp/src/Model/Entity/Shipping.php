<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Shipping Entity
 *
 * @property int $id
 * @property int $item_id
 * @property string $bidder_name
 * @property string $address
 * @property string $phone_number
 * @property bool $is_shipped
 * @property bool $is_received
 *
 * @property \App\Model\Entity\Item $item
 */
class Shipping extends Entity
{
	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 *
	 * Note that when '*' is set to true, this allows all unspecified fields to
	 * be mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove it), and explicitly make individual fields accessible as needed.
	 *
	 * @var array
	 */
	protected $_accessible = [
		'item_id' => true,
		'bidder_name' => true,
		'address' => true,
		'phone_number' => true,
		'is_shipped' => true,
		'is_received' => true,
		'biditems' => true,
	];
}
