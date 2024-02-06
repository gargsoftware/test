
// span variable
var nameError = document.getElementById("name_error")
var emailError = document.getElementById("email_error")
var addressError = document.getElementById("address_error")
var noError = document.getElementById("number_error")
var submit_error = document.getElementById("submit_error")

// validation
var name_valid = /^(?=.*[A-z])[A-Za-z ]{5,}$/
var email_valid = /^[a-z_]{3,}[0-9]{2,}@[a-z]{2,}.[a-z]{2,}.[a-z]{0,}$/
var address_valid = /^[A-Za-z0-9.@#]{1,}$/
var number_valid = /^[789]{1}[0-9]{9}$/

// input Value  
var inp_name = document.getElementById("inp_name")
var inp_mail = document.getElementById("inp_mail")
var inp_add = document.getElementById("inp_add")
var inp_num = document.getElementById("inp_num")

function validationName() {
    if (name_valid.test(inp_name.value)) {
        nameError.innerHTML = "<img src='../svg/download.png' width='15px' >"
        return true
    }
    else if (inp_name.value == "") {
        nameError.innerHTML = "required"
        return false
    }
    else {
        nameError.innerHTML = "wrong name"
        return false

    }
}
// var v=validationName()
// if (v != true) {
//     submit_error.innerHTML = "hello name"
// }
// else{
//     submit_error.innerHTML = "<img src='../svg/download.png' width='15px' >"
// }
function validationEmail() {
    if (email_valid.test(inp_mail.value)) {
        emailError.innerHTML = "<img src='../svg/download.png' width='15px' >"
        return true
    }
    else if (inp_mail.value == "") {
        emailError.innerHTML = "required"
        return false
    }
    else {
        emailError.innerHTML = "wrong email"
        return false
    }
}

function validationAddress() {
    if (address_valid.test(inp_add.value)) {
        addressError.innerHTML = "<img src='../svg/download.png' width='15px' >"
        return true
    }
    else if (inp_add.value=="") {
        addressError.innerHTML = "required"
        return false
    }
    else{
        addressError.innerHTML = "wrong adderss"
        return false
        }

}
function validationNumber(){
  if(number_valid.test(inp_num.value)){
    noError.innerHTML= "<img src='../svg/download.png' width='15px'>"
    return true
}
else if(inp_num.value==""){
      noError.innerHTML= "required"
      return false
  }
else{
      noError.innerHTML= "Wrong mobile no"
      return false
  }
}










//validation========================================================
function validation() {

    let validation = true;

    fname = $("#fname").val();
    if (fname == '') {
        fnameErr.textContent = "  This feild is required ";
        fnameErr.style.color = "red";
        $("#fnameErr").show().fadeOut(3000);
        return validation = false;
    } else {
        fnameErr.textContent = "";
    };

    lname = $("#lname").val();
    if (lname == '') {
        lnameErr.textContent = "  This feild is required ";
        lnameErr.style.color = "red";
        $("#lnameErr").show().fadeOut(3000);
        return validation = false;
    } else {
        lnameErr.textContent = "";
    };

    Dstart = $("#Dstart").val();
    if (Dstart == '') {
        DstartErr.textContent = "  This feild is required ";
        DstartErr.style.color = "red";
        $("#DstartErr").show().fadeOut(3000);
        return validation = false;
    } else {
        DstartErr.textContent = "";
    };

    email = $("#email").val();
    if (email == '') {
        emailErr.textContent = "  This feild is required ";
        emailErr.style.color = "red";
        $("#emailErr").show().fadeOut(3000);
        return validation = false;
    } else {
        emailErr.textContent = "";
    };

    phoneno = $("#phoneno").val();
    if (phoneno == '') {
        phonenoErr.textContent = "  This feild is required ";
        phonenoErr.style.color = "red";
        $("#phonenoErr").show().fadeOut(3000);
        return validation = false;
    } else {
        phonenoErr.textContent = "";
    };

    address = $("#address").val();
    if (phoneno == '') {
        addressErr.textContent = "  This feild is required ";
        addressErr.style.color = "red";
        $("#addressErr").show().fadeOut(3000);
        return validation = false;
    } else {
        addressErr.textContent = "";
    };

    zip = $("#zip").val();
    if (zip == '') {
        zipErr.textContent = "  This feild is required ";
        zipErr.style.color = "red";
        $("#zipErr").show().fadeOut(3000);
        return validation = false;
    } else {
        zipErr.textContent = "";
    };
    return validation;
};