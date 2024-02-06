function validateEmails(emails) {
  const emailArray = emails.split(',');
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  let isValid = true;
  for (const email of emailArray) {
    const trimmedEmail = email.trim();
    if (!emailPattern.test(trimmedEmail)) {
      isValid = false;
      break;
    }
  }
  const validationMessage = document.getElementById('emailValidationMessage');
  if (isValid) {
    validationMessage.textContent = 'All email addresses are valid.';
  } else {
    validationMessage.textContent = 'Please enter valid email addresses separated by commas.';
  }
}
function Frontendvalid() {
  var fname =
    document.forms.RegForm.fname.value;
  var email =
    document.forms.RegForm.email.value;
  var phone =
    document.forms.RegForm.phone.value;
  var paasword =
    document.forms.RegForm.paasword.value;
  var regEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/g;  //Javascript reGex for Email Validation.
  var regPhone = /^\d{10}$/;                                        // Javascript reGex for Phone Number validation.
  var regName = /^[a-z]+.*/i;                                  // Javascript reGex for Name validation

  if (fname == "" || regName.test(fname)) {
    window.alert("Please enter your name properly.");
    fname.focus();
    return false;
  }

  if (email == "" || !regEmail.test(email)) {
    window.alert("Please enter a valid e-mail address.");
    email.focus();
    return false;
  }

  if (paasword == "") {
    alert("Please enter your password");
    paasword.focus();
    return false;
  }

  if (paasword.length < 6) {
    alert("Password should be atleast 6 character long");
    paasword.focus();
    return false;

  }
  if (phone == "" || !regPhone.test(phone)) {
    alert("Please enter valid phone number.");
    phone.focus();
    return false;
  }

}
// =======================================================================================================================================


function client_validation() {
  let validation = true;
  // name ============================================================
  var name = document.getElementById("client_name").value;
  var nameErr = document.getElementById("client_nameErr");
  const minLength = 3;
  const maxLength = 40;
  if (name.trim() === "") {
    nameErr.innerHTML = "name required ";
    $('#client_nameErr').show().fadeOut(2000);
    return validation = false;
  }
  else if (name.length < minLength) {
    nameErr.innerHTML = " At least " + minLength + " characters long.";
    $('#client_nameErr').show().fadeOut(2000);
    return validation = false;

  } else if (name.length > maxLength) {
    nameErr.innerHTML = " Max  " + maxLength + " characters long.";
    $('#client_nameErr').show().fadeOut(2000);
    return validation = false;
  }
  else {
    nameErr.textContent = "";
  };

  // email============================================================
  var email = document.getElementById("client_email").value;
  var emailErr = document.getElementById("client_emailErr");
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var maxLength1 = 40;
  if (email.trim() === "") {
    emailErr.innerHTML = "Email is required";
    $('#client_emailErr').show().fadeOut(2000);
    return validation = false;
  } 
  else if (!emailRegex.test(email)) {
    emailErr.innerHTML = "Invalid email address!";
    $('#client_emailErr').show().fadeOut(2000);
    return validation = false;
  } 
  else if (email.length > maxLength1) {
    emailErr.innerHTML = " Max  " + maxLength1 + " characters long.";
    $('#client_emailErr').show().fadeOut(2000);
    return validation = false;
  } else {
    emailErr.textContent = "";
  }

 
  // mobile number ============================================================
  var mobile_no = document.getElementById("client_mobile_no").value;
  var noErr = document.getElementById("client_mobile_noErr");
  var expr = /^(0|91)?[7-9][0-9]{9}$/;
  if (mobile_no.trim() === "") {
    noErr.innerHTML = "Number is required!";
    $('#client_mobile_noErr').show().fadeOut(2000);
    return validation = false;
  }
  const numericValue = Number(mobile_no);
  if (isNaN(numericValue)) {
    noErr.innerHTML = "Invalid Number";
    $('#client_mobile_noErr').show().fadeOut(2000);
    return validation = false;
  }
  if (!expr.test(mobile_no)) {
    noErr.innerHTML = "Invalid Mobile Number.";
    $('#client_mobile_noErr').show().fadeOut(2000);
    return validation = false;
  }
  noErr.textContent = "";

  // Address ============================================================
  var address = document.getElementById("client_address").value;
  var addressErr = document.getElementById("client_addressErr");
  if (address.trim() === "") {
    addressErr.innerHTML = "Address is required";
    $('#noErr').show().fadeOut(2000);
    return validation = false;
  }
  addressErr.textContent = "";

  // state ============================================================
  var state = document.getElementById("client_state").value;
  var stateErr = document.getElementById("stateErr");

  if (state.trim() === "Select State") {
    stateErr.innerHTML = "State is required!";
    $('#stateErr').show().fadeOut(2000);
    return validation = false;
  }
  stateErr.textContent = "";

  // city ============================================================
  var city = document.getElementById("client_city").value;
  var citiesErr = document.getElementById("citiesErr");

  if (city.trim() === "Select Cities") {
    citiesErr.innerHTML = "State is required!";
    $('#citiesErr').show().fadeOut(2000);
    return validation = false;
  }
  citiesErr.textContent = "";
  return validation;
}

// const namePattern = regex = /^[a-zA-Z ]{2,30}$/;


