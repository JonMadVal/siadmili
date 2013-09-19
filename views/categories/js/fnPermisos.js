$(document).ready(function(){
    // Asignamos el focus al input para ver página específica
    $(':input.goto').focus();

    // Comportamiento del botón para ver una página específica
    $("body").on("click", ":input#goto_btn", function(ev){
        ev.preventDefault();
        var $page = parseInt($('.goto').val());
        var $no_of_pages = parseInt($('.total').data('total'));
        var $userID = parseInt($(":input#id_user").val());
        $("form[name='frm_goto']").attr("action", _root_ + "usuarios/permisos/" + $userID + "/" + $page);
        if ($page != 0 && $page <= $no_of_pages) {
            $("form[name='frm_goto']").submit();
        } else {
            jAlert('Ingrese una p&aacute;gina entre 1 y ' + $no_of_pages, 'Advertencia');
            $('.goto').val("").focus();
            return false;
        }
    });
    
    $("body").on('change', "#registros", function(ev) {
        ev.preventDefault();
        var $registro = $("#registros").val();
        var $userID = parseInt($(":input#id_user").val());
        $("form[name='frm_goto']").attr("action", _root_ + "usuarios/permisos/" + $userID + '/FALSE/' + $registro);
        $("form[name='frm_goto']").submit();
    });
});