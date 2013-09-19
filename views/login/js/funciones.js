$(document).on("ready", function() {
    $(":input:first").focus();
    
    $("#frmLogin").validate({
        debug: true,
        errorClass: "text-error",
        submitHandler: function(form) {
            form.submit();
        }
    });
});