// validate username
function validateUsername(username) {
  const minLength = 3;
  const maxLength = 40;
  if (username.trim() === "") {
    return "Name is required!";
  } else if (username.length < minLength) {
    return " At least " + minLength + " characters long.";
  } else if (username.length > maxLength) {
    return " Max  " + maxLength + " characters long.";
  } else {
    return "";
  }
}

//validate lastName
function validateLastName(lastName) {
  if (lastName.trim() === "") {
    return "Last Name is required!";
  } else {
    return "";
  }
}

// validate Email
function validateEmail(email) {
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var maxLength = 40;
  if (email.trim() === "") {
    return "Email is required!";
  } else if (!emailRegex.test(email)) {
    return "Invalid email address!";
  } else if (email.length > maxLength) {
    return " Max  " + maxLength + " characters long.";
  } else {
    return "";
  }
}

// Validate Number 
function validateNumber(number) {
  var expr = /^(0|91)?[7-9][0-9]{9}$/;

  if (number.trim() === "") {
    return "Number is required!";
  }
  const numericValue = Number(number);
  if (isNaN(numericValue)) {
    return "Invalid Number";
  }
  if (!expr.test(number)) {
    return "Invalid Mobile Number.";
  }
  return "";
}


// Validate Address
function validateAddress(address) {
  if (address.trim() === "") {
    return "Address is required";
  } else {
    return "";
  }
}

// Validate CheckBox
function validateCheckboxes(checkboxes) {
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked) {
      return true;
    }
  }
  return false;
}

// Validate Radio button
function validateRadioButtons(radioButtons) {
  for (var i = 0; i < radioButtons.length; i++) {
    if (radioButtons[i].checked) {
      return true;
    }
  }
  return false;
}

// validate Password
function validatePassword(password) {
  const minLength = 8;
  const regexUpperCase = /[A-Z]/;
  const regexNumber = /\d/;
  const regexSpecialChar = /[!@#$%^&*(),.?":{}|<>]/;

  if (password.trim() === "") {
    return "Password is required.";
  } else if (password.length < minLength) {
    return "Password" + minLength + " characters long.";
  } else if (!regexUpperCase.test(password)) {
    return "required one uppercase letter.";
  } else if (!regexNumber.test(password)) {
    return "required one number.";
  } else if (!regexSpecialChar.test(password)) {
    return "required one special character.";
  } else {
    return "";
  }
}

function validateState(state) {
  if (state.trim() === "Select State") {
    return "State is required!"
  } else {
    return "";
  }
}

function validateCity(city) {
  if (city.trim() === "Select City") {
    return "City is required!"
  } else {
    return "";
  }
}

function onlyNumberKey(evt) {
  var ASCIICode = evt.which ? evt.which : evt.keyCode;
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) return false;
  return true;
}

function onlyCharKey(event) {
  var key = event.key;
  if (key === "Backspace" || key === "Delete" || key === "Tab" || key === "Escape" || key === "Enter") {
    return true;
  }
  if (/^\d$/.test(key)) {
    event.preventDefault();
    return false;
  }

  return true;
}








// var obj={ 
//     falg:0,
//     toString:function(){
//         return this.falg++
//     }
// }

// if (obj==0 && obj==1){
//     console.log("hello")
// }
// else{console.log("error")}




function item_validation() {
  let validation = true;
  // item name============================================================
  var item = document.getElementById("item_name").value;
  var itemErr = document.getElementById("item_nameErr");
  const minLength = 3;
  const maxLength = 40;
  if (item.trim() === "") {
    itemErr.innerHTML = "name required ";
    $('#item_nameErr').show().fadeOut(2000);
    return validation = false;
  }
  else if (item.length < minLength) {
    itemErr.innerHTML = " At least " + minLength + " characters long.";
    $('#item_nameErr').show().fadeOut(2000);
    return validation = false;

  } else if (item.length > maxLength) {
    itemErr.innerHTML = " Max  " + maxLength + " characters long.";
    $('#item_nameErr').show().fadeOut(2000);
    return validation = false;
  }
  else {
    itemErr.textContent = "";
  };


    // item description ============================================================
    var item_d = document.getElementById("item_description").value;
    var item_dErr = document.getElementById("item_descriptionErr");
    if (item_d.trim() === "") {
      item_dErr.innerHTML = "Address is required";
      $('#item_descriptionErr').show().fadeOut(2000);
      return validation = false;
    }
    item_dErr.textContent = "";
  
  // item price ============================================================
  var item_price = document.getElementById("item_price").value;
  var item_priceErr = document.getElementById("item_priceErr");
  var expr = /^(0|91)?[7-9][0-9]{9}$/;
  if (item_price.trim() === "") {
    item_priceErr.innerHTML = "Number is required!";
    $('#item_priceErr').show().fadeOut(2000);
    return validation = false;
  }
  const numericValue = Number(item_price);
  if (isNaN(numericValue)) {
    item_priceErr.innerHTML = "Invalid Number";
    $('#item_priceErr').show().fadeOut(2000);
    return validation = false;
  }
  if (!expr.test(item_price)) {
    item_priceErr.innerHTML = "Invalid Mobile Number.";
    $('#item_priceErr').show().fadeOut(2000);
    return validation = false;
  }
  item_priceErr.textContent = "";

}

