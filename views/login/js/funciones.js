//$("#frmLogin").validate({debug: true});
$("#frmLogin").validate({
    debug: true,
    rules: {
        username: {
            required: true
        },
        password: {
            required: true,
            min: 6
        }
    },
    errorClass: "text-error",
    submitHandler: function(form) {
        form.submit();
    }
});