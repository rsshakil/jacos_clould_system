 $(document).ready(function() {
     var success_class = 'alert-success';
     var error_class = 'alert-danger';
     $('#role_click').on('click', function(event) {
         event.preventDefault();
         var role_id = $("#role_id").val();
         if (role_id == 0) {
             alert("Please select a Role");
             return false;
         }
         var permission = [];
         $('#permission:checked').each(function() {
             permission.push($(this).val());
         });
         console.log(permission);
         // return false;
         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: 'assign_permission_role',
             type: 'POST',
             dataType: 'JSON',
             data: { role_id: role_id, permission: permission },
             success: function(response) {
                 alert(response.message);
             }
         })
     })
     $('#user_role_assign').on('click', function(event) {
             event.preventDefault();
             flash_message_hide()
             var user_id = $("#user_id_for_role").val();
             var message_id = 'assign_role_message';
             //  alert(user_id);
             //  return 0;
             if (user_id == 0) {
                 alert_message_id(message_id, error_class, Globals.user_select);
                 return false;
             }
             var roles = [];
             $('#role:checked').each(function() {
                 roles.push($(this).val());
             });
             // console.log(roles);
             // return false;
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: 'assign_model_role',
                 type: 'POST',
                 dataType: 'JSON',
                 data: { user_id: user_id, roles: roles },
                 success: function(response) {
                     alert_message_id(message_id, success_class, Globals.set_up);
                 }
             })
         })
         // Assign permission to Model/User
     $('#save_permission').on('click', function(event) {
             // $( document ).delegate( "save_permission", "click", function() {
             event.preventDefault();
             flash_message_hide();
             var user_id = $("#user_id_for_permission").val();
             var message_id = 'assign_permission_message';
             // alert(user_id);
             // return 0;
             if (user_id == 0) {
                 alert_message_id(message_id, error_class, Globals.user_select);
                 return false;
             }
             var permission = [];
             $('#permission:checked').each(function() {
                 permission.push($(this).val());
             });
             // console.log(user_id);
             // console.log(permission);
             // return false;
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: 'assign_permission_model',
                 type: 'POST',
                 dataType: 'JSON',
                 data: { user_id: user_id, permission: permission },
                 success: function(response) {
                     alert_message_id(message_id, success_class, response.message);
                     //   $("#div").load(" #div > *");
                 }
             })
         })
         // Permission Delete 
     function delete_permission_data(permission_id) {

         $.ajax({
             url: "permission_delete/" + permission_id,
             method: "GET",
             success: function(data) {
                 var msg = data.permission_name + ' ' + data.message;
                 alert_message_id('permission_main_message', success_class, msg);
                 $("#permission_list").load(" #permission_list > *");
             }
         });
     }

     $(document).on('click', '.permission_delete', function() {
         var permission_id_for_modal = $(this).attr("id");
         $('#delete_id').val('');
         $('#delete_id').val(permission_id_for_modal);

         $('#delete_type').val('');
         $('#delete_type').val('permission_delete');

         $('#delete_heading').html('');
         $('#delete_heading').html(Globals.permission_delete);
         var permission_name = $('#permission_name' + permission_id_for_modal).val();
         $('#delete_msg').html(Globals.permission_delete_confirm + ':' + permission_name);

         $('#delete_modal').modal('show');
     });




     //User Create
     $('#new_user_save').on('click', function(event) {
             event.preventDefault();
             var message_id = 'user_message';
             // alert("Hi");
             // return false;
             var name = $("#name").val();
             var email = $("#email").val();
             var password = $("#password").val();
             var roles = [];
             $('#role').each(function() {
                 roles.push($(this).val());
             });

             var permissions = [];
             $('.permissions:checked').each(function() {
                 permissions.push($(this).val());
             });
             //  console.log(permissions);
             //  return false;
             var password_confirm = $("#password-confirm").val();
             if (name == "" || email == "" || password == "" || password_confirm == "") {
                 alert_message_id(message_id, error_class, Globals.all_fields_required);
                 return false;
             }
             if (password != password_confirm) {
                 alert_message_id(message_id, error_class, Globals.password_not_match);
                 return false;
             }

             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: 'user_create',
                 type: 'POST',
                 dataType: 'JSON',
                 data: { name: name, email: email, password: password, roles: roles, permissions: permissions },
                 success: function(response) {
                     var message_id = 'user_message';
                     if (response.message == 'success') {
                         $("#div").load(" #div > *");
                         $('#new_user_modal').modal('hide');
                         alert_message_id('user_main_message', success_class, Globals.user_created);
                     } else if (response.message == 'invalid') {
                         alert_message_id(message_id, error_class, Globals.email_already_database);
                     } else if (response.message == 'name_required') {
                         alert_message_id(message_id, error_class, Globals.name_length);
                     } else if (response.message == 'email_required') {
                         alert_message_id(message_id, error_class, Globals.email_length);
                     } else if (response.message == 'pass_required') {
                         alert_message_id(message_id, error_class, Globals.password_length);
                     }
                 }
             })
         })
         //User Create End


     // Add new User
     $(document).on('click', '#create_new', function() {
         $('#user_message').html('');
         $("#user_create")[0].reset();
         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "Post",
             url: "get_roles",
             dataType: "JSON",
             success: function(response) {
                 var html = '';
                 html += '<option value="" data-toggle="tooltip" data-html="true" data-placement="top" title="No permission">No role</option>';
                 for (var i = 0; i < response.length; i++) {
                     html += '<option value="' + response[i]['role_id'] + '" data-toggle="tooltip" data-html="true" data-placement="top" title="' + response[i]['permissions'] + '">' + response[i]['role_name'] + '</option>';
                 }
                 $('#role_for_user').html(html);

             }
         });
         get_permission_for_user()
         $('#new_user_modal').modal('show');
     });
     // Add new user end

     //User Delete

     function delete_user_data(user_id) {
         $.ajax({
             url: "user_delete/" + user_id,
             method: "GET",
             success: function(data) {
                 var msg = data.user_name + ' ' + data.message;
                 alert_message_id('user_main_message', success_class, msg);
                 $("#div").load(" #div > *");
             }
         });
     }

     $(document).on('click', '.user_delete', function() {
         var user_id_for_modal = $(this).attr("id");
         $('#delete_id').val('');
         $('#delete_id').val(user_id_for_modal);
         $('#delete_type').val('');
         $('#delete_type').val('user_delete');

         $('#delete_heading').html('');
         $('#delete_heading').html(Globals.user_delete);
         var user_name = $('#user_name' + user_id_for_modal).val();
         // alert(user_name);
         // return 0;
         $('#delete_msg').html(Globals.user_delete_confirm + ': ' + user_name);

         $('#delete_modal').modal('show');
     });



     //role Delete
     function delete_role_data(role_id) {
         var role_delete_url = Globals.base_url + '/role_delete/';
         $.ajax({
             url: role_delete_url + role_id,
             method: "GET",
             success: function(response) {
                 var msg = response.role_name + ' ' + response.message;
                 alert_message_id('role_main_message', response.class_name, msg);
                 $("#role_list").load(" #role_list > *");
             }
         });
     }

     $(document).on('click', '.role_delete', function() {
         var role_id_for_modal = $(this).attr("id");
         // alert(role_id_for_modal);
         // return 0;
         $('#delete_id').val('');
         $('#delete_id').val(role_id_for_modal);

         $('#delete_type').val('');
         $('#delete_type').val('role_delete');

         $('#delete_heading').html('');
         $('#delete_heading').html(Globals.role_delete);
         var role_name = $('#role_name' + role_id_for_modal).val();
         $('#delete_msg').html(Globals.role_delete_confirm + ':' + role_name);

         $('#delete_modal').modal('show');
     });

     $(document).on('click', '#delete_from_modal', function() {
         var delete_id = $('#delete_id').val();
         var delete_type = $('#delete_type').val();
         if (delete_type == 'role_delete') {
             delete_role_data(delete_id);
         } else if (delete_type == 'permission_delete') {
             delete_permission_data(delete_id);
         } else if (delete_type == 'user_delete') {
             delete_user_data(delete_id);
         }
         $('#delete_modal').modal('hide');
     })

     //role Delete End


     // User Update

     $('#update_user').on('submit', function(event) {
         event.preventDefault();
         // alert("Hi");
         // return false;
         flash_message_hide()
         var message_id = 'user_update_message';
         var fileInput = $("#image").val();

         if (fileInput != "") {
             var ext = checkFileExt(fileInput);
             if (ext == "jpg" || ext == "jpeg" || ext == "png") {} else {
                 alert_message_id(message_id, error_class, Globals.select_image);
                 return false;
             }
             var file_size = $("#image")[0].files[0].size / 1024 / 1024;
             if (file_size >= 1) {
                 alert_message_id(message_id, error_class, Globals.select_image);
                 return false;
             }

         }
         // var APP_URL = {!! json_encode(url('/')) !!}
         var APP_URL = Globals.base_url + 'update_user';
         $.ajax({
             method: 'POST',
             url: APP_URL,
             data: new FormData(this),
             dataType: 'JSON',
             processData: false,
             cache: false,
             contentType: false,
             success: function(response) {
                 //  console.log(response);
                 if (response.status_code == 1) {
                     location.reload();
                 } else {
                     alert_message_id(message_id, response.class_name, response.message);
                 }
             }
         }).fail(function() {
             alert_message_id(message_id, error_class, Globals.check_internet_connection);
         });
     });

     // User Update end

     // Password Change
     $(document).on('click', '.password_change', function() {
         var user_id = $(this).attr("id");
         // alert(user_id);
         // return false;
         $('#change_password_message').html('');
         $('#change_pass_user_id').val(user_id);
         $('#new_password').val('');
         $('#new_password_confirm').val('');

         $('#change_password_modal').modal('show');
     });


     $('#change_password_save').on('click', function(event) {
             event.preventDefault();

             var user_id = $("#change_pass_user_id").val();
             var message_id = 'change_password_message';
             // alert(user_id);
             // return false;
             var password = $("#new_password").val();
             var password_confirm = $("#new_password_confirm").val();
             if (password == "" || password_confirm == "") {
                 alert_message_id(message_id, error_class, Globals.all_fields_required);
                 //  $('#change_password_message').html('<h3 class="text-danger">' + Globals.all_fields_required + '</h3>');
                 return false;
             }
             if (password != password_confirm) {
                 alert_message_id(message_id, error_class, Globals.password_not_match);
                 //  $('#change_password_message').html('<h3 class="text-danger">' + Globals.password_not_match + '</h3>');
                 return false;
             }

             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: 'change_password',
                 type: 'POST',
                 dataType: 'JSON',
                 data: { user_id: user_id, password: password },
                 success: function(response) {
                     // console.log(response.message);
                     // return false;
                     if (response.message == 'success') {
                         alert_message_id('user_main_message', success_class, Globals.password_changed);
                         $("#div").load(" #div > *");
                         $('#change_password_modal').modal('hide');
                     } else if (response.message == 'invalid') {
                         alert_message_id(message_id, error_class, Globals.password_length);
                     }
                 }
             })
         })
         // Password change End

     //Permission show by user
     $(document).on('click', '.permission_view', function() {
         $('#all_permission_show').html('');
         var user_id = $(this).attr('id');
         var user_name = $('#user_name' + user_id).val();
         $('#permission_modal_heading').html('Permissions for the user: ' + user_name);
         permission_search(user_id);
         // alert(user_name);
         // return false;
         $('#permission_show_modal').modal('show');
     });

     function permission_search(user_id) {
         var permission_search_url = Globals.base_url + 'permission_search';
         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "post",
             url: permission_search_url,
             data: { user_id: user_id },
             dataType: "JSON",
             success: function(response) {
                 $('#total_permission').html('<h4>Total Permissions: ' + response.permission_count + '</h4>');
                 if (response.permission_implosed == '') {
                     $('#all_permission_show').html('This user has no permission');
                 } else {
                     $('#all_permission_show').html(response.permission_implosed);
                 }
             }
         });

     }
     $(document).on('click', '#single_permission_name', function(e) {
         e.preventDefault();
     });
     //Permission show by user end

     // changed password by user
     $(document).on('click', '.pc', function() {
         var user_id = $(this).attr("id");
         // alert(user_id);
         // return false;
         $('#user_change_password_message').html('');
         $('#user_id').val(user_id);
         $('#user_new_password').val('');
         $('#user_new_password_confirm').val('');

         $('#user_change_password_modal').modal('show');
     });



     $('#user_change_password_save').on('click', function(event) {
         event.preventDefault();

         var user_id = $("#user_id").val();
         var message_id = 'user_change_password_message';
         //  alert(user_id);
         //  return false;
         var password = $("#user_new_password").val();
         var password_confirm = $("#user_new_password_confirm").val();

         if (password == "" || password_confirm == "") {
             alert_message_id(message_id, error_class, Globals.all_fields_required);
             return false;
         }
         if (password != password_confirm) {
             alert_message_id(message_id, error_class, Globals.password_not_match);
             return false;
         }
         //  alert(password);
         //  return false;
         var change_password_url = Globals.base_url + 'change_password';
         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: change_password_url,
             type: 'POST',
             dataType: 'JSON',
             data: { user_id: user_id, password: password },
             success: function(response) {
                 //  console.log(response);
                 //  return false;
                 if (response.message == 'success') {
                     location.reload();
                     //  alert_message_id(message_id, success_class, Globals.password_changed);
                     //  $("#div").load(" #div > *");
                 } else if (response.message == 'invalid') {
                     alert_message_id(message_id, error_class, Globals.password_length);
                 }
             }
         })
     })

     // changed password by user end 
     //    Select2 Use
     //  $('.js-example-basic-multiple').select2();
     $("#user_id_for_permission").change(function(e) {
         e.preventDefault();
         var user_id = $(this).val();
         if (user_id == 0) {
             $('#permissions').html(Globals.no_selected_user);
             $('#previus_permissions').html(Globals.no_selected_user);
             return 0;
         }
         var get_permission_model = Globals.base_url + 'get_permission_model';
         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "post",
             url: get_permission_model,
             data: { user_id: user_id },
             dataType: "JSON",
             success: function(response) {
                 if (response.not_matches.length == 0) {
                     $('#permissions').html(Globals.no_pemission_left);
                 } else {
                     var html = '';
                     var not_match_permissions = response.not_matches;
                     for (var i = 0; i < not_match_permissions.length; i++) {
                         html += '<div class="checkbox">';
                         html += '<label>';
                         html += '<input type="checkbox" name="permission[]" id="permission" value="' + not_match_permissions[i].id + '" ' + ($.inArray(not_match_permissions[i].id, response.permissions_exist_id) == -1 ? '' : 'checked') + '>' + not_match_permissions[i]['name'];
                         html += '</label>';
                         html += '</div>';
                     }
                     $('#permissions').html(html);
                     // $('#save_button').html('');
                     var permissions_html = '';
                     permissions_html += '<ol>';
                     var permissions_array = response.all_permissions_for_user_array;
                     for (var j = 0; j < permissions_array.length; j++) {
                         permissions_html += '<a href="" id="single_permission_name">';
                         permissions_html += '<li>';
                         permissions_html += permissions_array[j];
                         permissions_html += '</li>';
                         permissions_html += '</a>';
                     }
                     permissions_html += '</ol>';
                     // console.log(response.all_permissions_for_user_array);
                     $('#previus_permissions').html(permissions_html);
                 }
             }
         });
     });
     $("#user_id_for_role").change(function(e) {
         e.preventDefault();
         var user_id = $(this).val();
         show_role(user_id);
     });

     function show_role(user_id) {
         $('#role').load('get_role/' + user_id);
     }
     $('#role_for_user').on('click', function() {
         var role_id = $(this).val();
         //  console.log(role_id);
         if (role_id.length <= 1 && role_id[0] == "") {
             role_id = null;
             //  console.log(role_id);
             //  console.log("Null");
         }
         get_permission_for_user(role_id)
     });



 });