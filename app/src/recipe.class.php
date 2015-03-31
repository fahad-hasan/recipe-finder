<?php

/*
Class: RecipeItem
Represents a item in a Recipe
*/
class RecipeItem extends aItem {

}

/*
Class: RecipeItemFactory
A factory for creating RecipeItem objects
*/
class RecipeItemFactory {

	public static function create($_name, $_amount, $_unit) {
		return new RecipeItem($_name, $_amount, $_unit);
	}

}

/*
Class: Recipe
Represents a recipe. Has a name and a list of ingredients(ReciptItems)
*/
class Recipe {
	
	public $name;
	private $ingredients;
	public $cook_by;

	//The constructor only takes name as an input. List of ingredients must be added afterwards.
	public function __construct($_name) {
		$this->name = $_name;
		$cook_by = null;
	}

	//Adding a RecipeItem to the ingredients list
	public function addIngredient($_name, $_amount, $_unit) {
		$this->ingredients[] = RecipeItemFactory::create($_name, $_amount, $_unit);
	}

	//Returns the list of ingredients for the Recipe
	public function getIngredients() {
		return $this->ingredients;
	}

	//Compares and set the closest expires date as Recipe cook_by from the list of ingredients
	private function setCookBy($date) {
		if(empty($this->cook_by)){ //Has not been set yet?
			//Set whatever we have
			$this->cook_by = $date;
		} else if ($date < $this->cook_by) { //Has been set before?
			$this->cook_by = $date;
		}
	}

	//Check if a Recipe has enough of all the ingredients(RecipeItems) in the Fridge and checks their expiry date to be able to priortize among multiple Recipes
	public function canBeCooked() {
		foreach($this->ingredients as $ingredient) {
			//Check if we have enough of this ingredient usable inside the Fridge
			if (Fridge::hasEnoughUsableItem($ingredient)) {
				//Cool, now we note the expiry date for each ingredient, so that we can cook the Recipe that will otherwise expire soon
				$this->setCookBy(Fridge::getCookByForIngredient($ingredient));
			} else {
				//Sorry, go shop!
				return false;
			}
		}
		return true;
	}

}

/*
Class: RecipeCollection
Holds a list of Recipes and performs various operations
*/
class RecipeCollection {
	
	private static $recipes;

	//Creates a new Recipe and adds it to RecipeCollection
	public static function create($_name, $_ingredients) {
		$recipe = new Recipe($_name);
		foreach($_ingredients as $ingredient) {
			$recipe->addIngredient($ingredient->item, $ingredient->amount,  $ingredient->unit);
		}
		self::$recipes[] = $recipe;
		return $recipe;
	}

	//Get all the Recipes from the list
	public static function getItems() {
		return self::$items;
	}

	//Suggest a Recipe based on various business logics
	public static function getSuggestion() {

		$sorted_recipes = array();
		//Seperate the Recipes that canBeCooked
		foreach(self::$recipes as $recipe) {
			if ($recipe->canBeCooked()) {
				$sorted_recipes[] = $recipe;
			}
		}

		//If there are matches found:
		if (!empty($sorted_recipes)) {
			//Sort the Recipe based in ingredients which will expire soon
			usort($sorted_recipes, function($a, $b) {
				return ($a->cook_by > $b->cook_by);
			});
			//Print the Recipe name on top of the list
			echo reset($sorted_recipes)->name;
		} else {
			//Sorry, time to pick up the phone!
			return "Order Takeout";
		}
	}
}

?>