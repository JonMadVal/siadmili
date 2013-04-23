<h2 class="text-center">Administracion de Permisos de Role</h2>
<h3>Role: {$role}</h3>
<h4 class="text-center">Permisos</h4>
<div id="grid">
    <form name="frmPermisos" method="post" action="">
        <input type="hidden" name="guardar" value="1" />
        <input type="hidden" id="roleID" name="roleID" value="{$roleID}" />
        {if isset($permisos) && count($permisos)}
            <table class="table table-striped">
                <caption class="text-info">Mantemiento de Permisos de Roles del Sistema, se puede agregar o denegar permisos.</caption>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Permiso</th>
                        <th>Habilitado</th>
                        <th>Denegado</th>
                        <th>Ignorar</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach item=pr from=$permisos}
                        <tr>
                            <td><input type='checkbox' name='userID' value='{$pr.id}' /></td>
                            <td>{$pr.nombre}</td>
                            <td><input type="radio" name="perm_{$pr.id}" value="1" {if ($pr.valor == 1)}checked="checked"{/if}/></td>
                            <td><input type="radio" name="perm_{$pr.id}" value="" {if ($pr.valor == "")}checked="checked"{/if}/></td>
                            <td><input type="radio" name="perm_{$pr.id}" value="x" {if ($pr.valor === "x")}checked="checked"{/if}/></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>  
        {/if}
        <p><button class="btn btn-primary" type="submit">Guardar</button></p>
    </form> 
    {if isset($paginacion)}
        <div class="pagination">
            {$paginacion}
        </div>
    {/if}
</div>