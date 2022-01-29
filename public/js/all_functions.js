function alert_message_id(message_id, class_name, message) {
    $('#' + message_id).html('');
    $('#' + message_id).html('<div class="alert ' + class_name + ' alert-dismissible fade show mb-0 text-center" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + message + '</div>');
}

function alert_message_class(message_class, class_name, message) {
    $('.' + message_class).html('');
    $('.' + message_class).html('<div class="alert ' + class_name + ' alert-dismissible fade show mb-0 text-center" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + message + '</div>');
}

function url_search() {
    var currentURL = window.location.href;
    var url_array = currentURL.split("/");
    var url_last_element = $(url_array).last()[0];
    return url_last_element;
}

function checkFileExt(filename) {
    filename = filename.toLowerCase();
    return filename.split('.').pop();
}

function flash_message_hide() {
    $('.alert_msg').removeClass('show');
    $('.alert_msg').addClass('hide');
}

function get_permission_for_user(role_id = null) {
    $('#permission_for_user').html('Loading.....');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "get_permission_for_user",
        data: { role_id: role_id },
        dataType: "JSON",
        success: function(response) {
            // console.log(response.length);
            // console.log(response);
            // console.log(response.permissions.length);
            var p_html = '';
            var permission_array = response.permissions;
            if (permission_array.length == 0) {
                p_html += 'You have no permission list';
            } else {
                for (let i = 0; i < permission_array.length; i++) {
                    var checked = $.inArray(permission_array[i].id, response.permission_for_role);
                    p_html += '<div class="form-check form-check-inline">';
                    p_html += '<input class="form-check-input permissions" type="checkbox" id="permission_' + permission_array[i].id + '" value="' + permission_array[i].id + '"' + (checked >= 0 ? 'checked disabled' : '') + '>';
                    p_html += '<label class="form-check-label" for="permission_' + permission_array[i].id + '">' + permission_array[i].name + '</label>';
                    p_html += '</div>';
                    // p_html += '<input type="checkbox" id="permission_list" name="permission_list[]" value="' + response[i].id + '">' + response[i].name;
                }
                $('#permission_for_user').html(p_html);

            }
        }
    });
}