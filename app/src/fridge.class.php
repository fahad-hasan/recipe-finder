<?php
class FridgeItem extends aItem {
	
	public $use_by;

	public function __construct($_name, $_amount, $_unit, $_use_by) {
		$this->name = $_name;
		$this->amount = $_amount;
		$this->unit = $_unit;
		$this->use_by = DateTime::createFromFormat('d/m/Y',$_use_by);
	}

	public function isUsable() {
		$today = new DateTime();
		return $today <= $this->use_by;
	}

}

class FridgeItemFactory {

	public static function create($_name, $_amount, $_unit, $_use_by) {
		return new FridgeItem($_name, $_amount, $_unit, $_use_by);
	}

}

class Fridge {

	private static $items;

	public static function addItem($_name, $_amount, $_unit, $_use_by) {
		self::$items[] = FridgeItemFactory::create($_name, $_amount, $_unit, $_use_by);
	}

	public static function getItems() {
		return self::$items;
	}

	public static function hasEnoughUsableItem(RecipeItem $rItem) {
		foreach(self::$items as $fItem) {
			if($fItem->name == $rItem->name && $fItem->amount >= $rItem->amount && $fItem->isUsable()) {
				return true;
			} else {
				continue;
			}
		}
		return false;
	}

	public static function getCookByForIngredient(RecipeItem $rItem) {
		foreach(self::$items as $fItem) {
			if($fItem->name == $rItem->name && $fItem->amount >= $rItem->amount && $fItem->isUsable()) {
					return $fItem->use_by;
			}
		}
		return null;
	}

}

?>