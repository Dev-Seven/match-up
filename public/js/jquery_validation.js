var customurl = SITE_URL;
$(document).ready(function(){
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#setting_page").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "facebook_link":{
                required:true,
                url: true
            },
            "instagram_link":{
                required:true,
                url: true
            },
            "support_tiktok":{
                required:true,
                url: true
            },
            "support_mail":{
                required:true,
                email:true,
                emailCheck:true,
            },
            "support_contact":{
                required:true,
                maxlength:10,
                minlength:10,
                number: true,
            }
        },
        messages:{
            "facebook_link":{
                required:'Please enter Facebook shared link',
                url:'Please enter valid link',
            },
            "instagram_link":{
                required:'Please enter Instagram shared link',
                url:'Please enter valid link',
            },
            "support_mail":{
                required:'Please enter support e-mail address',
                email:'Please enter valid e-mail address',
                emailCheck:'Please enter valid e-mail address',
            },
            "support_contact":{
                required:'Please enter Support contact number',
                maxlength:"Contact number must be 10 digits",
                minlength:"Contact number must be 10 digits",
                number: "Please enter valid Contact number",
            },
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> Loading...';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        },
    });

    $("#support_data_form").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "support_mail":{
                required:true,
                email:true,
                emailCheck:true,
            },
            "support_contact":{
                required:true,
                number: true,
            }
        },
        messages:{
            "support_mail":{
                required:'Please enter support e-mail address',
                email:'Please enter valid e-mail address',
                emailCheck:'Please enter valid e-mail address',
            },
            "support_contact":{
                required:'Please enter Support contact number',
                number: "Please enter valid Contact number",
            },
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> Loading...';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        },
    });

    
    
    $.validator.addMethod("emailCheck", function (value, element, param) {
        var check_result = false;
        result = this.optional( element ) || /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/.test( value );
        return result;
    });

    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 40MB');

    $.validator.addMethod("extension", function (value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Please enter a value with a valid extension.");

});

