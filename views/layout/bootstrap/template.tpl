<!DOCTYPE html>
<html lang="es-ES">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <title>{$titulo|default:"SIADMILI"}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{$_layoutParams.ruta_css}bootstrap.css" rel="stylesheet" media="screen">
        <link href="{$_layoutParams.ruta_css}bootstrap-responsive.css" rel="stylesheet" media="screen">
        <link href="{$_layoutParams.ruta_css}style.css" rel="stylesheet" type="text/css" />        

        <!-- Script para cargar css que se encuentran dentro de la carpeta public pero para vista específicas -->
        {if isset($_layoutParams.css) && count($_layoutParams.css)}
            {foreach item=css from=$_layoutParams.css}
                <link rel="stylesheet" type="text/css" media="screen" href="{$css}" />
            {/foreach}
        {/if}
    </head>

    <body>
        <!-- Inicio container-fluid -->
        <div class="container-fluid">
            <!-- Inicio row-fluid para header -->
            <div class="row-fluid">
                <header class="span12">
                    <div class="row-fluid">
                        <div class="span3">
                            <h1><a href="{$_layoutParams.root}" title="SIADMILI"><span>{$_layoutParams.configs.app_name}</span></a></h1>
                            <p>{$_layoutParams.configs.app_slogan}</p>
                        </div>
                        {if Session::get('logged_in')}
                            <div class="span9">
                                <p class="text-right">Bienvenid@, {Session::get('login')},  <a href="{$_layoutParams.configs.url_site}" title="Librer&iacute;a Crisol" target="_blank">Ver frontend</a> | <a href="{$_layoutParams.root}login/logout" class="logout" title="Cerrar sesi&oacute;n">Logout</a></p>
                            </div>
                        {/if}
                    </div>
                </header>
            </div>
            <!-- Fin row-fluid para header -->

            {if Session::get('logged_in')}
                {if isset($widgets.menu)}
                    {foreach $widgets.menu as $wd}
                        {$wd}                
                    {/foreach}
                {/if}
                <!-- Inicio row-fuid para sidebar left -->
                <div class="row-fluid">
                    <!-- Inicio sidebar_left -->
                    <aside class="span3 well" id="sidebar_left">
                        {if ($_acl->permiso('admin_access'))}
                            {if isset($widgets.sidebar)}
                                {foreach $widgets.sidebar as $sb}
                                    {$sb}
                                {/foreach}
                            {/if}
                        {/if}
                    </aside>  
                    <!-- Fin sidebar_left -->
                    <!-- Inicio content -->
                    <section class="span9 well" id="content">
                        {include file=$_contenido}
                    </section>
                    <!-- Fin content -->
                </div> 
                <!-- Fin row-fuid para sidebar left -->
            {else}
                <section class="row-fluid">
                    <article class="span4 offset4">
                        {include file=$_contenido}
                    </article>
                </section>
            {/if}

            <!-- Inicio row-fluid para footer -->
            <footer class="row-fluid">
                <div class="span6">
                    <p><small>{$_layoutParams.configs.app_name} | Desarrollado por <a href="http://sicom.site44.com" title="SICOM" target="_blank">SICOM</a></small></p>
                </div>
                <div class="span6" id="copyright">
                    <p class="text-right"><a href="http://sicom.site44.com" title="SICOM" target="_blank"><img src="{$_layoutParams.ruta_img}logo.png" alt="SICOM" title="SICOM" /></a></p>
                </div>
            </footer>
            <!-- Inicio row-fluid para footer -->
        </div>
        <!-- Fin container-fluid -->

        <!-- Cargamos script js -->
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="{$_layoutParams.ruta_js}bootstrap.js"></script>
        <!-- Cargamos script para trabajar con validacion jqueryValidation -->
        {if isset($_validation) && $_validation == 'TRUE'}
            <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
            <script type="text/javascript" src="{$_layoutParams.ruta_js}messages_es.js"></script>
        {/if}

        <script type="text/javascript">
            var _root_ = "{$_layoutParams.root}";
        </script>

        <!-- Script para cargar js que se encuentran dentro de la carpeta public pero para vista específica -->
        {if isset($_layoutParams.jsPlugin) && count($_layoutParams.jsPlugin)}
            {foreach $_layoutParams.jsPlugin as $jsPlugin}
                <script type="text/javascript" src="{$jsPlugin}"></script>
            {/foreach}
        {/if}

        <!-- Script para cargar js que se encuentran dentro de la carpeta de la vista a cargar -->
        {if isset($_layoutParams.js) && count($_layoutParams.js)}
            {foreach $_layoutParams.js as $js}
                <script type="text/javascript" src="{$js}"></script>
            {/foreach}
        {/if}
    </body>
</html>