<h2 class="text-center">Administraci&oacute;n de Permisos</h2> 
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
    <form name="frmSearch" id="frmSearch" class="form-inline text-center" method="POST">
        <label for="txtSearchPermiso">Permiso: </label><input type="text" class="input-large" name="txtSearchPermiso" id="txtSearchPermiso">
        <label for="txtSearchValor">Valor: </label><input type="text" class="input-large" name="txtSearchValor" id="txtSearchValor">     
        <button type="button" id="btnEnviar" class="btn"><i class="icon-search"></i></button>
    </form>
</div>
<div id="grid">
    {if isset($_permisos) && count($_permisos)}
        <form id="frmPermisos" action="{$_layoutParams.root}acl/deletePermisos" method="post">
            <table class="table table-striped">
                <caption class="text-info">Mantemiento de Permisos del Sistema, puede agregar, editar o eliminar alg&uacute;n registro.</caption>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Permiso</th>
                        <th>Valor</th>
                        {if ($_acl->permiso('edit_perm'))}<th>Editar</th>{/if}
                        {if ($_acl->permiso('del_perm'))}<th>Eliminar</th>{/if}
                    </tr>
                </thead>
                <tbody>
                    {foreach $_permisos as $pr}
                        <tr>
                            <td><input type='checkbox' name='idPermiso[]' value='{$pr.id_permiso}' /></td>
                            <td>{$pr.permiso}</td>
                            <td>{$pr.key}</td>
                            {if ($_acl->permiso('edit_perm'))}<td><a href='javascript:void(0);' class="editPermiso" data-permisoID="{$pr.id_permiso}" title='Editar permiso {$pr.permiso}'><i class="icon-edit"></i></a></td>{/if}
                            {if ($_acl->permiso('del_perm'))}<td><a href='javascript:void(0);' class="delPermiso" data-permiso="{$pr.permiso}" data-permisoID="{$pr.id_permiso}" title="Eliminar permiso {$pr.permiso}"><i class="icon-trash"></i></a></td>{/if}
                        </tr>
                    {/foreach}
                </tbody>
            </table>  
            {if ($_acl->permiso('add_perm'))}<a id="addPermiso" href="javascript:void(0);" class="btn btn-primary" title="Agregar permiso">Agregar permiso</a>{/if}
            {if ($_acl->permiso('del_perm'))}<a id="delPermisos" href="javascript:void(0);" class="btn btn-danger" title="Eliminar items">Eliminar items</a>{/if}
        </form>
        {if isset($paginacion)}
            <div class="pagination">
                {$paginacion}
            </div>
        {/if}
    {else}
        <h3>No hay registros de permisos.</h3>
    {/if}
</div>

<!-- Modal Agregar Role -->
<div id="addPermisoModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Agregar Permiso</h3>
    </div>
    <form class="form-horizontal" name="frmAddPermiso" id="frmAddPermiso" action="" method="post">
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="txtPermiso">Permiso: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" name="txtPermiso" id="txtPermiso">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="txtKey">Key: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" name="txtKey" id="txtKey">
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

<!-- Modal Editar Role -->
<div id="editPermisoModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Editar Permiso</h3>
    </div>
    <form class="form-horizontal" name="frmEditPermiso" id="frmEditPermiso" action="" method="post">
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="txtEditPermiso">Permiso: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" class="required" name="txtEditPermiso" id="txtEditPermiso">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="txtEditKey">Key: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" class="required" name="txtEditKey" id="txtEditKey">
                </div>
            </div>
            <p class="text-error">* Es requerido</p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="grabar" value="2" />
            <input type="hidden" name="permisoID" value="" />
            <input type="hidden" name="hd_permiso" value="" />
            <input type="hidden" name="hd_key" value="" />
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>