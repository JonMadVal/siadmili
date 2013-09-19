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
            jAlert('Ingrese una p&aacute;gina entre 1 y ' + $no_of_pages, 'Advertencia');
            $('.goto').val("").focus();
            return false;
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
    
    roleValido = true;
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

    /**
     * Eliminar un role determinado
     */
    $("body").on("click", '.delRole', function(ev) {
        ev.preventDefault();
        var $role = $(this).data('role');
        var $roleID = $(this).data('roleid');
        jConfirm('¿Está seguro que desea eliminar el registro ' + $role + '?', 'Eliminación de registro', function(r) {
            if (r == true) {
                $.post(_root_ + 'acl/deleteRole', {"id": $roleID}, function(data) {
                    if (data == '1') {
                        jConfirm('Se elimin&oacute; correctamente el registro', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'acl';
                            }
                        });
                    } else {
                        jConfirm('No se pudo eliminar el registro, por favor verifique', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'acl';
                            }
                        });
                    }
                });
            }
        });
    });

    /**
     * Eliminar varios roles a la vez
     */
    $("#delRoles").on('click', function(ev) {
        ev.preventDefault();
        jConfirm('¿Está seguro que desea eliminar los registros seleccionados?', 'Eliminación de registros', function(r) {
            if (r == true) {
                $("#frmRoles").submit();
            }
        });
    });
});