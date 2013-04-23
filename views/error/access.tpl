<div class="well">
  <h2>{if isset($_mensaje)}{$_mensaje}{/if}</h2>
  <p><a href="{$_layoutParams.root}" title="Ir al inicio">Ir al inicio</a> | <a href="javascript:history.back(1);" title="Ir a la p&aacute;gina anterior">Ir a la p&aacute;gina anterior</a>
  {if Session::get('logged_in') == false}   
    | <a href="{$_layoutParams.root}login" title="Iniciar sesi&oacute;n">Iniciar sesi&oacute;n</a>    
  {/if}
  </p>
</div>