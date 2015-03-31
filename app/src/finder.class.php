<?php
class DataLoader {

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

	public static function getArrayFromJSON($json_path) {
		return json_decode(file_get_contents($json_path));
	}

}

class RecipeFinder {

	public function __construct($_csv, $_json){
		$items = DataLoader::getArrayFromCSV($_csv);
		foreach($items as $item) {
			Fridge::addItem($item[0], $item[1],  $item[2], $item[3]);
		}

		$recipes_json = DataLoader::getArrayFromJSON($_json);
		foreach($recipes_json as $recipeObj) {
			$recipe = RecipeCollection::create($recipeObj->name, $recipeObj->ingredients);
		}
	}

	public function find() {
		RecipeCollection::getSuggestion();
	}
}
?>