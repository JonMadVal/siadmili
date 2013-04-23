<h2 class="text-center">Mantenimiento de Usuarios</h2>
<div class="well well-small">
    <form name="frmSearchUser" id="frmSearchUser" class="form-inline">
        <label for="txtNombre">Nombres: </label><input type="text" class="input-medium" name="txtNombre" id="txtNombre">
        <label for="txtApaterno">Ape. Paterno: </label><input type="text" class="input-medium" name="txtApaterno" id="txtApaterno">
        <label for="txtAmaterno">Ape. Materno: </label><input type="text" class="input-medium" name="txtAmaterno" id="txtAmaterno">        
        <button type="button" id="btnEnviar" class="btn"><i class="icon-search"></i></button>
    </form>
</div>
<!-- Inicio div grid -->
<div id="grid">
    {if isset($users) && count($users)}
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
                        <td><a id='dataUser' href='javascript:void(0);' title='Editar a {$data.login}' data-userid='{$data.userID}'><i class="icon-edit"></i></a></td>
                        <td><a href='{$_layoutParams.root}usuarios/permisos/{$data.userID}' title='Permisos'><i class="icon-tasks"></i></a></td>
                        <td><a href='javascript:void(0);' title='Eliminar a {$data.login}' onclick="deleteRow('{$data.login}', '{$data.userID}')"><i class="icon-trash"></i></a></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>  
        <a id="addUser" href="javascript:void(0);" class="btn btn-primary" title="Agregar usuario"><strong>Agregar usuario</strong></a>
        <a id="delUsers" href="javascript:void(0);" class="btn btn-danger" title="Eliminar items">Delete items</a>
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

<!--
<div id="frm_addUser">
  <form id="frmAddUser" name="frmAddUser" action="{$_layoutParams.root}usuarios/verifyAddUser" method="post" enctype="multipart/form-data">
    <fieldset>
      <dl>
        <dt><label for="name">Nombre:</label></dt>
        <dd>
          <input class="NFText" type="text" name="name" id="name" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
          <div id="nameFailed"></div>
        </dd>
      </dl>
      <dl>
        <dt><label for="apaterno">Ap. Paterno:</label></dt>
        <dd>
          <input class="NFText" type="text" name="apaterno" id="apaterno" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
          <div id="apaternoFailed"></div>
        </dd>
      </dl>
      <dl>
        <dt><label for="amaterno">Ap. Materno:</label></dt>
        <dd>
          <input class="NFText" type="text" name="amaterno" id="amaterno" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
          <div id="amaternoFailed"></div>
        </dd>
      </dl>
      <dl>
        <dt><label for="login">Login:</label></dt>
        <dd>
          <input class="NFText" type="text" name="login" id="login" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
          <div id="loginFailed"></div>
        </dd>
      </dl>
      <dl id="dlPassword">
        <dt><label for="password">Password:</label></dt>
        <dd>
          <input class="NFText" type="password" name="password" id="password" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
          <div id="passwordFailed"></div>
        </dd>
      </dl>
      <dl id="dlRePassword">
        <dt><label for="re-password">Repetir Password:</label></dt>
        <dd>
          <input class="NFText" type="password" name="re-password" id="re-password" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
          <div id="re-passwordFailed"></div>
        </dd>
      </dl>
      <dl>
        <dt><label for="email">Email:</label></dt>
        <dd>
          <input class="NFText" type="text" name="email" id="email" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
          <div id="emailFailed"></div>
        </dd>
      </dl>
      <dl>
        <dt><label for="telefono">Tel&eacute;fono:</label></dt>
        <dd>
          <input class="NFText" type="text" name="telefono" id="telefono" size="54" />
          <div id="telefonoFailed"></div>
        </dd>
      </dl>
      <dl>
        <dt><label for="upload">Avatar:</label></dt>
        <dd><input type="file" name="avatar" id="avatar" />
        </dd>
      </dl>
      <dl>
        <dt><label for="level">Nivel de acceso:</label></dt>
        <dd>
          <select id="level" class="NFSelect" size="1" name="level" onchange="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');">
            <option value="0">Seleccione el nivel</option>
{foreach item="data" from="$_level"}
  <option value="{$data.id_level}">{$data.role}</option>
{/foreach}
?>
</select>
<div id="levelFailed"></div>
</dd>
</dl>

