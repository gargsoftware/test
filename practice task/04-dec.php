<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number to Words Converter</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<?php

function numberToIndianWords($number) {
    $words = array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');

    $tens = array('twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');

    $numWords = array();

    if ($number < 20) {
        $numWords[] = $words[$number];
    } elseif ($number < 100) {
        $numWords[] = $tens[($number / 10) - 2]; 
        $remainder = $number % 10;
        if ($remainder) {
            $numWords[] = $words[$remainder];
        }
    } elseif ($number < 1000) {
        $numWords[] = $words[(int)($number / 100)] . ' hundred';
        $remainder = $number % 100;
        if ($remainder) {
            $numWords[] = numberToIndianWords($remainder);
        }
    } else {
        $baseUnit = ['', 'thousand', 'lakh', 'crore'];

        $baseCount = floor(log10($number) / 2);
        $base = pow(100, $baseCount);

        $numWords[] = numberToIndianWords((int)($number / $base)) . ' ' . $baseUnit[$baseCount];

        $remainder = $number % $base;

        if ($remainder) {
            $numWords[] = numberToIndianWords($remainder);
        }
    }

    return implode(' ', $numWords);
}

?>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        enter a no : <input type="text" name="name"><br>
        <input type="submit">
    </form>

    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no = $_REQUEST['name'];
    
    if (empty($no)) {
        echo "empty";
    }
    else{
        echo numberToIndianWords($no);
    }
   }?>
</body>

</html>