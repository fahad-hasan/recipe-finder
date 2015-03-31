<?php

/*
Class: Unit
This class holds the constants for units
*/
class Unit
{
	const OF = 'of';
	const GRAMS = 'grams';
	const ML = 'ml';
	const SLICES = 'slices';
}


/*
Class: aItem
This is the abstract class for a single Item. An Item inside the fridge or an Ingredient in a recipe extends this class.
*/
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

?>
