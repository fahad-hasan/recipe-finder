<?php

/*
Class: RecipeFinder
Loads CSV and JSON usinf DataLoader. Finds suggestion on Recipe to cook.
*/
class RecipeFinder {

	//Lock and load!
	public function __construct($_csv, $_json){
		//Load CSV into Fridge
		DataLoader::loadFridgeFromCSV($_csv);
		

		//Load JSON into RecipeCollection
		DataLoader::loadRecipesFromJSON($_json);
	}

	//Does the hasdest work, finds a recipe that you can cook!
	public function find() {
		//Get the suggestion from RecipeCollection.
		$recipes = RecipeCollection::getSuggestions();
		if(!empty($recipes)) {
			foreach($recipes as $recipe) {
				echo $recipe->name."\r\n";
			}
		} else {
			echo "Order Takeout";
		}
	}
}
?>