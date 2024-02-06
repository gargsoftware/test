<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <title>Hello, world!</title>

</head>

<body>
    <?php $nameErr=$lnameErr=$emailErr=$noErr=$addressErr=$languageErr=$commentsErr=$genderErr=$countryErr=$stateErr=$pincodeErr=$dateErr=$fileErr="";?>
    <h4 style="text-align:center;">Update form</h4>
    <form action="<?php echo ($_SERVER['PHP_SELF'])?>" method="POST" class="container mt-4 p-3 shadow border"
        enctype="multipart/form-data">
        <table>
            <tr>
                <td>
                    <div class="fname1">
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
                        <textarea id="other_lan" cols="20" rows="1" name="lang1[]" class="form-control"></textarea>
                    </div>
                </td>
                <td>
                    <div class="comments">
                        <label for="comments1">Comments</label><span>
                            *<?php echo $commentsErr; ?></span><br>
                        <textarea name="comments" id="comments1" cols="30" rows="2" class="form-control"></textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
    $(document).ready(function() {
        $("#lang3").click(function() {
            $("#other_lan").toggle();
        });
    });
    </script>
</body>

</html>