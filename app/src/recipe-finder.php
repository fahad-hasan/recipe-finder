<?php
include join(DIRECTORY_SEPARATOR, ["..", "src", "item.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "fridge.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "recipe.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "finder.class.php"]);

$options = getopt("c:j:t", ["csv:", "json:"]);
if (!empty($options['csv']) && !empty($options['json'])) {
	if (file_exists($options['csv']) && file_exists($options['json'])) {
		$finder = new RecipeFinder($options['csv'], $options['json']);
		$finder->find();
	}
} else {
	print("Welcome to Recipe Finder!\r\n");
	print("Usage:\r\n\r\n");
	print("--csv:\tRequired\tThe location of the CSV Fridge items file\r\n");
	print("--json:\tRequired\tThe location of the JSON Recipes file\r\n\r\n");
	print("In order to run the unit tests, please run \"phpunit --verbose ..".DIRECTORY_SEPARATOR."tests".DIRECTORY_SEPARATOR."tests.php\"\r\n");
}
?>