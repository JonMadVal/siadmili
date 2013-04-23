{if $_result == FALSE}
  <h3>No hay registros en esta tabla</h3>
{else}
<div class="data">
  <table id="rounded-corner">
    <thead>
      <tr>
        <th scope="col" class="rounded-company"></th>
        <th scope="col" class="rounded">Categor&iacute;a</th>
        <th scope="col" class="rounded">Edit</th>
        <th scope="col" class="rounded-q4">Elim</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="3" class="rounded-foot-left"><em>Mantemiento de Categor&iacute;a de Libros del Sistema, puede agregar, editar o eliminar alg&uacute;n registro.</em></td>
        <td class="rounded-foot-right">&nbsp;</td>
      </tr>
    </tfoot>
    <tbody>
    {foreach item="data" from="$_result"}
      <tr>
        <td><input type='checkbox' name='id_user' value='{$data.catid}' /></td>
        <td>{$data.catname}</td>
        <td><a class='editCat' href='javascript:void(0);' title='Editar a {$data.catname}' p='{$data.catid}'><img src='{$_layoutParams.ruta_img}user_edit.png' /></a></td>
        <td><a href='javascript:void(0);' class='ask' title='Eliminar a {$data.catname}' onclick="deleteRow('{$data.catname}', '{$data.catid}')"><img src='{$_layoutParams.ruta_img}trash.png' /></a></td></tr>
    {/foreach}
    </tbody>
  </table>  
  <a id='addCat' href='javascript:void(0);' class='bt_green' title='Agregar editorial'><strong>Agregar categor&iacute;a</strong></a>
  <a href='#' class='bt_blue'><span class='bt_blue_lft'></span><strong>View all items from category</strong><span class='bt_blue_r'></span></a>
  <a href='#' class='bt_red'><span class='bt_red_lft'></span><strong>Delete items</strong><span class='bt_red_r'></span></a></div>
  <div class='pagination'>
    <ul>
      {if isset($first_btn) and $cur_page > 1}
      <li p='1' class='active'>Primero</li>
      {elseif isset($first_btn)}
      <li p='1' class='inactive'>Primero</li>
      {/if}
      
      {if isset($previous_btn) and $cur_page > 1}
        {assign var="pre" value="`$cur_page-1`"}
      <li p='{$pre}' class='active'>Anterior</li>
      {elseif isset($previous_btn)}
      <li class='inactive'>Anterior</li>
      {/if}
        
      {section name="i" start="$start_loop" loop="`$end_loop + 1`" step="1"}
        {if $cur_page eq $smarty.section.i.index}
      <li p='{$smarty.section.i.index}' style='color:#fff;background-color:#006699;' class='active'>{$smarty.section.i.index}</li>
        {else}
      <li p='{$smarty.section.i.index}' class='active'>{$smarty.section.i.index}</li>
        {/if}
      {/section}

      {if isset($next_btn) and ($cur_page < $no_of_paginations)}
        {assign var="nex" value="`$cur_page + 1`"}
      <li p='{$nex}' class='active'>Siguiente</li>
      {elseif isset($next_btn)}
      <li class='inactive'>Siguiente</li>
      {/if}

      {if isset($last_btn) and ($cur_page < $no_of_paginations)}
      <li p='{$no_of_paginations}' class='active'>&Uacute;ltimo</li>
      {elseif isset($last_btn)}
      <li p='{$no_of_paginations}' class='inactive'>&Uacute;ltimo</li>
      {/if} 
    </ul> 
    <input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
    <span class='total' a='{$no_of_paginations}'>Page <strong>{$cur_page}</strong> of <strong>{$no_of_paginations}</strong></span> 
  </div>
</div>
{/if}