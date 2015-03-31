<?php

/*
Class: RecipeFinder
Loads CSV and JSON usinf DataLoader. Finds suggestion on Recipe to cook.
*/
class RecipeFinder {

	//Lock and load!
	public function __construct($_csv, $_json){
		//Load CSV into Fridge
		$items = DataLoader::getArrayFromCSV($_csv);
		foreach($items as $item) {
			Fridge::addItem($item[0], $item[1],  $item[2], $item[3]);
		}

		//Load JSON into RecipeCollection
		$recipes_json = DataLoader::getArrayFromJSON($_json);
		foreach($recipes_json as $recipeObj) {
			$recipe = RecipeCollection::create($recipeObj->name, $recipeObj->ingredients);
		}
	}

	//Does the hasdest work, finds a recipe that you can cook!
	public function find() {
		//Get the suggestion from RecipeCollection.
		RecipeCollection::getSuggestion();
	}
}
?>