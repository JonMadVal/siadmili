$(document).ready(function(){
    // Cambiamos el estilo de los input cuando tienen el focus
    $(':input').focus(function(){
        $(this).css('border', '1px dotted #666');
    })
  
    $('#go_btn').live('click',function(){
        var page = parseInt($('.goto').val());
        var no_of_pages = parseInt($('.total').attr('a'));
        var id_user = parseInt($('.id_user').val());
        if(page != 0 && page <= no_of_pages){
            window.location = getBaseURL() + 'usuarios/permisos/' + id_user + '/' + page;
        }else{
            jAlert('Ingrese una p&aacute;gina entre 1 y '+no_of_pages, 'Advertencia');
            $('.goto').val("").focus();
            return false;
        }
    });    
});