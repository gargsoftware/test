<?php

    // if(isset($_POST['type']) && $_POST['type']== 'edit'){
    //     geteditrec($_POS);

    // }
    // else if(isset($_POST['type']) && $_POST['type']== 'add'){


    // }


    // public function geteditrec($post){



    // }


?>
<?php 
include 'conn.php';  
include 'HTML.php';
$fname=$lname=$email=$mobile_no=$address=$language=$comments=$gander=$pincode=$country=$state=$date="";
$targetDir = "img/";

if ($nameErr == "" && $lnameErr == "" && $emailErr == "" && $noErr == "" && $addressErr == "" && $languageErr == "" && $commentsErr == "" && $genderErr == "" && $countryErr == "" && $stateErr == "" && $pincodeErr == "" && $dateErr == ""){
        if(isset($_POST['submit'])){
        
            print_r($_POST);die;
        }
        if (isset($_POST['submit'])){
            $hid_inp=$_POST['hid_inp'];
            $fname=$_POST['fname'];   
            $lname=$_POST['lname'];   
            $email=$_POST['email'];   
            $mobile_no=$_POST['mobile_no'];   
            $address=$_POST['address'];  
            $comments=$_POST['comments'];   
            $country=$_POST['country'];         
            $state=$_POST['state'];         
            $pincode=$_POST['pincode'];
            $date=$_POST['date'];
            $img=$_FILES['file'];

            $language = isset($_POST['lang1']) ? implode(',', $_POST['lang1']) : '';
            if (isset($_POST['gender'])){
                $gander=$_POST['gender'];     
            }
            $fileName = basename($_FILES["file"]["name"]); 
            $targetFilePath = $targetDir . $fileName; 
            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION); 
            $allowTypes = array('jpg','png','jpeg','gif');  
                $sql1 = "INSERT INTO input_data (First_Name,Last_NAME,Email,Mobile_NUMBER,Address,LANGUAGE,Comments,Gender,Country,State,Pincode,`D.O.B`,image) VALUE('$fname','$lname','$email', '$mobile_no','$address','$language','$comments','$gander','$country','$state','$pincode','$date','$fileName')";
                if ($conn->query($sql1) === TRUE) {
                    echo '<div class="alert alert-warning alert-dismissible fade show" id="alert_tab" role="alert">
                    Data add successfully!!</div>';
                } 
                else {
                    echo "Error: " . $sql1 . "<br>" . $conn->error;
                }
                $conn->close();                   
        }
    }
// delete record ===========================
    if (isset($_POST['type'])){
        if($_POST['type']=='delete'){
            $id=$_POST['id'];
            $query = "delete from input_data where Id ="."'".$id."'";
    if ($conn->query($query) === TRUE) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> Data deleted  succesfully!</div>';
    }else{
        echo "Error: " . $sql1 . "<br>" . $conn->error;
    }
    } } 
  
// edit record ==============================
if (isset($_POST['type'])){
    if($_POST['type']=='edit'){
        $id=$_POST['id'];
        // $sql1 = "UPDATE `input_data` SET `First_Name`='$fname', `Last_NAME`='$lname', `Email`='$email', `Mobile_NUMBER`=$mobile_no, `Address`='$address', `Language`='$language', `Comments`='$comments', `Gender`='$gander', `Country`='$country', `State`='$state', `Pincode`=$pincode, `D.O.B`='$date', `image`='$img' WHERE  `Id`=$id";
        $sql1="select * from input_data where id=$id";
if ($conn->query($sql1) === TRUE) {
    $result = $conn->query($sql1);
    echo $result;
    } 
else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}
$conn->close();
} }               
?>