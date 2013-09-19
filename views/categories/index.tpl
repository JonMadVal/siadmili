<h2 class="text-center">Mantenimiento de Categor&iacute;as</h2>
{if isset($_error)}
    <div class="alert alert-error">{$_error}</div>
{/if}
{if isset($_exito)}
    <div class="alert alert-success">{$_exito}</div>
{/if}

{if isset($_errores) && count($_errores) > 0}
    <div class="alert alert-error">
        {foreach $_errores as $error}
            {$error}</br>
        {/foreach}
    </div>
{/if}
{if isset($_exitos) && count($_exitos) > 0} 
    <div class="alert alert-success">
        {foreach $_exitos as $exito}
            {$exito}</br>
        {/foreach}
    </div>
{/if}

<div class="well well-small">
    <form name="frmSearchCategory" id="frmSearchCategory" class="form-inline text-center">
        <label for="txtCategory">Categor&iacute;a: </label><input type="text" class="input-medium" name="txtCategory" id="txtCategory">  
        <button type="button" id="btnEnviar" class="btn"><i class="icon-search"></i></button>
    </form>
</div>
<!-- Inicio div grid -->
<div id="grid">
    {if isset($categories) && count($categories)}
        <form id="frmCategories" name="frmCategories" action="{$_layoutParams.root}categories/deleteCategories" method="post">
            <table class="table table-striped">
                <caption class="text-info">Mantemiento de Categor&iacute;as del Sistema, puede agregar, editar o eliminar alg&uacute;n registro.</caption>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Categor&iacute;a</th>
                        <th>Edit</th>
                        <th>Elim</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $categories as $cat}
                        <tr>
                            <td><input type='checkbox' name='idCategory[]' value='{$cat.catid}' /></td>
                            <td>{$cat.catname}</td>
                            <td><a href="javascript:void(0);" class="editCategory" title="Editar a {$cat.catname}" data-catid="{$cat.catid}"><i class="icon-edit"></i></a></td>
                            <td><a href="javascript:void(0);" class="delCategory" data-catname="{$cat.catname}" data-catid="{$cat.catid}" title="Eliminar a {$cat.catname}"><i class="icon-trash"></i></a></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>  
            <a id="addCategory" href="javascript:void(0);" class="btn btn-primary" title="Agregar categor&iacute;a"><strong>Agregar categor&iacute;a</strong></a>
            <a id="delCategories" href="javascript:void(0);" class="btn btn-danger" title="Eliminar items">Delete items</a>
        </form>
        {if isset($paginacion)}
            <div class="pagination">
                {$paginacion}
            </div>
        {/if}
    {else}
        <h3>No hay registros en esta tabla</h3>
    {/if}
</div>
<!-- Fin div grid -->

<!-- Modal Agregar Categoría -->
<div id="addCategoryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Agregar Categor&iacute;a</h3>
    </div>
    <form class="form-horizontal" name="frmAddCategory" id="frmAddCategory" action="" method="post">
        <div class="modal-body">   
            <div class="control-group">
                <label class="control-label" for="txtCat">Categor&iacute;a: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" class="required" name="txtCat" id="txtCat">
                </div>
            </div>
            <p class="text-error">* Es requerido</p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="grabar" value="1" />
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>

<!-- Modal Editar Categoría -->
<div id="editCategoryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Editar Categor&iacute;a</h3>
    </div>
    <form class="form-horizontal" name="frmEditCategory" id="frmEditCategory" action="" method="post">
        <div class="modal-body">            
            <div class="control-group">
                <label class="control-label" for="txtEditCat">Categor&iacute;a: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" class="required" name="txtEditCat" id="txtEditCat">
                </div>
            </div>
            <p class="text-error">* Es requerido</p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="grabar" value="2" />
            <input type="hidden" name="hdCategoryId" value="" />
            <input type="hidden" name="hdCatname" value="" />
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary">Editar</button>
        </div>
    </form>
</div>