<?php
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
	public $cook_by;

	public function __construct($_name) {
		$this->name = $_name;
		$cook_by = null;
	}

	public function addIngredient($_name, $_amount, $_unit) {
		$this->ingredients[] = RecipeItemFactory::create($_name, $_amount, $_unit);
	}

	public function getIngredients() {
		return $this->ingredients;
	}

	private function setCookBy($date) {
		if(empty($this->cook_by)){
			$this->cook_by = $date;
		} else if ($date < $this->cook_by) {
			$this->cook_by = $date;
		}
	}

	public function canBeCooked() {
		foreach($this->ingredients as $ingredient) {
			if (Fridge::hasEnoughUsableItem($ingredient)) {
				$this->setCookBy(Fridge::getCookByForIngredient($ingredient));
				continue;
			} else {
				return false;
			}
		}
		return true;
	}

}

class RecipeCollection {
	
	private static $recipes;

	public static function create($_name, $_ingredients) {
		$recipe = new Recipe($_name);
		foreach($_ingredients as $ingredient) {
			$recipe->addIngredient($ingredient->item, $ingredient->amount,  $ingredient->unit);
		}
		self::$recipes[] = $recipe;
		return $recipe;
	}

	public static function getItems() {
		return self::$items;
	}

	public static function getSuggestion() {
		$sorted_recipes = array();
		foreach(self::$recipes as $recipe) {
			if ($recipe->canBeCooked()) {
				$sorted_recipes[] = $recipe;
			}
		}

		usort($sorted_recipes, function($a, $b) {
			return ($a->cook_by > $b->cook_by);
		});

		if (!empty($sorted_recipes)) {
			echo reset($sorted_recipes)->name;
		} else {
			return "Order Takeout";
		}
	}
}

?>