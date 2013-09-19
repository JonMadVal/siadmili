{if isset($users) && count($users)}
    <form id="frmUsers" action="{$_layoutParams.root}usuarios/deleteUsers" method="post">
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
                    <th>Edit</th>
                    <th>Perm</th>
                    <th>Elim</th>
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
                        <td><a href="javascript:void(0);" class="editUser" title="Editar a {$data.login}" data-userid="{$data.userID}"><i class="icon-edit"></i></a></td>
                        <td><a href="{$_layoutParams.root}usuarios/permisos/{$data.userID}" title="Permisos"><i class="icon-tasks"></i></a></td>
                        <td><a href="javascript:void(0);" class="delUser" data-user="{$data.login}" data-userid="{$data.userID}" title="Eliminar a {$data.login}"><i class="icon-trash"></i></a></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>  
        <a id="addUser" href="javascript:void(0);" class="btn btn-primary" title="Agregar usuario"><strong>Agregar usuario</strong></a>
        <a id="delUsers" href="javascript:void(0);" class="btn btn-danger" title="Eliminar items">Delete items</a>
    </form>
    {if isset($paginacion)}
        <div class="pagination">
            {$paginacion}
        </div>
    {/if}
{else}
    <h3>No hay registros en esta tabla</h3>
{/if}