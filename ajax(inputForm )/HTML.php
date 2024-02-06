<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Input Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <?php $nameErr=$lnameErr=$emailErr=$noErr=$addressErr=$languageErr=$commentsErr=$genderErr=$countryErr=$stateErr=$pincodeErr=$dateErr=$fileErr= "";

                if ($_SERVER['REQUEST_METHOD']=='POST'){
                    if (empty($_POST['fname'])){
                        $nameErr="required";
                    }
                    if (empty($_POST['lname'])){
                        $lnameErr="required";
                    }
                    if (empty($_POST['email'])){
                        $emailErr="required";
                    }
                    if (empty($_POST['mobile_no'])){
                        $noErr="required";
                    }
                    if (empty($_POST['address'])){
                        $addressErr="required";
                    }
                    if (empty($_POST['lang1'])){
                        $languageErr="required";
                    }
                    if (empty($_POST['comments'])){
                        $commentsErr="required";
                    }
                    if (empty($_POST['gender'])){
                        $genderErr="required";
                    }
                    if (empty($_POST['country'])){
                        $countryErr="required";
                    }
                    if (empty($_POST['state'])){
                        $stateErr="required";
                    }
                    if (empty($_POST['pincode'])){
                        $pincodeErr="required";
                    }
                    if (empty($_POST['date'])){
                        $dateErr="required";
                    }
                    // if (empty($_POST['file'])){
                    //     $fileErr="required";
                    // }

                        // if(in_array($fileType, $allowTypes)){ 
                //     if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                //     }
                // }
                }
                ?>
    <div class="m-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">INSERT DATA</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">LIST</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- insert data secation start -->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form method="POST" class="container mt-4 p-3 shadow border" enctype="multipart/form-data" id="form_id">
                    <table>
                        <tr>
                            <td>
                                <div class="fname1">
                                    <input type="hidden" name="hid_inp" id="hid_inp">
                                    <label for="fname">First Name</label><span id="fnameErr"> *<?php echo $nameErr; ?></span><br>
                                    <input type="text" class="form-control" name="fname" id="fname">
                                </div>
                            </td>
                            <td>
                                <div class="lname1">
                                    <label for="lname">Last Name</label><span> *<?php echo $lnameErr; ?></span><br>
                                    <input type="text" class="form-control" name="lname" id="lname">
                                </div>
                            </td>
                            <td>
                                <div class="email">
                                    <label for="email">Email</label><span id="emailErr"> *<?php echo $emailErr; ?></span><br>
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </td>
                            <td>
                                <div class="mobile_no">
                                    <label for="mobile_no">Mobile Number</label><span> *<?php echo $noErr; ?></span><br>
                                    <input type="number" class="form-control" name="mobile_no" id="mobile_no">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="address">
                                    <label for="address">Address</label><span> *<?php echo $addressErr; ?></span><br>
                                    <input type="text" class="form-control" name="address" id="address">
                                </div>
                            </td>
                            <td>
                                <div class="language">
                                    <label for="address">Language</label><span> *<?php echo $languageErr; ?></span><br>
                                    <input type="checkbox" id="lang_1" name="lang1[]" value="hindi">
                                    <label for="lang_1">Hindi</label><br>

                                    <input type="checkbox" id="lang2" name="lang1[]" value="english">
                                    <label for="lang2">English</label><br>

                                    <input type="checkbox" id="lang3">
                                    <label for="lang3">Other</label><br>
                                    <textarea id="other_lan" cols="20" rows="1" name="lang1[]"
                                        class="form-control"></textarea>
                                </div>
                            </td>
                            <td>
                                <div class="comments">
                                    <label for="comments1">Comments</label><span>
                                        *<?php echo $commentsErr; ?></span><br>
                                    <textarea name="comments" id="comments1" cols="30" rows="2"
                                        class="form-control"></textarea>
                                    <br>
                                </div>
                            </td>
                            <td>
                                <div class="gender_1 ">
                                    <label for="gender1">Gender</label><span> *<?php echo $genderErr; ?></span><br>

                                    <input type="radio" id="gender1" name="gender" value="Male">
                                    <label for="gender1">Male</label><br>

                                    <input type="radio" id="gender2" name="gender" value="Female">
                                    <label for="gender2">Female</label><br>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="country">
                                    <label for="country">Country</label><span> *<?php echo $countryErr; ?></span><br>
                                    <select name="country" id="show_country" onchange="gState()" class="form-control">
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="state">
                                    <label for="state">State</label> <span> *<?php echo $stateErr; ?></span><br>
                                    <select name="state" id="show_state" class="form-control">
                                    </select>
                                </div>
                            </td>
                        
                            <td>
                                <div class="pincode">
                                    <label for="pincode">pincode</label><span id="pincodeErr"> *<?php echo $pincodeErr; ?></span><br>
                                    <input type="text" class="form-control" name="pincode" id="pincode">
                                </div>
                            </td>
                            <td>
                                <div class="date">
                                    <label for="date">D.O.B</label><span> *<?php echo $dateErr; ?></span><br>
                                    <input type="date" class="form-control" name="date" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <!-- <div class="file">
                                    <label for="upload_file">Upload File</label>
                                    <input type="file" class="form-control" name="file" id="upload_file">
                                    <div style="display:none;" id="img_id"></div>
                                </div> -->
                                <div class="file">
                                    <label for="path">Upload file</label>
                                    <input type="file" class="form-control" accept="image/*" id="upload_file" name="file"
                                        onchange="loadimage(event)">
                                </div>

                            </td>
                            <td>
                                    <div id="img">
                                        <img src= "" style="width: 80px;margin: 13px 0px 0px 10px; display:none;"id="output" alt="preview">
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="reset" id="reset" class="btn btn border">Reset</button>
                                <button type="button" name="submit" id="submit" onclick=insertrec(); class="btn btn-success">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <!-- insert data secation end -->
            <!-- Show data secation Start -->
            <div class="tab-pane fade container-flud mt-4 p-2 shadow border" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">
                <table class="table_style">
                    <thead>
                        <tr class="styl">
                            <th class="styl">ID</th>
                            <th class="styl">First Name</th>
                            <th class="styl">Last Name</th>
                            <th class="styl">Email</th>
                            <th class="styl">Mobile Number</th>
                            <th class="styl">Address</th>
                            <th class="styl">Language</th>
                            <th class="styl">Comments</th>
                            <th class="styl">Gender</th>
                            <th class="styl">Country</th>
                            <th class="styl">State</th>
                            <th class="styl">Pincode</th>
                            <th class="styl">D.O.B</th>
                            <th class="styl">Image</th>
                            <th colspan="2">Button</th>
                        </tr>
                    </thead>
                    <tbody id="list_data" class="styl">
                        
                    </tbody>
                   
                </table>
            </div>


        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/scripts.js"></script>
    <script src="js/ajax.js"></script>
</body>

</html>

<!-- 
<th><a id="id" data-order="'.$order.'" href="#" onclick="find(this)">
        <span id="sa" class="a1"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i></span>Id</a>
    </th>
    <th><a id="name" data-order="'.$order.'" href="#" onclick="find(this)">
        <span id="saa" class="a2"><i class="fa-solid fa-arrow-up fa-2xs class"></i></span>Id</a>
    </th> -->


<!-- 
 -->