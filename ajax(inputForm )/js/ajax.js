// get country data ajax

function gCountry() {
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            type: 'country'
        },
        dataType: 'html',
        success: function (data) {
            $("#show_country").html(data);
        }
    })
}
gCountry()
// get state data ajax
function gState() {
    var id =$("#show_country").val()
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            id:id,
            type: 'state'
        },
        dataType: 'html',
        success: function (data) {
            $("#show_state").html(data);
        }
    })
}

// show data ajax
function show_data() {
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            type: 'show'
        },
        dataType: 'html',
        success: function (data) {
            $("#list_data").html(data);
            // console.log(data)
        }
    })
}
show_data()
// validation
function validation() {
    let validation = true;
    // name ================================================
    fname = $("#fname").val();
    if (fname == '') {
        fnameErr.textContent = "  This feild is required ";
        fnameErr.style.color = "red";
        $("#fnameErr").show().fadeOut(5000);
        return validation = false;
    } else {
        fnameErr.textContent = "";
    };
     // email ================================================
     email = $("#email").val();
     if (email == '') {
         emailErr.textContent = " This feild is required ";
         emailErr.style.color = "red";
         $("#emailErr").show().fadeOut(5000);
         return validation = false;
     }
    //  else if((email.length)>=15 ){ 
    //      emailErr.textContent = " not correct";
    //      return validation = false;
    //  } 
     else {
         emailErr.textContent = "";
 
     };
    // pincode ================================================
    pincode = $("#pincode").val();
    if (pincode == '') {
        pincodeErr.textContent = "  This feild is required ";
        pincodeErr.style.color = "red";
        $("#pincodeErr").show().fadeOut(5000);
        return validation = false;
    }
    else if((pincode.length)>=7 ){ 
        pincodeErr.textContent = " not correct";
        return validation = false;
    } 
    else {
        pincodeErr.textContent = "";
    };
   
    return validation;
}
// insert data ajax
function insertrec() {
    var form = document.getElementById('form_id');
    var formData = new FormData(form);
    formData.append("type", 'add');
    let val = validation();
    if (val == true) {
        $.ajax({
            type: "POST",
            url: "main.php",
            dataType: 'html',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            success: function (data) {
                resetFormFields()
                alert('Data add successfully!!');
                $("#output").fadeOut(0.5);
                $("#hid_inp").val("")
                show_data()
                $("#home-tab").html("INSERT DATA")
            },
            error: function (e) {
                console.log("this is error", e)
            }
        })
    }
}
// reset form on submit
function resetFormFields() {
    var form = $('#form_id');
    var formInput = form.find('input,select,textarea')
    formInput.val('');
    $('#form_id input[type=radio]').prop('checked', false);
    $('#form_id input[type=checkbox]').prop('checked', false);
}
// delete data ajax
function deleterec(id) {
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            id: id,
            type: 'delete'
        },
        dataType: 'html',
        success: function (data) {
            alert("delete data Successfully!!");
            show_data();

        }
    })
}
// show record on edit form ajax
function editrec(id) {
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            id: id,
            type: 'edit'
        },
        dataType: 'json',
        success: function (data) {
            // console.log(data[1]);
            console.log(data[0]);
            if (data[1] == 'img/') {
                $("#output").css("display", "none");
            }
            else {

                $("#output").css("display", "");
            }
            $("#home-tab").addClass("active show")
            $("#home-tab").html("Update Data")
            $("#home").addClass("active show")
            $("#profile-tab").removeClass("active")
            $("#profile").removeClass("active")

            $("#hid_inp").val(data[0]['Id'])
            $("#fname").val(data[0]['First_Name'])
            $("#lname").val(data[0]['Last_NAME'])
            $("#email").val(data[0]['Email'])
            $("#mobile_no").val(data[0]['Mobile_NUMBER'])
            $("#address").val(data[0]['Address'])
            $("#comments1").val(data[0]['Comments'])

            $gdr = data[0]['Gender'];
            if ($gdr == 'Male') {
                $("#gender1").prop("checked", true)
            }
            else if ($gdr == 'Female') {
                $("#gender2").prop("checked", true)
            }
            else {
                $("#gender1").prop("checked", false)
                $("#gender2").prop("checked", false)
            }


            $("#show_country").val(data[0]['State']).trigger("change")
            $("#show_state").val(data[0]['State']).trigger("change")
            $("#pincode").val(data[0]['Pincode'])
            $("#date").val(data[0]['D.O.B'])
            $("#output").attr("src", data[1])

        }
    })
}


