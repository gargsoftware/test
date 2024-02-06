<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('common_F\head.php'); ?>


<body class="g-sidenav-show  bg-gray-100 ">

    <main class="main-content position-relative mt-1 border-radius-lg ">
        <!-- Navbar -->
        <?php $this->load->view('common_F\navbar.php'); ?>
        <!-- ==================================================== user master start ====================================================== -->
        <div class="container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" style="color:black;" data-bs-toggle="tab"
                        data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"
                        onclick="click_all_user()">All
                        User</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" style="color:black;" data-bs-toggle="tab"
                        data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                        aria-selected="false">Add User</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">

                <!-- all user start  -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form method="POST" id="search_form"
                        class="container mt-4 shadow border d-flex align-items-center justify-content-start"
                        enctype="multipart/form-data">
                        <table class="w-75 ">
                            <tr>
                                <td>
                                    <div class="search">
                                        <input type="hidden" name="">
                                        <label for="search_fname">User Name</label><span
                                            id="search_fname_err"></span><br>
                                        <input type="text" class="form-control" name="search_fname" id="search_fname">
                                    </div>
                                </td>
                                <td>
                                    <div class="search">
                                        <label for="search_email">Email</label><span id="search_emailErr"></span><br>
                                        <input type="email" class="form-control" name="search_email" id="search_email">
                                    </div>
                                </td>
                                <td>
                                    <div class="search">
                                        <label for="search_mobile_no">Mobile Number</label><span
                                            id="search_mobile_noErr"></span><br>
                                        <input type="text" class="form-control" name="search_mobile_no"
                                            id="search_mobile_no"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="pt-3">
                                    <button type="button" name="submit" class="btn btn search_btn_style" id="search_btn"
                                        onclick="show_user_master_data()"
                                        style="background-color:#ff7600;color:white;">Search</button>

                                    <!-- <input type="reset" name="reset_all" class="btn btn border search_btn_style"  onmouseout="reset_page()" value="Reset"> -->
                                    <button type="submit" class="btn btn border search_btn_style">Reset</button>

                                </td>
                            </tr>
                        </table>
                    </form>
                    <br>
                    <br>
                    <span>
                        <label for="recordsPerPage">Records per Page:</label>
                        <select id="recordsPerPage" class="border" name="recordsPerPage"
                            onchange="show_user_master_data()">
                            <option value='3'>3</option>
                            <option value='6'>6</option>
                            <option value='9'>9</option>
                        </select>
                    </span>
                    <div class="table-container shadow border table table-striped">

                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th class="table_h" onclick="sort_table('id');asdf();">S.No<i
                                            class="fa-solid fa-up-long d-none up-a" style="color: #ff7600;"></i><i
                                            class="fa-solid fa-down-long d-none down-a" style="color: #ff7600;"></i>
                                    </th>
                                    <th class="table_h" onclick="sort_table('name')">User Name</th>
                                    <th class="table_h" onclick="sort_table('email_id')">Email</th>
                                    <th class="table_h" onclick="sort_table('mobile_no')">Phone</th>
                                    <th class="table_h" colspan="2" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="show_data">
                            </tbody>
                        </table>
                        <div id="pagination"
                            style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                        </div>
                        <input type="hidden" name="hid_field" id="hid_field" value="asc">

                    </div>
                    <!-- show data  -->
                </div>
                <!-- all user end  -->
                <!-- add user start -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form method="POST"
                        class="container mt-4 shadow border d-flex align-items-center justify-content-center"
                        enctype="multipart/form-data" id="userMaster_form">
                        <table class="w-100">
                            <tr>
                                <td>
                                    <div class="fname1 add_style">
                                        <input type="hidden" name="h_input" id="h_input">
                                        <label for="user_name">User Name</label><span class="err">* &nbsp; <span
                                                id="user_nameErr"></span></span><br>
                                        <input type="text" class="form-control" name="user_name" id="user_name"
                                            maxlength="30">
                                    </div>
                                </td>
                                <td>
                                    <div class="email add_style">
                                        <label for="email">Email</label><span class="err">* &nbsp; <span
                                                id="emailErr_i"></span></span><br>
                                        <input type="email" class="form-control" name="email" id="email" maxlength="40">
                                    </div>
                                </td>
                                <td>
                                    <div class="mobile_no add_style">
                                        <label for="mobile_no">Mobile Number</label><span class="err">* &nbsp; <span
                                                id="noErr"></span></span><br>
                                        <input type="text" class="form-control" name="mobile_no" id="mobile_no"
                                            minlength="10" maxlength="10"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                                    </div>
                                </td>
                                <td>
                                    <div class="password add_style">
                                        <input type="hidden" name="hid_pwd" id="hid_pwd">
                                        <label for="pwd">Password</label><span class="err">* &nbsp; <span
                                                id="pwdErr"></span></span><br>
                                        <input type="password" class="form-control" name="pwd" id="pwd"
                                            placeholder="***********" maxlength="10">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="pt-3">
                                    <button type="button" name="submit" id="submit" onclick=insert_user_master();
                                        class="btn btn add_style_btn"
                                        style="background-color:#ff7600;color:white; ">Submit</button>

                                    <button type="reset" id="reset" class="btn btn border add_style_btn">Reset</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <!-- add user start -->
            </div>
        </div>
        <!-- ======================================================== user master end ================================================== -->
    </main>
    <?php  $this->load->view('common_F\script.php'); ?>

    <script>
        function click_all_user() {
            $("#reset").click();
            $("#h_input").val("");
            $("#profile-tab").html("Add User")
        }
    
        function validation() {
            let validation = true;
            // name ============================================================
            var name = document.getElementById("user_name").value;
            var nameErr = document.getElementById("user_nameErr");
            const namePattern = regex = /^[a-zA-Z ]{2,30}$/;
            // debugger
            if (name.trim() === '') {
                nameErr.innerHTML = "name required ";
            
                $('#user_nameErr').show().fadeOut(2000);
                return validation = false;
            } else if (!name.match(namePattern)) {
                nameErr.innerHTML = "invalid name";
                $('#user_nameErr').show().fadeOut(2000);
                return validation = false;
            } else {
                nameErr.textContent = "";
            };
        
            // email============================================================
            var email = document.getElementById("email").value;
            var emailErr = document.getElementById("emailErr_i");
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            var maxLength1 = 40;
            console.log(email)
            console.log(emailErr)
            if (email == '') {
                emailErr.innerHTML = "email required ";
                $('#emailErr_i').show().fadeOut(2000);
                return validation = false;
            } else if (!email.match(emailPattern)) {
                emailErr.innerHTML = "invalid Email";
                $('#emailErr_i').show().fadeOut(2000);
                return validation = false;
            } else if (email.length > maxLength1) {
                emailErr.innerHTML = " Max  " + maxLength1 + " characters long.";
                $('#emailErr_i').show().fadeOut(2000);
                return validation = false;
            } else {
                emailErr.textContent = "";
            };
            // mobile number ============================================================
            var mobile_no = document.getElementById("mobile_no").value;
            var noErr = document.getElementById("noErr");
            const noPattern = /^([7,9]{11}$)|(^[7-9][0-9]{9}$)/;
        
            if (mobile_no == '') {
                noErr.innerHTML = " mobile no required ";
                $('#noErr').show().fadeOut(2000);
                return validation = false;
            } else if (!mobile_no.match(noPattern)) {
                noErr.innerHTML = "invalid mobile no";
                $('#noErr').show().fadeOut(2000);
                return validation = false;
            } else {
                noErr.textContent = "";
            };
        
            //  password ===========================================================
            var password = document.getElementById("pwd").value;
            var pwdErr = document.getElementById("pwdErr");
            var max_len_pwd = 10;
            if (password.length > max_len_pwd) {
                pwdErr.innerHTML = " Max  " + max_len_pwd + " characters long.";
                $('#pwdErr').show().fadeOut(2000);
                return validation = false;
            } else {
                pwdErr.textContent = "";
            };
            return validation;
        }
    
        function resetFormFields() {
            var form = $('#userMaster_form');
            var formInput = form.find('input,select,textarea')
            formInput.val('');
            $("#h_input").val("")
            // $('#form_id input[type=radio]').prop('checked', false);
            // $('#form_id input[type=checkbox]').prop('checked', false);
        }
    
        function page() {
            resetFormFields()
            show_user_master_data()
            $("#home-tab").addClass("active show")
            $("#home").addClass("active show")
            $("#profile-tab").removeClass("active")
            $("#profile").removeClass("active")
        }
    
        function sort_table(fn) {
            var field_name = document.getElementById("hid_field").value;
            if (field_name == 'asc') {
                document.getElementById('hid_field').value = 'desc';
            } else {
                document.getElementById('hid_field').value = 'asc';
            }
            show_user_master_data(1, fn);
        }
        // =================================================================== on click function ===================================================================================
    
        function show_user_master_data(page = '1', column_name = 'id') {
        
            var search=$('#search_form').serializeArray()  
            let recordsPerPage = $("#recordsPerPage").val();
            let sorting = $('#hid_field').val();
        
            $.ajax({
                type: "POST",
                url: "<?=base_url('Welcome/show_user_list')?>",
                data: {
                    type: 'show',
                    page: page,
                    recordsPerPage: recordsPerPage,
                    search:search,
                
                    column_name: column_name,
                    sorting: sorting,
                
                },
                dataType: 'html',
                success: function(data) {
                    // console.log(data)
                    $("#show_data").html(data);
                    $("#pagination").html(data.pagination);
                }
            })
        };
        show_user_master_data()
    
        function insert_user_master() {
            var form = document.getElementById('userMaster_form');
            var formData = new FormData(form);
            formData.append("type", 'insert_userMaster');
            var val = validation()
            if (val == true) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('Welcome/insert')?>",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    success: function(data) {
                        if (data.statuscode == 150) {
                            toastr.warning(data.message);
                            // $("#emailErr_i").html(data.message)
                            $('#emailErr_i').show().fadeOut(2000);
                        
                        } else if (data.statuscode == 180) {
                            // $("#pwdErr").html(data.message)
                            // $('#pwdErr').show().fadeOut(2000);
                            toastr.warning(data.message);
                        } else if (data.statuscode == 200) {
                            page()
                            toastr.success(data.message);
                        } else if (data.statuscode == 250) {
                            page()
                            toastr.success(data.message);
                        
                            $("#profile-tab").html("Add User")
                        }
                    },
                    error: function(data) {
                        toastr.success("this is error", data.message)
                    }
                })
            }
        }
    
        function user_deleterec(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                customClass: 'swal-wide',
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('Welcome/delete')?>",
                        data: {
                            id: id,
                            type: 'delete'
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.statuscode == 200) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: data.message,
                                    icon: "error"
                                
                                });
                                show_user_master_data()
                            } else if (data.statuscode == 400) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: data.message,
                                    icon: "success"
                                
                                });
                                show_user_master_data()
                            }
                        }
                    })
                }
            });
        }
    
        function user_editrec(id) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('Welcome/edit')?>",
                data: {
                    id: id,
                    type: 'edit_userMaster_row'
                },
                dataType: 'json',
                success: function(data) {
                    $("#profile-tab").addClass("active show")
                    $("#profile-tab").html("Update User")
                    $("#profile").addClass("active show")
                    $("#home-tab").removeClass("active")
                    $("#home").removeClass("active")
                    console.log(data[0])
                
                    $("#h_input").val(data['id'])
                    $("#user_name").val(data['name'])
                    $("#email").val(data['email_id'])
                    $("#mobile_no").val(data['mobile_no'])
                    $("#hid_pwd").val(data['PASSWORD'])
                
                }
            })
        }
    
        function reset_page() {
            show_user_master_data()
        }
    </script>
</body>

</html>