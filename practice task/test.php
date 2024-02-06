<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo"first php";?></title>
</head>
<body>

 <?php
$arr=array(1,2,3,4,5,6,7,8,9);
echo '- Sum of array <br>';
echo array_sum($arr);
echo ' <br>';
echo '- print array   <br>';
for ($i=0; $i <count($arr) ; $i++) { 
    echo $arr[$i];
    echo '<br>';}   
echo '- table of 7  <br>';
for ($i=1; $i < 11; $i++) { 
    echo $i*7;
    echo '<br>';
}
echo '<br>';
echo '- using while loop  <br>';
$x=0;
do {
   echo "The number is: $x <br>";
   $x++;
} while ($x <= 5);

echo '<br>';

echo '- using while loop  <br>';
$var=0;
while($var<=10){
    $var++;
    echo $var;
    echo '<br>';

}?> 

<?php
// echo "hello ayush garg ";
echo '<br>';
$arr= array(1,2,3,4,5);

for ($i=0; $i < count($arr); $i++) {
    echo $arr[$i];
}
echo '<br>';
$di = array(1,69,21,23,45);
sort($di);
echo '<br>';
$clength = count($di);
for($x = 0; $x < $clength; $x++) {
  echo $di[$x];
  echo "<br>";
}
$hello= array("1"=>"one","2"=>"two","3"=>"three");
echo '<br>';
foreach ($hello as $key => $value) {
    echo "$key $value";
    echo "<br>";
}


$mul_dim=array(array(1,2,3),array(4,5,6),array(7,8,9));

// echo $mul_dim[1][1];
echo '<br>';
for ($i=0; $i <count($mul_dim); $i++) { 
   for ($j=0; $j < count($mul_dim[$i]); $j++) {
        echo $mul_dim[$i][$j]; 
        echo ' ';
    }
    echo '<br>';
}
?> 
<?php echo "<br>";

$ty= 4;
$gh= 5;

function sum(){
    global $ty;
    global $gh;
    $ty= 2;
    $gh= 7;
    echo "$ty hello i am $gh";
};
$f= sum();
echo $f;

echo "$ty hello i am $gh";

?>
</body>
</html>





