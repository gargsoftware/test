
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Input Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/ajax.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<!-- =================================================================================================================================================== -->

<body>
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
                <?php 
                $nameErr=$lnameErr=$emailErr=$noErr=$addressErr=$languageErr=$commentsErr=$genderErr=$countryErr=$stateErr=$pincodeErr=$dateErr=$fileErr="";

                // if ($_SERVER['REQUEST_METHOD']=='POST'){
                //     if (empty($_POST['fname'])){
                //         $nameErr="required";
                //     }
                //     if (empty($_POST['lname'])){
                //         $lnameErr="required";
                //     }
                //     if (empty($_POST['email'])){
                //         $emailErr="required";
                //     }
                //     if (empty($_POST['mobile_no'])){
                //         $noErr="required";
                //     }
                //     if (empty($_POST['address'])){
                //         $addressErr="required";
                //     }
                //     if (empty($_POST['lang1'])){
                //         $languageErr="required";
                //     }
                //     if (empty($_POST['comments'])){
                //         $commentsErr="required";
                //     }
                //     if (empty($_POST['gender'])){
                //         $genderErr="required";
                //     }
                //     if (empty($_POST['country'])){
                //         $countryErr="required";
                //     }
                //     if (empty($_POST['state'])){
                //         $stateErr="required";
                //     }
                //     if (empty($_POST['pincode'])){
                //         $pincodeErr="required";
                //     }
                //     if (empty($_POST['date'])){
                //         $dateErr="required";
                //     }
                // }
                ?>
                <form action="<?php echo ($_SERVER['PHP_SELF'])?>" method="POST"
                    class="container mt-4 p-3 shadow border" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>
                                <div class="fname1">
                                    <input type="hidden" name="hid_inp">
                                    <label for="fname">First Name</label><span> *<?php echo $nameErr; ?></span><br>
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
                                    <label for="email">Email</label><span> *<?php echo $emailErr; ?></span><br>
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </td>
                            <td>
                                <div class="mobile_no">
                                    <label for="mobile_no">Mobile Number</label><span> *<?php echo $noErr; ?></span><br>
                                    <input type="text" class="form-control" name="mobile_no" id="mobile_no">
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
                                    <select name="country" id="country" class="form-control">
                                        <option value="">-select-</option>
                                        <?php 
                                                $sql="SELECT * FROM countries";
                                                $result=(mysqli_query($conn, $sql));
                                                while ($countries = mysqli_fetch_array($result,MYSQLI_ASSOC)):; 
                                                $con=$countries["id"]
                                            ?>
                                        <option value="<?php echo $con; ?>">
                                            <?php echo $countries["name"];?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="state">
                                    <label for="state">State</label> <span> *<?php echo $stateErr; ?></span><br>
                                    <select name="state" id="state" class="form-control">
                                        <option value="">--select--</option>
                                        <?php 
                                        $sql1="SELECT * FROM states WHERE country_id=101"; 
                                        $result1=(mysqli_query($conn, $sql1));
                                        while ($states = mysqli_fetch_array($result1,MYSQLI_ASSOC)):; ?>
                                        <option value="<?php echo $states["id"]; ?>">
                                            <?php echo $states["name"];?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="pincode">
                                    <label for="pincode">pincode</label><span> *<?php echo $pincodeErr; ?></span><br>
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
                                <div class="file">
                                    <label for="upload_file">Upload File</label><span>
                                        *<?php echo $fileErr; ?></span><br>
                                    <input type="file" class="form-control" name="file" id="upload_file">
                                    <div style="display:none;" id="img_id"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="reset" value="Reset" name="reset" class="btn btn border">
                                <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-success">
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
                    <tr class="styl">
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> ID</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> First Name</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Last Name</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Email</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Mobile Number</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Address</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Language</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Comments</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Gender</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Country</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> State</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Pincode</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> D.O.B</th>
                        <th class="styl"><i class="fa-solid fa-arrow-down fa-2xs myclass"></i> Image</th>
                        <th colspan="2">Button</th>
                        <!-- =============================================================================================== -->
                    </tr>
                    <?php 
                            $sql2="SELECT * FROM input_data"; 
                            // print_r($sql2);die;
                            $result2=(mysqli_query($conn, $sql2));
                            while ($states1 = mysqli_fetch_array($result2,MYSQLI_ASSOC)):; ?>
                    <tr>
                        <td class="styl"> <?php echo $states1["Id"];?> </td>
                        <td class="styl"> <?php echo $states1["First_Name"];?> </td>
                        <td class="styl"> <?php echo $states1["Last_NAME"];?> </td>
                        <td class="styl"> <?php echo $states1["Email"];?> </td>
                        <td class="styl"> <?php echo $states1["Mobile_NUMBER"];?> </td>
                        <td class="styl"> <?php echo $states1["Address"];?> </td>
                        <td class="styl"> <?php echo $states1["Language"];?> </td>
                        <td class="styl"> <?php echo $states1["Comments"];?> </td>
                        <td class="styl"> <?php echo $states1["Gender"];?> </td>
                        <td class="styl"> <?php echo $states1["Country"];?> </td>
                        <td class="styl"> <?php echo $states1["State"];?> </td>
                        <td class="styl"> <?php echo $states1["Pincode"];?> </td>
                        <td class="styl"> <?php echo $states1["D.O.B"];?> </td>
                        <td class="styl"> <img src="img/<?php echo $states1["image"]; ?>" alt="" width="70px"></td>
                        <th class="styl">
                            <button type="submit" class="edit_msg" style="border:none;background-color:transparent;"
                                onclick="editrec(<?php echo $states1['Id'];  ?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-pen" viewBox="0 0 16 16">
                                    <path
                                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                                </svg>
                            </button>
                        </th>
                        <th class="styl">
                            <button type="submit" class="delete_msg" style="border:none;background-color:transparent;"
                                onclick="deleterec(<?php echo $states1['Id'];  ?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="color:red;" width="16" height="16"
                                    fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path
                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                    <path
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                </svg>
                            </button>
                        </th>
                    </tr>
                    <?php endwhile;?>
                </table>
            </div>


        </div>
    </div>


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