<dl>
<dt><label for="comments">Comentario:</label></dt>
<dd>
<textarea id="comments" class="NFTextarea" cols="36" rows="5" name="comments" style="width: 303px; height: 93px;"></textarea>
</dd>
</dl>

<dl class="submit">
<!-- onclick="doAjax('<?php //echo BASE_URL . 'usuarios/addUser'    ?>', '', 'mostrarForm', 'POST', '0');"--
<input class="NFButton" type="button" name="submit" id="insertUser" value="Guardar" />
</dl>
</fieldset>
</form>
</div>  

<div id="frm_editUser">
<form id="frmEditUser" name="frmEditUser" action="{$_layoutParams.root}usuarios/verifyEditUser" method="post" enctype="multipart/form-data">
<fieldset>
<dl>
<dt><label for="nameEdit">Nombre:</label></dt>
<dd>
  <input class="NFText" type="text" name="nameEdit" id="nameEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
  <div id="nameEditFailed"></div>
</dd>
</dl>
<dl>
<dt><label for="apaternoEdit">Ap. Paterno:</label></dt>
<dd>
  <input class="NFText" type="text" name="apaternoEdit" id="apaternoEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
  <div id="apaternoEditFailed"></div>
</dd>
</dl>
<dl>
<dt><label for="amaternoEdit">Ap. Materno:</label></dt>
<dd>
  <input class="NFText" type="text" name="amaternoEdit" id="amaternoEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
  <div id="amaternoEditFailed"></div>
</dd>
</dl>
<dl>
<dt><label for="loginEdit">Login:</label></dt>
<dd>
  <input class="NFText" type="text" name="loginEdit" id="loginEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
  <div id="loginEditFailed"></div>
</dd>
</dl>
<dl>
<dt><label for="emailEdit">Email:</label></dt>
<dd>
  <input class="NFText" type="text" name="emailEdit" id="emailEdit" size="54" onblur="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');" />
  <div id="emailEditFailed"></div>
</dd>
</dl>
<dl>
<dt><label for="telefonoEdit">Tel&eacute;fono:</label></dt>
<dd>
  <input class="NFText" type="text" name="telefonoEdit" id="telefonoEdit" size="54" />
</dd>
</dl>
<dl>
<dt><label for="avatarEdit">Avatar:</label></dt>
<dd><input type="file" name="avatarEdit" id="avatarEdit" />
</dd>
</dl>
<dl>
<dt><label for="levelEdit">Nivel de acceso:</label></dt>
<dd>
  <select id="levelEdit" class="NFSelect" size="1" name="levelEdit" onchange="validate(this.value, this.id, '{$_layoutParams.root}usuarios/validateUserAjax');">
    <option value="0">Seleccione el nivel</option>
{foreach item="data" from="$_level"}
  <option value="{$data.id_level}">{$data.role}</option>
{/foreach}
</select>
<div id="levelEditFailed"></div>
</dd>
</dl>

<dl>
<dt><label for="commentsEdit">Comentario:</label></dt>
<dd>
<textarea id="commentsEdit" class="NFTextarea" cols="36" rows="5" name="commentsEdit" style="width: 303px; height: 93px;"></textarea>
</dd>
</dl>
<input type="hidden" id="id" name="id" value="" />
<input type="hidden" id="hdLogin" name="hdLogin" value="" />
<input type="hidden" id="hdEmail" name="hdEmail" value="" />
<input type="hidden" id="hdAvatar" name="hdAvatar" value="" />
<dl class="submit">
<!-- onclick="doAjax('<?php //echo BASE_URL . 'usuarios/addUser'    ?>', '', 'mostrarForm', 'POST', '0');"--
<input class="NFButton" type="button" name="submit" id="editUser" value="Guardar" />
</dl>
</fieldset>
</form>
</div>  -->
<div id="exito" class="valid_box informe">
    <p></p>
</div>
<div id="error" class="error_box informe">
    <p></p>
</div>  