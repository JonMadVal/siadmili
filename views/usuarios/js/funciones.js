$(document).on("ready", function() {
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
            $("#grid").html(msg);
        });
    }

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

    $("#txtNombre, #txtApaterno, #txtAmaterno").keyup(function() {
        loadData();
    });

    $("body").on('change', "#registros", function(ev) {
        ev.preventDefault();
        loadData();
    });
});
/* 
 // Hacemos que todos los div cuyo id empiecen por frm inicialmente no se muestren
 $('div[id^="frm"]').addClass('hidden');
 
 // Todo div cuyo id terminen con Failed no se muestren inicialmente
 $('div[id $= "Failed"]').addClass('hidden');
 
 // Ocultamos la caja donde se muestran los errores y de éxito
 $('#error').hide();
 $('#exito').hide();
 
 
 // Editar un usuario
 $('#dataUser').on('click', function() {
 $('#error').hide();
 $('#exito').hide();
 var id = $(this).attr('p');
 $.post(getBaseURL() + 'usuarios/editUser', {'id': id}, function(data) {
 $('#frm_editUser').dialog({
 modal: true,
 minWidth: 620,
 minHeight: 500,
 title: 'Editar Usuario',
 show: 'slide',
 hide: 'slide',
 resizable: false,
 open: function() {
 $('div[id$="Failed"]').addClass('hidden');
 $("input:file").val('');
 $('input[name^="hd"]').addClass('hidden');
 }
 });
 $('#nameEdit').attr('value', data.nombres);
 $('#apaternoEdit').attr('value', data.apaterno);
 $('#amaternoEdit').attr('value', data.amaterno);
 $('#loginEdit').attr('value', data.login);
 $('#emailEdit').attr('value', data.email);
 $('#telefonoEdit').attr('value', data.telefono);
 $('#avatarEdit').text('');
 $("select[id='levelEdit'] option[value=" + data.level + "]").attr("selected", true);
 $('#commentsEdit').text(data.Comentario);
 $('#id').val(id);
 $('#hdLogin').val(data.login);
 $('#hdEmail').val(data.email);
 $('#hdAvatar').val(data.avatar);
 }, 'json');
 return false;
 });
 
 // Cargamos el div cuyo id addUser se muestre como modal
 $('#addUser').on('click', function() {
 $('#error').hide();
 $('#exito').hide();
 $('#frm_addUser').dialog({
 modal: true,
 minWidth: 620,
 minHeight: 500,
 title: 'Agregar nuevo usuario',
 show: 'slide',
 hide: 'slide',
 resizable: false,
 open: function() {
 $('div[id$="Failed"]').addClass('hidden');
 }
 });
 });
 
 // Cambiamos el estilo de los input cuando tienen el focus
 $(':input').focus(function() {
 $(this).css('border', '1px dotted #666');
 })
 
 // Realizamos el submit a través del botón insertUser
 $('#insertUser').on('click', function() {
 $('#frmAddUser').submit();
 $('#frm_addUser').dialog("close");
 });
 
 // Realizamos el submit a través del botón editUser
 /*$('#editUser').live('click', function() {
 $('#frmEditUser').submit();
 $('#frm_editUser').dialog("close");
 });
 
 $('#loading').hide();
 
 $('#loading img').ajaxStart(function() {
 $(this).show();
 }).ajaxStop(function() {
 $(this).hide();
 });
 
 // Enviamos los datos a través del plugin jquery.form para agregar un nuevo usuario
 var options = {
 target: '.informe', // elemento destino que se actualizará 
 beforeSubmit: showRequest, //  respuesta antes de llamarpre-submit callback 
 success: showResponse  //  respuesta después de llamar 
 };
 
 // vincular formulario usando 'ajaxForm' 
 //$('#frmAddUser').ajaxForm(options); 
 //$('#frmEditUser').ajaxForm(options);
 })
 
 // respuesta antes de envío 
 function showRequest(formData, jqForm) {
 var extra = [{
 name: 'ajax',
 value: '1'
 }];
 $.merge(formData, extra)
 
 return true;
 }
 
 // respuesta después de envío 
 function showResponse(responseText, statusText) {
 if (responseText == 'Se ingres&oacute; correctamente al nuevo usuario' || responseText == 'El usuario se edit&oacute; satisfactoriamente.') {
 $('#exito').show();
 $('#error').hide();
 $('#frmAddUser').resetForm();
 loadData(1);
 } else {
 $('#error').show();
 $('#exito').hide();
 }
 }
 
 // Función que preguntará de estar seguro de eliminar un registro
 function deleteRow(registro, id) {
 jConfirm('¿Está seguro que desea eliminar el registro ' + registro + '?', 'Eliminación de registro', function(r) {
 if (r == true) {
 $.ajax({
 type: "POST",
 url: getBaseURL() + "usuarios/deleteUser",
 data: "id=" + id,
 success: function(msg) {
 if (msg == '0') {
 $('#error').text('No se pudo eliminar el registro').show();
 loadData(1);
 } else if (msg == '1') {
 $('#exito').text('El registro se elimino correctamente').show();
 loadData(1);
 }
 }
 });
 }
 });
 }*/