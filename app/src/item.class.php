<?php
abstract class aItem {
	
	public $name;
	public $amount;
	public $unit;

	public function __construct($_name, $_amount, $_unit) {
		$this->name = $_name;
		$this->amount = $_amount;
		$this->unit = $_unit;
	}

}

class RecipeItem extends aItem {

}

class RecipeItemFactory {

	public static function create($_name, $_amount, $_unit) {
		return new RecipeItem($_name, $_amount, $_unit);
	}

}

class Recipe {
	
	public $name;
	private $ingredients;

	public function __construct($_name) {
		$this->name = $_name;
	}

	public function addIngredient($_name, $_amount, $_unit) {
		$this->ingredients[] = RecipeItemFactory::create($_name, $_amount, $_unit);
	}

	public function getIngredients() {
		return $this->ingredients;
	}

	public function canBeCooked() {
		foreach($this->ingredients as $ingredient) {
			if (Fridge::hasEnoughUsableItem($ingredient)) {
				continue;
			} else {
				return false;
			}
		}
		return true;
	}

}

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

}
?>