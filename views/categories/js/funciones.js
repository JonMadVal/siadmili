$(document).on("ready", function() {
    // Función que en base a parámetros nos cargará la información a mostrar
    var loadData = function(pagina) {
        var datos = {page: pagina,
            category: $(":input[name='txtCategory']").val(),
            registros: $("#registros").val()};
        $.ajax({
            type: "POST",
            url: _root_ + "categories/displayCategories",
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
    $("#txtCategory").keyup(function() {
        loadData();
    });

    // Indicamos que cantidad de registros queremos ir mostrando
    $("body").on('change', "#registros", function(ev) {
        ev.preventDefault();
        loadData();
    });

    // Cuando presionamos el botón para agregar usuario nos muestra la ventana modal
    $("body").on("click", "#addCategory", function(ev) {
        ev.preventDefault();
        $("#addCategoryModal").modal();
        $('#addCategoryModal').on('show', function() {
            // Reseteamos los campos del formulario al abrirse
            $("#frmAddCategory")[0].reset();
            $("label[for^='txt'][class='text-error']").remove();
        })
    });

    //Validar si username ya esta registrado
    validateCategory = true;
    $.validator.addMethod("validateCategory", function(value, element) {
        $.post(_root_ + "categories/verifyCategory", {category: value})
                .done(function(data) {
            validateCategory = (data == 'true') ? true : false;
        });
        return validateCategory;
    }, 'Esta categoría ya se encuentra registrada.');

    $("#frmAddCategory").validate({
        debug: true,
        rules: {
            txtCat: {
                validateCategory: true,
            },
        },
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#addCategoryModal').modal('hide');
            form.submit();
        }
    });

    /**
     * Abrimos ventana modal para la edición de usuario seleccionado
     */
    $("body").on("click", ".editCategory", function(ev) {
        ev.preventDefault();
        var $catid = $(this).data('catid');
        $.post(_root_ + 'categories/getCategory', {"catid": $catid}, function(data) {
            // Reseteamos los campos del formulario al abrirse
            $("#frmEditCategory")[0].reset();
            $("label[for^='txt'][class='text-error']").remove();

            $(":input[name='txtEditCat']").val(data['catname']);
            $("input:hidden[name='hdCategoryId']").val(data.catid);
            $("input:hidden[name='hdCatname']").val(data.catname);
            $("#editCategoryModal").modal();
        }, "json");
    });

    $("#frmEditCategory").validate({
        debug: true,        
        errorClass: "text-error",
        submitHandler: function(form) {
            $('#editCategoryModal').modal('hide');
            form.submit();
        }
    });
    
    /**
     * Eliminar un usuario determinado
     */
    $("body").on("click", '.delCategory', function(ev) {
        ev.preventDefault();
        var $catname = $(this).data('catname');
        var $catid = $(this).data('catid');
        jConfirm('¿Está seguro que desea eliminar el registro ' + $catname + '?', 'Eliminación de registro', function(r) {
            if (r == true) {
                $.post(_root_ + 'categories/deleteCategory', {"catid": $catid}, function(data) {
                    if (data == '1') {
                        jConfirm('Se elimin&oacute; correctamente el registro', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'categories';
                            }
                        });
                    } else {
                        jConfirm('No se pudo eliminar el registro, por favor verifique', 'Aviso', function(r) {
                            if (r == true) {
                                window.location = _root_ + 'categories';
                            }
                        });
                    }
                });
            }
        });
    });

    /**
     * Eliminar varias categorías
     */
    $("body").on("click", "#delCategories", function(ev) {
        ev.preventDefault();
        jConfirm('¿Está seguro que desea eliminar los registros seleccionados?', 'Eliminación de registros', function(r) {
            if (r == true) {
                $("#frmCategories").submit();
            }
        });
    });
});