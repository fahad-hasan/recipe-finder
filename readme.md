#Recipe Finder
This program makes a suggestion on what to cook based on a given list of recipes with ingredients and a list of items in the fridge.

####Features
- Fridge items that has expired, can not be used for cooking
- In case of multiple recipe match, the one having an ingredient expiring soon will have preference
- If no recipe matches, the program will suggest to order takeout
- All the classes and business logics contain unit tests

####Source
All classes can be found inside app/src folder.

####Tests
All tests can be found inside app/tests/tests.php
Please run "phpunit --verbose tests.php" to run the tests

####Usage
Please run recipe-finder.php from command line

php recipe-finder.php

The above should return help texts and instructions on how to run the program.

####Options
--csv: (Required)
The location of the CSV Fridge items file

--json: (Required)
The location of the JSON Recipes file