<h2>Ultimos Posts</h2>
{if isset($customers) && count($customers)}
<table>
  {foreach item=datos from=$customers}
<tr>
  <td>{$datos.customerid}</td>
  <td>{$datos.name}</td>
  <td>{$datos.ap_paterno}</td>
  <td>{$datos.ap_materno}</td>
  <td>{$datos.address}</td>
  <td>{$datos.city}</td>
  <td>{$datos.zip}</td>
  <td>{$datos.country_id}</td>
  <!--<td>
    {if isset($datos.imagen)}
    <a href="{$_layoutParams.root}public/img/post/{$datos.imagen}">
      <img src="{$_layoutParams.root}public/img/post/thumb/thumb_{$datos.imagen}" />
    </a>
    {/if}
  </td>-->
  <td><a href="{$_layoutParams.root}customers/editCustomer/{$datos.customerid}">Editar</a></td>
  <td><a href="{$_layoutParams.root}customers/deleteCustomer/{$datos.customerid}">Eliminar</a></td>
</tr>
  {/foreach}
</table>
{else}
<p><strong>No hay clientes!</strong></p>
{/if}

{if isset($paginacion)}
  {$paginacion}
{/if}
<p><a href="{$_layoutParams.root}customers/addCustomer">Agregar Cliente</a></p>