<?php
include join(DIRECTORY_SEPARATOR, ["..", "src", "item.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "fridge.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "recipe.class.php"]);
include join(DIRECTORY_SEPARATOR, ["..", "src", "finder.class.php"]);

class testRecipeFinder extends PHPUnit_Framework_TestCase
{
    public function testCreateRecipeItem() {
    	//Create a new Item for a Recipe
    	$item = RecipeItemFactory::create("bread", 2, Unit::SLICES);

    	//Check Item details
    	$this->assertEquals($item->name, "bread");
    	$this->assertEquals($item->amount, 2);
    	$this->assertEquals($item->unit, Unit::SLICES);
    }

    public function testCreateFridgeItem() {
    	$today = new DateTime();

    	//Create a new Item which expires tomorrow (P1D) to be put inside the Fridge
    	$item = FridgeItemFactory::create("beef", 500,  Unit::GRAMS, $today->add(new DateInterval("P1D"))->format('d/m/Y'));
    	$this->assertEquals($item->name, "beef");
    	$this->assertEquals($item->amount, 500);
    	$this->assertEquals($item->unit, Unit::GRAMS);

    	//Check if the Item has an expired date
    	$this->assertClassHasAttribute('use_by', 'FridgeItem');

    	//Check type of use_by attribute is DateTime or not
    	$this->assertInstanceOf('DateTime', $item->use_by);
    	$this->assertNotEquals($item->use_by->format('d/m/Y'), date('d/m/Y'));

    	//Check the item is still usable or has expired
    	$this->assertTrue($item->isUsable());
    }

    public function testCreateFridge() {
    	$today = new DateTime();

    	//Put a new item inside Fridge
    	$expired = $today->add(new DateInterval('P3D'));
    	$item = FridgeItemFactory::create("beef", 500,  Unit::GRAMS, $expired->format('d/m/Y'));
    	Fridge::addItem("beef", 500,  Unit::GRAMS, $expired->format('d/m/Y'));

    	//Make sure the Fridge has the item
    	$this->assertEquals(count(Fridge::getItems()), 1);
    	$this->assertEquals(Fridge::getItems(), [$item]);

    	//Create a simple recipe item for a recipe and check if the Fridge has enough usable of that item
    	$rItem = RecipeItemFactory::create("beef", 250,  Unit::GRAMS);
    	$this->assertTrue(Fridge::hasEnoughUsableItem($rItem));

    }

    public function testCreateRecipe() {
    	$today = new DateTime();

    	//Create a simple recipe Steak
    	$recipe = new Recipe("Steak");
    	$ingredients = array();

    	//Steak needs beef
    	$rItem = RecipeItemFactory::create("beef", 200,  Unit::GRAMS);
    	$ingredients[] = $rItem;
    	$recipe->addIngredient("beef", 200,  Unit::GRAMS);
    	$this->assertEquals($recipe->getIngredients(), $ingredients);

			//We already have beef in Fridge
    	$this->assertEquals(count(Fridge::getItems()), 1);

    	//Check if we have enough beef inside Fridge
    	$this->assertTrue(Fridge::hasEnoughUsableItem($rItem));

    	//Adding peeper to the Recipe and to the Fridge
    	$recipe->addIngredient("peeper", 10,  Unit::GRAMS);
    	Fridge::addItem("peeper", 200,  Unit::GRAMS, $today->add(new DateInterval('P3M'))->format('d/m/Y'));

    	//Check if we gave everything to cook Steak
    	$this->assertTrue($recipe->canBeCooked());
    }

    public function testCSVLoader() {

    	//Sample array
    	$list = array (
		    array('bread','10','slices','25/12/2015'),
		    array('cheese','10','slices','24/12/2015'),
		    array('butter','250','grams','25/12/2015'),
				array('peanut butter','250','grams','2/12/2015'),
				array('mixed salad','500','grams','26/12/2015')
			);

    	//Write the array into a CSV file
			$fp = fopen('csv_test.csv', 'w');
			foreach ($list as $fields) {
			    fputcsv($fp, $fields);
			}
			fclose($fp);

			//Check if the CSV was loaded properly
    	$items = DataLoader::getArrayFromCSV('csv_test.csv');
	   	$this->assertEquals(5, count($items));
    }

    public function testJSONLoader() {
    	
    	//Sample JSON string
    	$json_string = 
    	'[
				{
					"name": "grilled cheese on toast",
					"ingredients": [
						{ "item":"bread", "amount":"2", "unit":"slices"},
						{ "item":"cheese", "amount":"2", "unit":"slices"}
					]
				},
				{
					"name": "salad sandwich",
					"ingredients": [
						{ "item":"bread", "amount":"2", "unit":"slices"},
						{ "item":"mixed salad", "amount":"200", "unit":"grams"}
					]
				}
			]';
			
			//Write the JSON to a file
			$fp = fopen('json_test.json', 'w');
			fwrite($fp, $json_string);
			fclose($fp);

			//Check if the JSON was loaded properly
			$items = DataLoader::getArrayFromJSON('json_test.json');
			$this->assertEquals(2, count($items));
    }

    public function testFinder() {
    	//We already have sample files
    	$finder = new RecipeFinder('csv_test.csv', 'json_test.json');

    	//Based on the sample files, check if the program prints "grilled cheese on toast"
    	$this->expectOutputString('grilled cheese on toast');
			$finder->find();
    }
}

?>