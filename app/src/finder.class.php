<?php

/*
Class: DataLoader
Provides helping methods for loading data from various sources
*/
class DataLoader {

	//Returns array from a CSV
	public static function getArrayFromCSV($csv_path) {
		$data = array();
		$file = fopen($csv_path,"r");
		while(! feof($file)) {
		  $item = fgetcsv($file);
		  if (count($item) == 4) {
		  	$data[] = $item;
			}
		}
		fclose($file);
		return $data;
	}

	//Returns array from a JSON
	public static function getArrayFromJSON($json_path) {
		return json_decode(file_get_contents($json_path));
	}

}

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