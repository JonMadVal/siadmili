<h2 class="text-center">Administraci&oacute;n de Roles</h2> 
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

<!-- Inicio div grid -->
<div id="grid">
    {if isset($_roles) && count($_roles)}        
        <form id="frmRoles" action="{$_layoutParams.root}acl/deleteRoles" method="post">
            <table class="table table-striped">
                <caption class="text-info">Mantemiento de Roles del Sistema, puede agregar, editar o eliminar alg&uacute;n registro.</caption>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Rol</th>
                        {if ($_acl->permiso('view_perm'))}<th>Permisos</th>{/if}
                        {if ($_acl->permiso('edit_role'))}<th>Editar</th>{/if}
                        {if ($_acl->permiso('del_role'))}<th>Eliminar</th>{/if}
                    </tr>
                </thead>
                <tbody>
                    {foreach $_roles as $rol}
                        <tr>
                            <td><input type='checkbox' name='idRole[]' value='{$rol.roleID}' /></td>
                            <td>{$rol.role}</td>
                            {if ($_acl->permiso('view_perm'))}<td><a href='{$_layoutParams.root}acl/permisosRole/{$rol.roleID}' title='Permisos'><i class="icon-tasks"></i></a></td>{/if}
                            {if ($_acl->permiso('edit_role'))}<td><a href='javascript:void(0);' class="editRole" data-role="{$rol.roleID}" title='Editar rol {$rol.role}'><i class="icon-edit"></i></a></td>{/if}
                            {if ($_acl->permiso('del_role'))}<td><a href='javascript:void(0);' class="delRole" data-role="{$rol.role}" data-roleID="{$rol.roleID}" title="Eliminar rol {$rol.role}"><i class="icon-trash"></i></a></td>{/if}
                        </tr>
                    {/foreach}
                </tbody>
            </table>  
            {if ($_acl->permiso('add_role'))}<a id="addRole" href="javascript:void(0);" class="btn btn-primary" title="Agregar rol">Agregar role</a>{/if}
            {if ($_acl->permiso('del_role'))}<a id="delRoles" href="javascript:void(0);" class="btn btn-danger" title="Eliminar items">Eliminar items</a>{/if}
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

<!-- Modal Agregar Role -->
<div id="addRoleModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Agregar Role</h3>
    </div>
    <form class="form-horizontal" name="frmAddRole" id="frmAddRole" action="" method="post">
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="txtRole">Role: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" name="txtRole" id="txtRole">
                </div>
            </div>
            <p class="text-error">* Es requerido</p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="grabar" value="1" />
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary btnAddRole">Guardar</button>
        </div>
    </form>
</div>

<!-- Modal Editar Role -->
<div id="editRoleModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Editar Role</h3>
    </div>
    <form class="form-horizontal" name="frmEditRole" id="frmEditRole" action="" method="post">
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="txtEditRole">Role: <span class="text-error">*</span></label>
                <div class="controls">
                    <input type="text" name="txtEditRole" id="txtEditRole">
                </div>
            </div>
            <p class="text-error">* Es requerido</p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="grabar" value="2" />
            <input type="hidden" name="roleID" value="" />
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>