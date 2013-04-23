$(document).ready(function(){
    // Otorgamos al primer input el focus
    $(":input:first").focus();
   
    $('#go_btn').live('click',function(){
        var page = parseInt($('.goto').val());
        var no_of_pages = parseInt($('.total').attr('a'));
        if(page != 0 && page <= no_of_pages){
            window.location = getBaseURL() + 'acl/permisos/' + page;
        }else{
            jAlert('Ingrese una p&aacute;gina entre 1 y '+no_of_pages, 'Advertencia');
            $('.goto').val("").focus();
            return false;
        }
    });
    
    // Hacemos que todos los div cuyo id empiecen por frm inicialmente no se muestren
    $('div[id^="frm"]').addClass('hidden');
    $('div[id^="modal"]').addClass('hidden');
    
    // Todo div cuyo id terminen con Failed no se muestren inicialmente
    $('div[id $= "Failed"]').addClass('hidden');
    
    // Ocultamos la caja donde se muestran los errores y de éxito
    $('#error').hide();
    $('#exito').hide();
    
    // Editar un usuario
    $('.editPermiso').live('click', function(){
        $('#error').hide();
        $('#exito').hide();
        var id = $(this).attr('p');
        $.post(getBaseURL()+'acl/editPermiso', {'id': id}, function(data) {
            $('#modal_frmPermisoEdit').dialog({
                modal    :   true,
                minWidth :   620,
                minHeight:   150,
                title    :   'Editar Permiso',
                show     :   'slide',
                hide     :   'slide',
                resizable:   false,
                open     :   function(){
                    $('div[id$="Failed"]').addClass('hidden');
                    $('input[name^="hd"]').addClass('hidden');
                    $('input[name^="namePermiso"]').remove();
                    $('input[name^="nameKey"]').remove();
                }
            });
            $('#txtPermisoEdit').attr('value', data.permiso);
            $('#txtKeyEdit').attr('value', data.key);
            $('#id').attr('value', data.id_permiso);
            $('<input type="hidden" name="namePermiso" value="' + data.permiso + '" />').insertAfter('#id');
            $('<input type="hidden" name="nameKey" value="' + data.key + '" />').insertBefore('#editPermiso');
        }, 'json');
        return false;
    });
  
    // Cargarmos el div que nos mostrará el modal para agregar un permiso
    $('#addPermiso').live('click', function(){
        $('#error').hide();
        $('#exito').hide();
        $('#modal_frmPermiso').dialog({
            modal    :   true,
            minWidth :   620,
            minHeight:   150,
            title    :   'Agregar Permiso',
            show     :   'slide',
            hide     :   'slide',
            resizable:   false,
            open     :   function(){
                $('div[id$="Failed"]').addClass('hidden');
            }
        });
    });
    
    // Cambiamos el estilo de los input cuando tienen el focus
    $(':input').focus(function(){
        $(this).css('border', '1px dotted #666');
    })
        
    // Realizamos el submit a través del botoón insertPermiso
    $('#insertPermiso').live('click', function() {
        $('#frmPermiso').submit();
        $('#modal_frmPermiso').dialog("close");
    });
    
    // Realizamos el submit a través del botón editUser
    $('#editPermiso').live('click', function() {
        $('#frmEditPermiso').submit();
        $('#modal_frmPermisoEdit').dialog("close");
    });
    
    $('#loading').hide();
    
    $('#loading img').ajaxStart(function(){
        $(this).show();
    }).ajaxStop(function(){
        $(this).hide();
    });
    
    // Enviamos los datos a través del plugin jquery.form para agregar un nuevo usuario
    var options = { 
        target      :   '.informe', // elemento destino que se actualizará 
        beforeSubmit:   showRequest,  //  respuesta antes de llamar pre-submit callback 
        success     :   showResponse  //  respuesta después de llamar 
    }; 
 
    // vincular formulario usando 'ajaxForm' 
    $('#frmPermiso').ajaxForm(options);
    $('#frmEditPermiso').ajaxForm(options);
})

// respuesta antes de envío 
function showRequest(formData, jqForm) { 
    var extra = [ {
        name: 'ajax', 
        value: '1'
    }];
    $.merge(formData, extra)
 
    return true;  
} 
 
// respuesta después de envío 
function showResponse(responseText, statusText)  { 
    if (responseText == 'Se ingres&oacute; correctamente el permiso' || responseText == 'Se edit&oacute; correctamente el permiso'){
        window.location = getBaseURL() + "acl/permisos";
        $('#exito').show();
        $('#error').hide();
        $('#frmPermiso').resetForm();
    }
    else {
        $('#error').show();
        $('#exito').hide();
    }
} 

// Función que preguntará de estar seguro de eliminar un registro
function deleteRow(registro,  id){
    jConfirm('¿Está seguro que desea eliminar el registro '+registro+'?', 'Eliminación de registro', function(r) {
        if(r == true){
            $.ajax({
                type    :   "POST",
                url     :   getBaseURL() + "acl/deletePermiso",
                data    :   "id="+id,
                success :   function(msg){
                    if(msg == '0'){
                        $('#error').text('No se pudo eliminar el registro').show();
                        window.location = getBaseURL() + "acl/permisos";
                    }else if(msg == '1'){
                        $('#exito').text('El registro se elimino correctamente').show();
                        window.location = getBaseURL() + "acl/permisos";
                    }
                }
            });
        }
    });
}