<?php

/*
Class: FridgeItem
Represents an item inside Fridge. Extends abstract class aItem.
$use_by is a DateTime object. The constructor takes string date input and converts to DateTime object.
*/
class FridgeItem extends aItem {
	
	public $use_by;

	public function __construct($_name, $_amount, $_unit, $_use_by) {
		$this->name = $_name;
		$this->amount = $_amount;
		$this->unit = $_unit;
		$this->use_by = DateTime::createFromFormat('d/m/Y',$_use_by);
	}

	//Checks whether this item is usable or not. Compares the expiry date to current date and returns TRUE/FALSE.
	public function isUsable() {
		$today = new DateTime();
		return $today <= $this->use_by;
	}

}

/*
Class: FridgeItemFactory
This class acts as a factory for creating FridgeItem objects.
*/
class FridgeItemFactory {

	//Creates an item inside the fridge
	public static function create($_name, $_amount, $_unit, $_use_by) {
		return new FridgeItem($_name, $_amount, $_unit, $_use_by);
	}

}

/*
Class: Fridge
This class holds all the fridge items in a static array.
*/
class Fridge {

	private static $items;

	//Adds an item to the Fridge
	public static function addItem($_name, $_amount, $_unit, $_use_by) {
		self::$items[] = FridgeItemFactory::create($_name, $_amount, $_unit, $_use_by);
	}

	//Get all the items inside the Fridge
	public static function getItems() {
		return self::$items;
	}

	//Clears fridge items
	public static function clear() {
		self::$items = array();
	}

	//Check if a RecipeItem has enough usable item inside the Fridge
	public static function hasEnoughUsableItem(RecipeItem $rItem) {
		//Loop inside Fridge items
		foreach(self::$items as $fItem) {
			if($fItem->name == $rItem->name && $fItem->amount >= $rItem->amount && $fItem->isUsable()) {
				//Return true if the RecipeItem matches FridgeItem and isUsable == true
				return true;
			}
		}
		return false;
	}

	//Check and return the use_by date for a perticular RecipeItem that matches a FridgeItem
	public static function getCookByForIngredient(RecipeItem $rItem) {
		foreach(self::$items as $fItem) {
			if($fItem->name == $rItem->name && $fItem->amount >= $rItem->amount && $fItem->isUsable()) {
					////Return use_by date if the RecipeItem matches FridgeItem and isUsable == true
					return $fItem->use_by;
			}
		}
		//Return null if there is no match or isUsable == false
		return null;
	}

}

?>