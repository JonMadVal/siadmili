$(document).on("ready", function() {
    // Función que en base a parámetros nos cargará la información a mostrar
    var loadData = function(pagina) {
        var datos = {page: pagina,
            nombre: $(":input[name='txtNombre']").val(),
            apaterno: $(":input[name='txtApaterno']").val(),
            amaterno: $(":input[name='txtAmaterno']").val(),
            registros: $("#registros").val()};
        $.ajax({
            type: "POST",
            url: _root_ + "usuarios/displayUser",
            data: datos
        }).done(function(msg) {
            $("#grid").html('');
            if (msg != '') {
                $("#grid").html(msg);
            } else {
                $("#grid").html("<h4 class='text-info text-center'>No se encontr&oacute; registros.</h4>");
            }
        });
    }

    // Nos mostrará los usuarios de acuerdo a la página seleccionada
    $("body").on("click", ".pagina", function(ev) {
        ev.preventDefault();
        var page = $(this).data('page');
        loadData(page);
    });

    // Comportamiento del botón para ver una página específica
    $("body").on("click", "#goto_btn", function(ev) {
        ev.preventDefault();
        var $page = parseInt($('.goto').val());
        var $no_of_pages = parseInt($('.total').data('total'));
        if ($page != 0 && $page <= $no_of_pages) {
            loadData($page);
        } else {
            jAlert('Ingrese una p&aacute;gina entre 1 y ' + $no_of_pages, 'Advertencia');
            $('.goto').val("").focus();
            return false;
        }
    });

    // Comportamiento del botón para busqueda de usuario
    $("#btnEnviar").click(function() {
        loadData();
    });

    // Cuando vayamos ingresando nuestros texto de búsqueda nos irá mostrando el resultado
    $("#txtNombre, #txtApaterno, #txtAmaterno").keyup(function() {
        loadData();
    });

    // Indicamos que cantidad de registros queremos ir mostrando
    $("body").on('change', "#registros", function(ev) {
        ev.preventDefault();
        loadData();
    });

    // Cuando presionamos el botón para agregar usuario nos muestra la ventana modal
    $("body").on("click", "#addUser", function(ev) {
        ev.preventDefault();
        $("#addUserModal").modal();
        $('#addUserModal').on('show', function() {
            // Reseteamos los campos del formulario al abrirse
            $("#frmAddUser")[0].reset();
            $("label[for^='txt'][class='text-error']").remove();
        })
    });

    //Validar si username ya esta registrado
    validateUsername = true;
    validateEmail = true;
    $.validator.addMethod("validateUsername", function(value, element) {
        $.post(_root_ + "usuarios/verifyUsername", {username: value})
                .done(function(data) {
            validateUsername = (data == 'true') ? true : false;
        });
        return validateUsername;
    }, 'Username ya se encuentra registrado.');

    $.validator.addMethod("validateEmail", function(value, element) {
        $.post(_root_ + "usuarios/verifyEmail", {email: value})
                .done(function(data) {
            validateEmail = (data == 'true') ? true : false;
        });
        return validateEmail;
    }, 'Email ya se encuentra registrado.');

    //Validar si se ha seleccionado el role
    $.validator.addMethod("validateRole", function(value, element) {
        var result = false;
        if (value != "0") {
            result = true;
        }
        return result;
    }, 'Debe seleccionar el role del usuario.');

    $("#frmAddUser").validate({
        debug: true,
        rules: {
            txtUsername: {
                validateUsername: true,
            },
            txtRePass: {
                equalTo: "#txtPass",
            },
            txtEmail: {
                validateEmail: true,
            },
            drdRole: {
                validateRole: true,
            }
        },
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#addUserModal').modal('hide');
            form.submit();
        }
    });

    /**
     * Abrimos ventana modal para la edición de usuario seleccionado
     */
    $("body").on("click", ".editUser", function(ev) {
        ev.preventDefault();
        var $userid = $(this).data('userid');
        $.post(_root_ + 'usuarios/getUser', {"userid": $userid}, function(data) {
            // Reseteamos los campos del formulario al abrirse
            $("#frmEditUser")[0].reset();
            $("label[for^='txt'][class='text-error']").remove();

            $(":input[name='txtEditNombre']").val(data['nombres']);
            $(":input[name='txtEditAPaterno']").val(data['apaterno']);
            $(":input[name='txtEditAMaterno']").val(data['amaterno']);
            $(":input[name='txtEditUsername']").val(data['login']);
            $(":input[name='txtEditEmail']").val(data['email']);
            $(":input[name='txtEditTel']").val(data['telefono']);
            $("select[name='drdEditRole'] option[value=" + data.roleID + "]").prop("selected", true);
            $(":input[name='txtEditComentario']").val(data['Comentario']);
            $("input:hidden[name='hdUserId']").val(data.userID);
            $("input:hidden[name='hdUsername']").val(data.login);
            $("input:hidden[name='hdEmail']").val(data.email);
            $("#editUserModal").modal();
        }, "json");
    });

    $("#frmEditUser").validate({
        debug: true,
        rules: {            
            drdEditRole: {
                validateRole: true,
            }
        },
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#editUserModal').modal('hide');
            form.submit();
        }
    });
    
    /**
     * Eliminar un usuario determinado
     */
    $("body").on("click", '.delUser', function(ev) {
        ev.preventDefault();
        var $username = $(this).data('user');
        var $userID = $(this).data('userid');
        jConfirm('¿Está seguro que desea eliminar el registro ' + $username + '?', 'Eliminación de registro', function(r) {
            if (r == true) {
                $.post(_root_ + 'usuarios/deleteUser', {"id": $userID}, function(data) {
                    if (data == '1') {
                        jConfirm('Se elimin&oacute; correctamente el registro', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'usuarios';
                            }
                        });
                    } else {
                        jConfirm('No se pudo eliminar el registro, por favor verifique', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'usuarios';
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
    $("#delUsers").on('click', function(ev) {
        ev.preventDefault();
        jConfirm('¿Está seguro que desea eliminar los registros seleccionados?', 'Eliminación de registros', function(r) {
            if (r == true) {
                $("#frmUsers").submit();
            }
        });
    });
});