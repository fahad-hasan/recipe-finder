<?php

//Various includes
include join(DIRECTORY_SEPARATOR, ["..", "src", "item.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "fridge.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "recipe.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "dataloader.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "finder.class.php"]);

/*
Get command line options
--csv: location of the CSV file
--json: location of the JSON file
*/
$options = getopt("c:j:t", ["csv:", "json:"]);
if (!empty($options['csv']) && !empty($options['json'])) {
	if (file_exists($options['csv']) && file_exists($options['json'])) {
		//Initialize a RecipeFinder object based on command line args
		$finder = new RecipeFinder($options['csv'], $options['json']);
		$finder->find();
	}
} else {
	//Print help texts and tests
	print("Welcome to Recipe Finder!\r\n");
	print("Usage:\r\n\r\n");
	print("--csv:\tRequired\tThe location of the CSV Fridge items file\r\n");
	print("--json:\tRequired\tThe location of the JSON Recipes file\r\n\r\n");
	print("In order to run the unit tests, please run \"phpunit --verbose ..".DIRECTORY_SEPARATOR."tests".DIRECTORY_SEPARATOR."tests.php\"\r\n");
}
?>