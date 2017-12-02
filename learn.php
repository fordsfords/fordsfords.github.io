<html><head><title>x.php</title></head>
<body>
<h1>x.php</h1>
<?php
echo "<pre>\n";

// Set up assertion stuff
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

$num_asserts = 0;
function my_assert_handler($file, $line, $code)
{
  echo "<hr>Assertion Failed:<br />File '$file'<br /> Line '$line'<br /> Code '$code'<br /><hr />";
  $GLOBALS['num_asserts']++;
}

assert_options(ASSERT_CALLBACK, 'my_assert_handler');


$a = 1;
$b = 1;

a1();
assert($a == 1);
assert($b == 1);

function a1() {
  $a = 2;
  $b = 2;
}


function a2() {
  global $a;  // Can comma-separate multiple vars.
  $a = 2;
  $b = 2;
}

a2();
assert($a == 2);
assert($b == 1);


function a3() {
  $GLOBALS['a'] = 3;
}

a3();
assert($a == 3);


function a4() {
  global $a;  // Can comma-separate multiple vars.
  static $x = 0;
  $x++;
  $a = $x;
}

a4();
assert($a == 1);
a4();
assert($a == 2);


// String stuff.
assert(strlen("Hello" . ' world') == 11);
assert(str_word_count('Hello world!') == 2);
assert(strrev("Hello world") == "dlrow olleH");
assert(strpos("Hello world", "wor") == 6);
assert(str_replace("world", "Dolly", "Hello world") == "Hello Dolly");
$a = 2;
assert("Hello $a world" == 'Hello 2 world');  // 
assert('Hello $a world' == 'Hello $a world');


// Conditional stuff.
if ("Hello" == "world") {
  assert(false);
} elseif ("world" == "Hello") {
  assert(false);
} else {
  assert(true);
}

$a = 2;
switch ($a) {
  case "red":
    assert(false);
    break;
  case 2:
    assert(true);
    break;
  case 0:
    assert(false);
    break;
  default:
    assert(false);
}
// "while", "do { ... } while", "for" operate as one would expect.

$a = 0;
foreach (array("a", "b", "c") as $b) {
  if ($a == 0) { assert($b == "a"); }
  $a++;
}
assert($a == 3);

$a = 0;
foreach (array("a", "b", "c") as $b => $c) {
  if ($a == 0) {
    assert($b == 0);
    assert($c == "a");
  }
  $a++;
}
assert($a == 3);


// Array stuff.
$a = array("x", "y", "z");
assert($a[0] == "x");
assert(count($a) == 3);

$a = array("a"=>1, "b"=>2, "c"=>3, "d"=>4);
assert(count($a) == 4);
assert($a["b"] == 2);

$a = array("x", "y", "z");
$b = array("a", "b", "c", "d");
$c = $a + $b;
$d = array('x', 'y', 'z', 'd');
assert($c == $d);

assert(sort($d) == array('d', 'x', 'y', 'z'));
assert(rsort($d) == array('z', 'y', 'x', 'd'));
assert(ksort($d) == array('x', 'y', 'z', 'd')); // sort by key


// Function stuff.
function b1($iline, $suffix = ".") {
  return $iline . $suffix;
}

assert(b1("Hello World") == "Hello World.");
assert(b1("Hello World", "") == "Hello World");


// Regular expression stuff.
if (preg_match("/a(b*)c/", " ac abc abbc ", $a) == 1) {
  assert($a[0] == "ac");
  assert($a[1] == "");
} else {
  assert(false);
}

if (preg_match("/A(b+)c/i", " ac abc abbc ", $a) == 1) {
  assert($a[0] == "abc");
  assert($a[1] == "b");
} else {
  assert(false);
}

$a = preg_replace("/a(b+)c/", "x$1y", " ac abc abbc ");
assert($a == " ac xby xbby ");

$a = preg_replace("/a(b+)c/", "x$1y", " ac abc abbc ", 1);
assert($a == " ac xby abbc ");

$z = preg_split("/,/", "1,2,3,4");
assert($z[0] == 1);


if ($num_asserts == 0) {
  echo "All tests passed.\n";
} else {
  echo "$num_asserts tests failed!\n";
}

echo "</pre>\n";
?>
</body></html>
