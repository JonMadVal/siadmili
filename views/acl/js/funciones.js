$(document).on("ready", function() {
    // Asignamos el focus al input para ver página específica
    $(':input.goto').focus();

    // Comportamiento del botón para ver una página específica
    $(':input#goto_btn').on("click", function(ev) {
        ev.preventDefault();
        var $page = parseInt($('.goto').val());
        var $no_of_pages = parseInt($('.total').data('total'));
        $("form[name='frm_goto']").attr("action", _root_ + "acl/index/" + $page);
        if ($page != 0 && $page <= $no_of_pages) {
            $("form[name='frm_goto']").submit();
        } else {
            // En caso no hayamos ingresado la página o esta es superior al total de páginas nos muestra un alert de error
            $('#errorModal').modal();
            $('#errorModal').on('shown', function() {
                $(".modal-body p").text("Ingrese una página entre 1 y " + $no_of_pages);
                $(':input.goto').val('');
                $page = null;
            });
            $('#errorModal').on('hidden', function() {
                $(':input.goto').focus();
            });
        }
    });

    // Cuando presionamos el botón para agregar role nos muestra la ventana modal
    $("#addRole").on("click", function(ev) {
        ev.preventDefault();
        $("#addRoleModal").modal();
        $('#addRoleModal').on('show', function() {
            $(":input[name='txtRole']").val('');
            $("label[for='txtRole'][class='text-error']").remove();
        })
    });

    // Enviamos el submit a acl/index para agregar el role
    $(".btnAddRole").on("click", function(ev) {
        ev.preventDefault();
        $("#frmAddRole").submit();
    });

    var roleValido;
    //Validar si role ya esta registrado
    $.validator.addMethod("validateRole", function(value, element) {
        $.ajax({
            type: "POST",
            url: _root_ + "acl/verifyExistRole",
            data: {role: value},
            success: function(data) {
                roleValido = (data == 'true') ? true : false;
            }
        });
        return roleValido;
    }, 'Role ya existe');

    $("#frmAddRole").validate({
        debug: true,
        rules: {
            txtRole: {
                validateRole: true,
                required: true,
            }
        },
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#addRoleModal').modal('hide');
            form.submit();
        }
    });

    $("#frmEditRole").validate({
        debug: true,
        rules: {
            txtEditRole: {
                validateRole: true,
                required: true,
            }
        },
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#editRoleModal').modal('hide');
            form.submit();
        }
    });

    /**
     * Abrimos ventana modal para la edición de role seleccionado
     */
    $(".editRole").on("click", function(ev) {
        ev.preventDefault();
        var $role = $(this).data('role');
        $.post(_root_ + 'acl/getRole', {"role": $role}, function(data) {
            $(":input[name='txtEditRole']").val(data['role']);
            $(":input[name='roleID']").val(data['roleID']);
            $("label[for='txtEditRole'][class='text-error']").remove();
            $("#editRoleModal").modal();
        }, "json");
    });

    $("body").on("click", '.delRole', function(ev) {
        ev.preventDefault();
        var $roleID = $(this).data('roleid');
        var $role = $(this).data('role');
        $('#delRoleModal').modal();
        $('#delRoleModal').on('shown', function() {
            $(".modal-body p").text("¿Esta seguro que desea eliminar el role: " + $role + "?");
            $(":input[name='roleID']").val($roleID);
        });
        $('#delRoleModal').on('hidden', function() {
            $(".modal-body p").text('* Es requerido');
        })
    });

    $("#delRoles").on('click', function(ev) {
        ev.preventDefault();
        $("#frmRoles").submit();
    });

    /* Enviamos los datos a través del plugin jquery.form para agregar un nuevo usuario
     var options = {
     target      :   '.informe', // elemento destino que se actualizará 
     beforeSubmit:   showRequest, //  respuesta antes de llamarpre-submit callback 
     success     :   showResponse  //  respuesta después de llamar };
     // vincular formulario usando 'ajaxForm' 
     $('#frmAddRole').ajaxForm(options);
     $('#frmEditRole').ajaxForm(options);
     };*/
});

// respuesta antes de envío 
function showRequest(formData, jqForm) {
    var extra = [{
            name: 'ajax',
            value: '1'
        }];
    $.merge(formData, extra);
    return true;
}

// respuesta después de envío 
function showResponse(responseText, statusText) {
    if (responseText == 'Se ingres&oacute; correctamente al nuevo role' || responseText == 'El role se edit&oacute; satisfactoriamente.') {
        $('#exito').show();
        $('#error').hide();
        $('#frmAddRole').resetForm();
        loadData(1);
    }
    else {
        $('#error').show();
        $('#exito').hide();
    }
}