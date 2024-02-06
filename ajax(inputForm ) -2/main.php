<?php
include 'conn.php';
// get country ========================================================================================
$getCountry = function () use ($conn) {
    $sql = "SELECT id, name FROM countries";
    $result = mysqli_query($conn,$sql);
    // print_r($result);die;    
    // print_r(mysqli_num_rows($result));die;

    if (mysqli_num_rows($result)) {

        $output = "<option value=''>-select-</option>";
        while ($country = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $cid = $country['id'];
            $name = $country['name'];
            $output .= "<option value='" . $cid . "'>" . $name . "</option>";
        }
        echo $output;
    }
};
// get State ========================================================================================
$getState = function () use ($conn) {
    $sql = "SELECT id, name FROM states where country_id =101";
    $result = mysqli_query($conn,$sql);
    
    if (mysqli_num_rows($result)) {
        $output = "<option value=''>-select-</option>";
        // print_r(mysqli_fetch_array($result, MYSQLI_ASSOC));die;
        while ($state = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // print_r($state);die;
            $sid = $state['id'];
            $name = $state['name'];
            $output .= "<option value='" . $sid . "'>" . $name . "</option>";
        }
        echo $output;
    }
};                                 
// show list========================================================================================
$show_list = function () use ($conn) {
    $sql = "SELECT * FROM input_data";
    $result = mysqli_query($conn,$sql);
    
    if (mysqli_num_rows($result)) {
        $output = "";
        while ($state = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $sid_s = $state['Id'];
            $name_s = $state['First_Name'];
            $lname_s = $state['Last_NAME'];
            $email_s = $state['Email'];
            $no_s = $state['Mobile_NUMBER'];
            $address_s= $state['Address'];
            $language_s = $state['Language'];
            $comments_s = $state['Comments'];
            $gander_s = $state['Gender'];
            $country_s = $state['Country'];
            $state_s = $state['State'];
            $pincode_s = $state['Pincode'];
            $date_s = $state['D.O.B'];
            $image_s = $state['image'];     
            $output .= "<tr>
                            <td>". $sid_s."</td>  
                            <td>".$name_s."</td>
                            <td>".$lname_s."</td>
                            <td>".$email_s."</td>
                            <td>".$no_s."</td>
                            <td>".$address_s."</td>
                            <td>".$language_s ."</td>
                            <td>".$comments_s."</td>
                            <td>".$gander_s."</td>
                            <td>".$country_s."</td>
                            <td>".$state_s."</td>
                            <td>".$pincode_s."</td>
                            <td>".$date_s."</td>
                            <td><img src='img/".$image_s."' alt='' width='70px'></td>
                            <th class='styl'>
                            <button type='submit' class='edit_msg' style='border:none;background-color:transparent;'
                                onclick='editrec(" . $sid_s . ");'>
                                <i class='fa-regular fa-pen-to-square'></i>
                            </button>
                        </th>                   
                        <th class='styl'>
                            <button type='submit' class='delete_msg' style='border:none;background-color:transparent;'
                                onclick='deleterec(" . $sid_s . ");'>
                                <i class='fa-regular fa-trash-can' style='color:red;'></i>
                            </button>
                        </th>
                        </tr>";
        }
        echo $output;
    }
};  
// delete_row ========================================================================================    
$delete_row = function ($Did) use ($conn) {
    // print_r($Did);
    $sql5 = "";
    $sql5 .= "delete from input_data where Id =".$Did;
    $result = mysqli_query($conn,$sql5);
    if (mysqli_num_rows($result)) {
    };
};
// add update========================================================================================
$add = function () use ($conn) { 
    $fname=$lname=$email=$mobile_no=$address=$language=$comments=$gander=$pincode=$country=$state=$date="";
        $targetDir = "img/";  
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

        if ($hid_inp===""){
            $sql1 = "INSERT INTO input_data (First_Name,Last_NAME,Email,Mobile_NUMBER,Address,LANGUAGE,Comments,Gender,Country,State,Pincode,`D.O.B`,image) VALUES('$fname','$lname','$email', '$mobile_no','$address','$language','$comments','$gander','$country','$state','$pincode','$date','$fileName')"; 
            $result = mysqli_query($conn,$sql1);
                if ($result) {
                    echo json_encode("add");
                } 
        } 
        else{
            $sql1 = "UPDATE `input_data` SET `First_Name`='$fname', `Last_NAME`='$lname', `Email`='$email', `Mobile_NUMBER`=$mobile_no, `Address`='$address', `Language`='$language', `Comments`='$comments', `Gender`='$gander', `Country`='$country', `State`='$state', `Pincode`=$pincode, `D.O.B`='$date', `image`='$fileName' WHERE  `Id`=$hid_inp"; 
            $result = mysqli_query($conn,$sql1);
            if ($result) {
                echo json_encode("delete");
            } 

        }    
          
};

// edit ========================================================================================
$edit = function () use ($conn) { 
    $id=$_POST['id'];
    $sql ="";
    $sql .="select * from input_data where id=".$id;
    $result = mysqli_query($conn,$sql);
    $a = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $b = 'img/'.$a['image'];
    echo json_encode(array($a,$b));
    
};

// condition ========================================================================================    

if(isset($_POST['type']) && $_POST['type']== 'country'){
    $getCountry();
}
elseif(isset($_POST['type']) && $_POST['type']== 'state'){
    $getState();
}
elseif(isset($_POST['type']) && $_POST['type']== 'add'){
    $add();
}
elseif(isset($_POST['type']) && $_POST['type']== 'show'){
    $show_list();
}
elseif(isset($_POST['type']) && $_POST['type']== 'delete'){
    $Did=$_POST['id'];
    $delete_row($Did);
}
elseif(isset($_POST['type']) && $_POST['type']=='edit'){
    $edit();
} 
?>