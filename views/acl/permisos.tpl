<h2>Administraci&oacute;n de Permisos</h2> 
{if isset($permisos) && count($permisos)}
    <div id="grid">
        <div class="data">
            <table id="rounded-corner">
                <thead>
                    <tr>
                        <th scope="col" class="rounded-company"></th>
                        <th scope="col" class="rounded">Permiso</th>
                        <th scope="col" class="rounded">Valor</th>
                        <th scope="col" class="rounded">Editar</th>
                        <th scope="col" class="rounded-q4">Eliminar</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="4" class="rounded-foot-left"><em>Mantemiento de Permisos, puede habilitar, denegar o ignorar un permiso.</em></td>
                        <td class="rounded-foot-right">&nbsp;</td>
                    </tr>
                </tfoot>
                <tbody>
                    {foreach item=pr from=$permisos}
                        <tr>
                            <td><input type='checkbox' name='id_permiso' value='{$pr.id_permiso}' /></td>
                            <td>{$pr.permiso}</td>
                            <td>{$pr.clave }</td>
                            <td><a class="editPermiso" href="javascript:void(0);" p="{$pr.id_permiso}" title='Editar permiso {$pr.permiso}' ><img src='{$_layoutParams.ruta_img}user_edit.png' /></a></td>
                            <td><a href='javascript:void(0);' class='ask' title='Eliminar permiso {$pr.permiso}' onclick="deleteRow('{$pr.permiso}', '{$pr.id_permiso}')"><img src='{$_layoutParams.ruta_img}trash.png' /></a></td></tr>
                        </tr>
                    {/foreach}
                </tbody>
            </table>  
            <a id='addPermiso' href='javascript:void(0);' class='bt_green' title='Agregar Permiso'><strong>Agregar Permiso</strong></a>
            <a href='#' class='bt_blue'><span class='bt_blue_lft'></span><strong>View all items from category</strong><span class='bt_blue_r'></span></a>
            <a href='#' class='bt_red'><span class='bt_red_lft'></span><strong>Delete items</strong><span class='bt_red_r'></span></a>
        </div>
        <div class="pagination">
            {$paginacion|default:""}
        </div>
    </div>
{else}
    <h4>No hay registros de permisos.</h4>
{/if}

<div id="loading">
    <img src="{$_layoutParams.ruta_img}ajax-loader.gif" border="0" />
</div>
<div id="modal_frmPermiso">
    <form id="frmPermiso" name="frmPermiso" action="{$_layoutParams.root}acl/setPermiso" method="post">
        <fieldset>
            <dl>
                <dt><label for="txtPermiso">Permiso:</label></dt>
                <dd>
                    <input class="NFText" type="text" name="txtPermiso" id="txtPermiso" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}acl/validateAjax');" />
                    <div id="txtPermisoFailed"></div>
                </dd>
            </dl>
            <dl>
                <dt><label for="txtKey">Key:</label></dt>
                <dd>
                    <input class="NFText" type="text" name="txtKey" id="txtKey" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}acl/validateAjax');" />
                    <div id="txtKeyFailed"></div>
                </dd>
            </dl>
            <dl class="submit">
                <input class="NFButton" type="button" name="submit" id="insertPermiso" value="Agregar" />
            </dl>
        </fieldset>
    </form>
</div>  

<div id="modal_frmPermisoEdit">
    <form id="frmEditPermiso" name="frmEditPermiso" action="{$_layoutParams.root}acl/setPermiso" method="post">
        <fieldset>
            <dl>
                <dt><label for="txtPermisoEdit">Permiso:</label></dt>
                <dd>
                    <input class="NFText" type="text" name="txtPermisoEdit" id="txtPermisoEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}acl/validateAjax');" />
                    <div id="txtPermisoEditFailed"></div>
                </dd>
            </dl>
            <dl>
                <dt><label for="txtKeyEdit">Key:</label></dt>
                <dd>
                    <input class="NFText" type="text" name="txtKeyEdit" id="txtKeyEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}acl/validateAjax');" />
                    <div id="txtKeyEditFailed"></div>
                </dd>
            </dl>
            <input type="hidden" id="id" name="id" value="" />
            <input type="hidden" name="optEdit" value="1" />
            <dl class="submit">
                <input class="NFButton" type="button" name="submit" id="editPermiso" value="Editar" />
            </dl>
        </fieldset>
    </form>
</div> 
<div id="exito" class="valid_box informe">
    <p></p>
</div>
<div id="error" class="error_box informe">
    <p></p>
</div>  