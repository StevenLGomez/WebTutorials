# PHP_A
PHP Essentials Training working files (video tutorial from lynda.com)

This project serves two purposes:
1.) To host the source files for the Lynda.com tutorial mentioned above.
2.) To serve as a Git tutorial platform to allow myself to transition from the Subversion
    world into Git.

The source files contained in this repository are not my work, other than having been typed
in my me as I worked my way through the video tutorial.  Information contained here 
should be considered the property of Lynda.com.

Some very, very basics:
// String '' ""
$name = "My Name";

// Interger
$age = 28;

// Float/Decimal
$age_exact = 28.75;

// Boolean/bool
$bool = true;
$bool = false;
$bool = 1;  // true
$bool = 0;  // false

// Array
$array = ["name", "age", "country"];
$array = [true, 123123.123123, 28];

// Constants
define('__NAME__', 'El Guapo';
echo __NAME__;

// __NAME__ = "Bullwinkle";  << Will fail, constants cannot be redefined.

// Arrays
// $array = ["Mr. Gomez", 28, "0 cats"];

// With arrays, you can loop through them...

print_r($array);

//Associative Arrays
echo <br />';
$array = [
	"name" => "Mr. Gomez",
	"website" => "steven-gomez.com',
	"age" = 69,
	"cats" => "0 cats"
]:
print_r($array);

Loops:

// 1. for loop
// 2. while loop
// 3. foreach loop

$array = [
	"name" => "Mr. Gomez",
	"website" => "steven-gomez.com",
	"age" => 69,
	"cats" => "0 cats"
};

// 1. For loop
for ($i = 0; $#i < 10; i++)
{
	echo '<hr />For loop<br />';
	echo "Line: %1 <br />";
}

// 2. While loop
	echo '<hr />While loop<br />';
$num = 0;
while ($num < 10)
{
	echo "line: $num <br />";
	$num++;
}

// 3. foreach loop
	echo '<hr />foreach loop<br />';
foreach ($array as $key => $val)
{
	echo "$key. $val <br />";
}

	// OR

	echo '<hr />foreach loop (shorthand)<br />';
foreach ($array as $val)
{
	echo "$val <br />";
}










