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

?>