<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>

        .overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #ff7600 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>
</head>

<body>
    <div class="overlay" id="spinner_parent">
        <div class="cv-spinner" id="spinner_child">
            <span class="spinner"></span>
        </div>
    </div>

</body>

<?php include 'script.php';?>

<script>
    jQuery(function($){
  $(document).ajaxSend(function() {
    $(".overlay").fadeIn(1000);
    $("#spinner_parent").removeClass("overlay");
    $("#spinner_child").removeClass("cv-spinner");
  });
		
    $.ajax({
      type: 'GET',
      success: function(){
        }
        })
  });	
</script>

</html>