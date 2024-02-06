<!DOCTYPE html>
<html>

<head>
    <title>Email verification form in php using otp</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            font-size: 0.9em;
            color: #212121;
            font-family: Arial;
        }
    
        .container {
            width: 350px;
            margin: 50px auto;
            box-sizing: border-box;
        }
    
    
        #mobile-number-verification {
            border: #E0E0E0 1px solid;
            border-radius: 3px;
            padding: 30px;
            text-align: center;
            background: #eee;
        }
    
        #message1 {}
    
        #message2 {}
    
        .mobile-heading {
            font-size: 1.5em;
            margin-bottom: 30px;
        }
    
        .mobile-row {
            margin-bottom: 30px;
        }
    
        .mobile-input {
            padding: 10px 20px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 3px;
            border: #E0E0E0 1px solid;
        }
    
        .mobile-input:focus {
            background: lightgreen;
        }
    
        .mobileSubmit {
            background: forestgreen;
            padding: 8px 10px;
            border: green 1px solid;
            border-radius: 5px;
            width: 100%;
            color: #eee;
        }
    
        .mobileSubmit:hover {
            background: green;
            padding: 8px 10px;
            border: green 1px solid;
            border-radius: 5px;
            width: 100%;
            color: white;
        }
    
    
        .err {
            color: #483333;
            padding: 10px;
            background: #ffbcbc;
            border: #efb0b0 1px solid;
            border-radius: 3px;
            margin: 0 auto;
            margin-bottom: 20px;
            width: 350px;
            display: none;
            box-sizing: border-box;
        }
    
        .success {
            color: #483333;
            padding: 10px 20px;
            background: #cff9b5;
            border: #bce4a3 1px solid;
            border-radius: 3px;
            margin: 0 auto;
            margin-bottom: 20px;
            width: 350px;
            display: none;
            box-sizing: border-box;
        }
    
        .btnVerify {
            background: #4CAF50;
            padding: 8px 20px;
            border: #449e48 1px solid;
            border-radius: 3px;
            width: 100%;
            color: #FFF;
        }
    
        #loading-image {
            display: none;
        }
    </style>

</head>

<body>

    <div class="container w3-card">
        <div class="err"></div>
        <form id="mobile-number-verification">
            <div class="mobile-heading">Email Verification</div>
            <div class="mobile-row">
                <input type="text" id="name" class="mobile-input" placeholder="Enter your name">
                <br>
                <div id="message1"></div>
                <br>
                <input type="email" id="email" class="mobile-input" placeholder="Enter your email-id">
                <div id="message2"></div>
            </div>
            <div id="loading-image"><img src="/image/ajax-loader.gif" alt="ajax loader"></div>
            <input type="button" class="mobileSubmit" id="enter" disabled="true" value="Get OTP" onClick="getOTP();">
        </form>
        <script>
        $('#email').on('keyup', function() {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            var email = $("#email").val();
            if (email.match(mailformat)) {
                $('#message2').html('valid').css('color', 'green');
                $("#enter").prop('disabled', false);
            } else
                $('#message2').html('Invalid Email').css('color', 'red');

        });
        </script>
    </div>
</body>

</html>