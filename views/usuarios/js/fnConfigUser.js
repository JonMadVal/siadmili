$(document).on("ready", function() {
    //Validar si se ha seleccionado el role
    $.validator.addMethod("validateRole", function(value, element) {
        var result = false;
        if (value != "0") {
            result = true;
        }
        return result;
    }, 'Debe seleccionar el role del usuario.');

    $("#frmConfigUser").validate({
        debug: true,
        rules: {
            txtNombres: {
                required: true,
            },
            txtAPaterno: {
                required: true,
            },
            txtAMaterno: {
                required: true,
            },
            txtRePass: {
                equalTo: "#txtPass",
            },
            txtLogin: {
                required: true,
            },
            txtEmail: {
                required: true,
            }, 
            drdRole: {
                validateRole: true,
            }
        },
        errorClass: "text-error",
        submitHandler: function(form) {
            form.submit();
        }
    });
});