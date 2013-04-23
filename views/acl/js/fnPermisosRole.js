$(document).on("ready", function(){
    // Asignamos el focus al input para ver página específica
    $(':input.goto').focus();

    // Comportamiento del botón para ver una página específica
    $(':input#goto_btn').on("click", function(ev) {
        ev.preventDefault();
        var $page = parseInt($('.goto').val());
        var $no_of_pages = parseInt($('.total').data('total'));
        var $roleID = parseInt($(":input#roleID").val());
        $("form[name='frm_goto']").attr("action", _root_ + "acl/permisosRole/" + $roleID + "/" + $page);
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
});