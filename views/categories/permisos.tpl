<h2 class="text-center">Permisos de Usuario</h2> 
<h4>Usuario: {$info.nombres} {$info.apaterno} {$info.amaterno} </h4>
<h4>Role: {$info.role}</h4>
{if isset($_error)}
    <div class="alert alert-error">{$_error}</div>
{/if}
{if isset($_exito)}
    <div class="alert alert-success">{$_exito}</div>
{/if}

<!-- Inicio grid -->
<div id="grid">
    {if isset($permisos) && count($permisos)}
        <form name="frmPermisos" method="post" action="">
            <input type="hidden" name="guardar" value="1" />
            <input type="hidden" class="id_user" id="id_user" name="id_user" value="{$info.userID}" />
            <table class="table table-striped">
                <caption class="text-info">Mantemiento de Permisos de Usuario, puede editar y/o asignar los permisos.</caption>
                <thead>
                    <tr>
                        <th>Permisos</th>
                        <th>Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $permisos as $pr}
                        {if $role.$pr.valor == 1}
                            {assign var="v" value="habilitado"}
                        {else}
                            {assign var="v" value="denegado"}
                        {/if}
                        <tr>
                            <td>{$usuario.$pr.permiso}</td>
                            <td>
                                <select name="perm_{$usuario.$pr.id}">
                                    <option value="x" {if $usuario.$pr.heredado} selected="selected"{/if}>Heredado({$v})</option>
                                    <option value="1" {if ($usuario.$pr.valor == 1 && $usuario.$pr.heredado == "")} selected="selected"{/if}>Habilitado</option>
                                    <option value="" {if ($usuario.$pr.valor == "" && $usuario.$pr.heredado == "")} selected="selected"{/if}>Denegado</option>
                                </select>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>              
            <input class="btn btn-primary" type="submit" value="Guardar" />
        </form>
        {if isset($paginacion)}
            <div class="pagination">
                {$paginacion}
            </div>
        {/if} 
    {else}
        <h3 class="text-center text-info">El role del usuario no tiene asignado permisos.</h3>
    {/if}
    <button class="btn btn-primary" type="button" title="Ir atr&aacute;s" onclick="window.location = '{$_layoutParams.root}usuarios'">Ir atr&aacute;s</button>
</div>
<!-- Fin grid -->