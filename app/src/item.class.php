<?php
class Unit
{
	const OF = 'of';
	const GRAMS = 'grams';
	const ML = 'ml';
	const SLICES = 'slices';
}

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
