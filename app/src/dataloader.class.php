<?php

/*
Class: DataLoader
Provides helping methods for loading data from various sources
*/
class DataLoader {

	//Loads fridge from a CSV
	public static function loadFridgeFromCSV($csv_path) {
		if (file_exists($csv_path)) {
			Fridge::clear();
			$file = fopen($csv_path,"r");
			while(! feof($file)) {
			  $item = fgetcsv($file);
			  if (count($item) == 4) {
					Fridge::addItem($item[0], $item[1],  $item[2], $item[3]);
				}
			}
			fclose($file);
		} else {
			echo "ERROR: Can not find the specified CSV file.\r\n";
			exit();
		}
	}

	//Loads recipes from a JSON
	public static function loadRecipesFromJSON($json_path) {
		if (file_exists($json_path)) {
			RecipeCollection::clear();
			$recipes_json = json_decode(file_get_contents($json_path));
			foreach($recipes_json as $recipeObj) {
				$recipe = RecipeCollection::create($recipeObj->name, $recipeObj->ingredients);
			}
		} else {
			echo "ERROR: Can not find the specified JSON file.\r\n";
			exit();
		}
	}

}

?>