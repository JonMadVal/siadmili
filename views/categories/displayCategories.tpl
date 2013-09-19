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