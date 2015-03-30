<?php
include '../src/item.class.php';

class RecipeFinder extends PHPUnit_Framework_TestCase
{
    public function testCreateRecipeItem() {
    	//Create a new Item for a Recipe
    	$item = RecipeItemFactory::create("bread", 2, "slices");

    	//Check Item details
    	$this->assertEquals($item->name, "bread");
    	$this->assertEquals($item->amount, 2);
    	$this->assertEquals($item->unit, "slices");
    }

    public function testCreateFridgeItem() {
    	$today = new DateTime();

    	//Create a new Item which expires tomorrow (P1D) to be put inside the Fridge
    	$item = FridgeItemFactory::create("beef", 500, "grams", $today->add(new DateInterval("P1D"))->format('d/m/Y'));
    	$this->assertEquals($item->name, "beef");
    	$this->assertEquals($item->amount, 500);
    	$this->assertEquals($item->unit, "grams");

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
    	$item = FridgeItemFactory::create("beef", 500, "grams", $expired->format('d/m/Y'));
    	Fridge::addItem("beef", 500, "grams", $expired->format('d/m/Y'));

    	//Make sure the Fridge has the item
    	$this->assertEquals(count(Fridge::getItems()), 1);
    	$this->assertEquals(Fridge::getItems(), [$item]);

    	//Create a simple recipe item for a recipe and check if the Fridge has enough usable of that item
    	$rItem = RecipeItemFactory::create("beef", 250, "grams");
    	$this->assertTrue(Fridge::hasEnoughUsableItem($rItem));

    }

    public function testCreateRecipe() {
    	$today = new DateTime();

    	//Create a simple recipe Steak
    	$recipe = new Recipe("Steak");
    	$ingredients = array();

    	//Steak needs beef
    	$rItem = RecipeItemFactory::create("beef", 200, "grams");
    	$ingredients[] = $rItem;
    	$recipe->addIngredient("beef", 200, "grams");
    	$this->assertEquals($recipe->getIngredients(), $ingredients);

			//We already have beef in Fridge
    	$this->assertEquals(count(Fridge::getItems()), 1);

    	//Check if we have enough beef inside Fridge
    	$this->assertTrue(Fridge::hasEnoughUsableItem($rItem));

    	//Adding peeper to the Recipe and to the Fridge
    	$recipe->addIngredient("peeper", 10, "grams");
    	Fridge::addItem("peeper", 200, "grams", $today->add(new DateInterval('P3M'))->format('d/m/Y'));

    	//Check if we gave everything to cook Steak
    	$this->assertTrue($recipe->canBeCooked());
    }
}
?>