<h2>Mantenimiento de Categor&iacute;as</h2> 
<!-- Inicio div grid -->
<div id="grid">
</div>
<!-- Fin div grid -->

<div id="loading">
  <img src="{$_layoutParams.ruta_img}ajax-loader.gif" border="0" />
</div>

<div id="frm_addCategories">
  <form id="frmAddCategories" name="frmAddCategories" action="{$_layoutParams.root}categories/verifyAddCategories" method="post">
    <fieldset>
      <dl>
        <dt><label for="txtCategory">Categor&iacute;a:</label></dt>
        <dd>
          <input class="NFText" type="text" name="txtCategory" id="txtCategory" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}categories/validateCategoryAjax');" />
          <div id="txtCategoryFailed"></div>
        </dd>
      </dl>    
      <dl class="submit">
        <input class="NFButton" type="button" name="submit" id="insertCategory" value="Guardar" />
      </dl>
    </fieldset>
  </form>
</div>  

<div id="frm_editCategories">
  <form id="frmEditCategories" name="frmEditCategories" action="{$_layoutParams.root}categories/verifyEditCategory" method="post">
    <fieldset>
      <dl>
        <dt><label for="txtCategoryEdit">Editorial:</label></dt>
        <dd>
          <input class="NFText" type="text" name="txtCategoryEdit" id="txtCategoryEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}categories/validateCategoryAjax');" />
          <div id="txtCategoryEditFailed"></div>
        </dd>
      </dl>    
      <dl class="submit">
        <input class="NFButton" type="button" name="submit" id="editCategory" value="Guardar" />
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