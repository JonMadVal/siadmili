<h2>Mantenimiento de Editoriales</h2> 
<!-- Inicio div grid -->
<div id="grid">
</div>
<!-- Fin div grid -->

<div id="loading">
  <img src="{$_layoutParams.ruta_img}ajax-loader.gif" border="0" />
</div>

<div id="frm_addPublisher">
  <form id="frmAddPublisher" name="frmAddPublisher" action="{$_layoutParams.root}publisher/verifyAddPublisher" method="post" enctype="multipart/form-data">
    <fieldset>
      <dl>
        <dt><label for="txtPublisher">Editorial:</label></dt>
        <dd>
          <input class="NFText" type="text" name="txtPublisher" id="txtPublisher" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}publisher/validatePublisherAjax');" />
          <div id="txtPublisherFailed"></div>
        </dd>
      </dl>    
      <dl>
        <dt><label for="txtDescription">Comentario:</label></dt>
        <dd>
          <textarea id="comments" class="NFTextarea" cols="36" rows="5" name="txtDescription" style="width: 303px; height: 93px;"></textarea>
        </dd>
      </dl>

      <dl class="submit">
        <input class="NFButton" type="button" name="submit" id="insertPublisher" value="Guardar" />
      </dl>
    </fieldset>
  </form>
</div>  

<div id="frm_editPublisher">
  <form id="frmEditPublisher" name="frmEditPublisher" action="{$_layoutParams.root}publisher/verifyEditPublisher" method="post" enctype="multipart/form-data">
    <fieldset>
      <dl>
        <dt><label for="txtPublisherEdit">Editorial:</label></dt>
        <dd>
          <input class="NFText" type="text" name="txtPublisherEdit" id="txtPublisherEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}publisher/validatePublisherAjax');" />
          <div id="txtPublisherEditFailed"></div>
        </dd>
      </dl>    
      <dl>
        <dt><label for="txtDescription">Comentario:</label></dt>
        <dd>
          <textarea id="comments" class="NFTextarea" cols="36" rows="5" name="txtDescription" style="width: 303px; height: 93px;"></textarea>
        </dd>
      </dl>

      <dl class="submit">
        <input class="NFButton" type="button" name="submit" id="editPublisher" value="Guardar" />
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