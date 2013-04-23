<div id="login">
    <h3 class="text-center">Login Panel de Administraci&oacute;n</h3>
    <p class="text-right"><a href="#"><i class="icon-question-sign"></i>¿Olvido su password?</a></p>
    {if isset($_error)}
        <div class="alert alert-error">{$_error}</div>
    {/if}
    <form id="frmLogin" name="frmLogin" action="" method="post" class="form-horizontal">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="username">Username:</label>
                <div class="controls">
                    <input type="text" id="username" class="required" name="username" value="{if isset($datos)}{$datos.username}{/if}">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="password">Password:</label>
                <div class="controls">
                    <input type="password" id="password" name="password">
                </div>
            </div>

            <input type="hidden" name="grabar" value="1">
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox">
                        <input type="checkbox" name="interests[]"> Recordarme?
                    </label>
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
