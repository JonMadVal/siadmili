<h2 class="text-center">Mantenimiento de Usuarios</h2>
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
    <form name="frmSearchUser" id="frmSearchUser" class="form-inline text-center">
        <label for="txtNombre">Nombres: </label><input type="text" class="input-medium" name="txtNombre" id="txtNombre">
        <label for="txtApaterno">Ape. Paterno: </label><input type="text" class="input-medium" name="txtApaterno" id="txtApaterno">
        <label for="txtAmaterno">Ape. Materno: </label><input type="text" class="input-medium" name="txtAmaterno" id="txtAmaterno">        
        <button type="button" id="btnEnviar" class="btn"><i class="icon-search"></i></button>
    </form>
</div>
<!-- Inicio div grid -->
<div id="grid">
    {if isset($users) && count($users)}
        <form id="frmUsers" name="frmUsers" action="{$_layoutParams.root}usuarios/deleteUsers" method="post">
            <table class="table table-striped">
                <caption class="text-info">Mantemiento de Usuarios del Sistema, puede agregar, editar o eliminar alg&uacute;n registro.</caption>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Role</th>
                        {if ($_acl->permiso('edit_user'))}<th>Edit</th>{/if}
                        {if ($_acl->permiso('view_perm'))}<th>Perm</th>{/if}
                        {if ($_acl->permiso('del_user'))}<th>Elim</th>{/if}
                    </tr>
                </thead>
                <tbody>
                    {foreach $users as $data}
                        <tr>
                            <td><input type='checkbox' name='idUser[]' value='{$data.userID}' /></td>
                            <td>{$data.nombres}</td>
                            <td>{$data.apaterno} {$data.amaterno}</td>
                            <td>{$data.login}</td>
                            <td>{$data.email}</td>
                            <td>{$data.role}</td>
                            {if ($_acl->permiso('edit_user'))}<td><a href="javascript:void(0);" class="editUser" title="Editar a {$data.login}" data-userid="{$data.userID}"><i class="icon-edit"></i></a></td>{/if}
                            {if ($_acl->permiso('view_perm'))}<td><a href="{$_layoutParams.root}usuarios/permisos/{$data.userID}" title="Permisos"><i class="icon-tasks"></i></a></td>{/if}
                            {if ($_acl->permiso('del_user'))}<td><a href="javascript:void(0);" class="delUser" data-user="{$data.login}" data-userid="{$data.userID}" title="Eliminar a {$data.login}"><i class="icon-trash"></i></a></td>{/if}
                        </tr>
                    {/foreach}
                </tbody>
            </table>  
            {if ($_acl->permiso('add_user'))}<a id="addUser" href="javascript:void(0);" class="btn btn-primary" title="Agregar usuario"><strong>Agregar usuario</strong></a>{/if}
            {if ($_acl->permiso('del_user'))}<a id="delUsers" href="javascript:void(0);" class="btn btn-danger" title="Eliminar items">Delete items</a>{/if}
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
<div id="addUserModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Agregar Usuario</h3>
    </div>
    <form class="form-horizontal" name="frmAddUser" id="frmAddUser" action="" method="post">
        <div class="modal-body">            
            <div class="row-fluid">
                <div class="span12">
                    <label for="txtNombre">Nombre: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtNombre" id="txtNombre">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label for="txtAPaterno">Ape. Paterno: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtAPaterno" id="txtAPaterno">
                </div>
                <div class="span6">
                    <label for="txtAMaterno">Ape. Materno: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtAMaterno" id="txtAMaterno">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <label for="txtUsername">Username: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtUsername" id="txtUsername">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label for="txtPass">Password: <span class="text-error">*</span></label>
                    <input type="password" class="required" name="txtPass" id="txtPass" minlength="6">
                </div>
                <div class="span6">
                    <label for="txtRePass">Repetir Password: <span class="text-error">*</span></label>
                    <input type="password" class="required" name="txtRePass" id="txtRePass" minlength="6">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label for="txtEmail">Email: <span class="text-error">*</span></label>
                    <input type="email" class="required" name="txtEmail" id="txtEmail">
                </div>
                <div class="span6">
                    <label for="txtTel">Tel&eacute;fono: </label>
                    <input type="text" name="txtTel" id="txtTel">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label for="drdRole">Role: <span class="text-error">*</span></label>
                    <select name="drdRole" id="drdRole" class="required">
                        <option value="0">Seleccione el role</option>
                        {foreach $_level as $role}
                            <option value="{$role.roleID}">{$role.role}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="span6">
                    <label for="txtComentario">Comentario:</label>
                    <textarea name="txtComentario" id="txtComentario"></textarea>
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

<!-- Modal Editar Usuario -->
<div id="editUserModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Editar Usuario</h3>
    </div>
    <form class="form-horizontal" name="frmEditUser" id="frmEditUser" action="" method="post">
        <div class="modal-body">            
            <div class="row-fluid">
                <div class="span12">
                    <label for="txtEditNombre">Nombre: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtEditNombre" id="txtEditNombre">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label for="txtEditAPaterno">Ape. Paterno: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtEditAPaterno" id="txtEditAPaterno">
                </div>
                <div class="span6">
                    <label for="txtEditAMaterno">Ape. Materno: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtEditAMaterno" id="txtEditAMaterno">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <label for="txtEditUsername">Username: <span class="text-error">*</span></label>
                    <input type="text" class="required" name="txtEditUsername" id="txtEditUsername">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label for="txtEditEmail">Email: <span class="text-error">*</span></label>
                    <input type="email" class="required" name="txtEditEmail" id="txtEmail">
                </div>
                <div class="span6">
                    <label for="txtEditTel">Tel&eacute;fono: </label>
                    <input type="text" name="txtEditTel" id="txtEditTel">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label for="drdEditRole">Role: <span class="text-error">*</span></label>
                    <select name="drdEditRole" id="drdEditRole" class="required">
                        <option value="0">Seleccione el role</option>
                        {foreach $_level as $role}
                            <option value="{$role.roleID}">{$role.role}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="span6">
                    <label for="txtEditComentario">Comentario:</label>
                    <textarea name="txtEditComentario" id="txtEditComentario"></textarea>
                </div>
            </div>
            <p class="text-error">* Es requerido</p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="grabar" value="2" />
            <input type="hidden" name="hdUserId" value="" />
            <input type="hidden" name="hdUsername" value="" />
            <input type="hidden" name="hdEmail" value="" />
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-primary">Editar</button>
        </div>
    </form>
</div>