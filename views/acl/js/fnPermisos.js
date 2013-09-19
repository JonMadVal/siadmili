$(document).on("ready", function() {
    // Asignamos el focus al input para ver página específica
    $(':input.goto').focus();

    // Comportamiento del botón para ver una página específica
    $(':input#goto_btn').on("click", function(ev) {
        ev.preventDefault();
        var $page = parseInt($('.goto').val());
        var $no_of_pages = parseInt($('.total').data('total'));
        $("form[name='frm_goto']").attr("action", _root_ + "acl/permisos/FALSE/" + $page);
        if ($page != 0 && $page <= $no_of_pages) {
            $("form[name='frm_goto']").submit();
        } else {
            jAlert('Ingrese una p&aacute;gina entre 1 y ' + $no_of_pages, 'Advertencia');
            $('.goto').val("").focus();
            return false;
        }
    });

    // Cuando presionamos el botón para agregar permiso nos muestra la ventana modal
    $("#addPermiso").on("click", function(ev) {
        ev.preventDefault();
        $("#addPermisoModal").modal();
        $('#addPermisoModal').on('show', function() {
            $(":input[name='txtPermiso']").val('');
            $(":input[name='txtKey']").val('');
            $("label[for='txtPermiso'][class='text-error']").remove();
            $("label[for='txtKey'][class='text-error']").remove();
        })
    });

    var permValido = false;
    var keyValido = false;
    //Validar si permiso ya esta registrado
    $.validator.addMethod("validatePermiso", function(value, element) {
        $.ajax({
            type: "POST",
            url: _root_ + "acl/verifyPermiso",
            data: {permiso: value},
            success: function(data) {
                permValido = (data == 'true') ? true : false;
            }
        });
        return permValido;
    }, 'Permiso ya existe');
    
    //Validar si permiso ya esta registrado
    $.validator.addMethod("validateKey", function(value, element) {
        $.ajax({
            type: "POST",
            url: _root_ + "acl/verifyKey",
            data: {key: value},
            success: function(data) {
                keyValido = (data == 'true') ? true : false;
            }
        });
        return keyValido;
    }, 'Key ya existe');

    $("#frmAddPermiso").validate({
        debug: true,
        rules: {
            txtPermiso: {
                validatePermiso: true,
                required: true,
            },
            txtKey: {
                validateKey: true,
                required: true
            }
        },
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#addPermisoModal').modal('hide');
            form.submit();
        }
    });

    $("#frmEditPermiso").validate({
        debug: true,
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#editPermisoModal').modal('hide');
            form.submit();
        }
    });
    
    /**
     * Abrimos ventana modal para la edición de role seleccionado
     */
    $(".editPermiso").on("click", function(ev) {
        ev.preventDefault();
        var $id = $(this).data('permisoid');
        $.post(_root_ + 'acl/getPermiso', {"id": $id}, function(data) {
            $(":input[name='txtEditPermiso']").val(data['permiso']);
            $(":input[name='txtEditKey']").val(data['key']);
            $(":input[name='permisoID']").val('');
            $(":input[name='permisoID']").val(data['id_permiso']);
            $(":input[name='hd_permiso']").val(data['permiso']);
            $(":input[name='hd_key']").val(data['key']);
            $("label[for='txtEditPermiso'][class='text-error']").remove();
            $("#editPermisoModal").modal();
        }, "json");
    });
    
    $("body").on("click", '.delPermiso', function(ev) {
        ev.preventDefault();
        var $permiso = $(this).data('permiso');
        var $permisoID = $(this).data('permisoid');
        jConfirm('¿Está seguro que desea eliminar el registro ' + $permiso + '?', 'Eliminación de registro', function(r) {
            if (r == true) {
                $.post(_root_ + 'acl/deletePermiso', {"id": $permisoID}, function(data) {
                    if (data == '1') {
                        jConfirm('Se elimin&oacute; correctamente el registro', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'acl/permisos';
                            }
                        });
                    } else {
                        jConfirm('No se pudo eliminar el registro, por favor verifique', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'acl/permisos';
                            }
                        });
                    }
                });
            }
        });
    });

    $("#delPermisos").on('click', function(ev) {
        ev.preventDefault();
        jConfirm('¿Está seguro que desea eliminar los registros seleccionados?', 'Eliminación de registros', function(r) {
            if (r == true) {
                $("#frmPermisos").submit();
            }
        });
    });
    
    // Comportamiento del botón para busqueda de permiso
    $("#btnEnviar").click(function() {
        $("#frmSearch").submit();
    });
    
    $("body").on('change', "#registros", function(ev) {
        ev.preventDefault();
        var $registro = $("#registros").val();
        $("form[name='frm_goto']").attr("action", _root_ + "acl/permisos/" + $registro + "/FALSE/");
        $("form[name='frm_goto']").submit();
    });
});