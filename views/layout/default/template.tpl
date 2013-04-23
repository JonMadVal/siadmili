<!DOCTYPE html>
<html lang="es-ES">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <title>{$titulo|default:"SIADMILI"}</title>
        <link href="{$_layoutParams.ruta_css}niceforms-default.css" rel="stylesheet" type="text/css" />
        <link href="{$_layoutParams.ruta_css}style.css" rel="stylesheet" type="text/css" />
        <!-- Script para cargar css que se encuentran dentro de la carpeta public pero para vista específicas -->
        {if isset($_layoutParams.css) && count($_layoutParams.css)}
            {foreach item=css from=$_layoutParams.css}
                <link rel="stylesheet" type="text/css" media="screen" href="{$css}" />
            {/foreach}
        {/if}

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="{$_layoutParams.ruta_js}ddaccordion.js"></script>
        <script type="text/javascript">
            ddaccordion.init({
            headerclass: "submenuheader", //Shared CSS class name of headers group
            contentclass: "submenu", //Shared CSS class name of contents group
            revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
            mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
            collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
            defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
            onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
            animatedefault: false, //Should contents open by default be animated into view?
            persiststate: true, //persist state of opened contents within browser session?
            toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
            togglehtml: ["suffix", "<img src='http://localhost/libreria/admin/views/layout/default/images/plus.gif' class='statusicon' />", "<img src='http://localhost/libreria/admin/views/layout/default/images/minus.gif' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
            animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
            oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
            //do nothing
        }, 
        onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
        //do nothing
    }
    })
        </script>
        <!-- Script para cargar js que se encuentran dentro de la carpeta de la vista a cargar -->
        {if isset($_layoutParams.js) && count($_layoutParams.js)}
            {foreach item=js from=$_layoutParams.js}
                <script type="text/javascript" src="{$js}"></script>
            {/foreach}
        {/if}
        <!-- Script para cargar js que se encuentran dentro de la carpeta public pero para vista específica -->
        {if isset($_layoutParams.jsPublic) && count($_layoutParams.jsPublic)}
            {foreach item=jsPublic from=$_layoutParams.jsPublic}
                <script type="text/javascript" src="{$jsPublic}"></script>
            {/foreach}
        {/if}
    </head>

    <body>
        <!-- Inicio main_container -->
        <div id="main_container">
            {if Session::get('logged_in')}      
                <!-- Inicio header -->
                <div class="header">
                    <div class="logo">
                        <a href="{$_layoutParams.root}"><img src="{$_layoutParams.ruta_img}logo.gif" alt="" title="" /></a>
                        <p>{$_layoutParams.configs.app_name}</p>
                    </div>
                    <div class="right_header">Bienvenid@, {Session::get('login')},  <a href="{$_layoutParams.configs.url_site}" title="Librer&iacute;a Crisol" target="_blank">Ver frontend</a> | <a href="{$_layoutParams.root}login/logout" class="logout" title="Cerrar sesi&oacute;n">Logout</a></div>
                    <div id="clock_a"></div>
                </div>
                <!-- Fin header -->
            {else}
                <div class="header_login">
                    <div class="logo">
                        <a href="{$_layoutParams.root}"><img src="{$_layoutParams.ruta_img}logo.gif" alt="SIADMILI" title="SIADMILI" /></a>
                        <p>{$_layoutParams.configs.app_slogan}</p>
                    </div>
                </div>
            {/if}

            {if Session::get('logged_in')}
                <!-- Esta parte debemos realizar una comprobacion de si esta logueado el usario para mostrarlo -->
                <!-- Inicio main_content -->
                <div class="main_content">
                    <div class="menu">
                        <ul>
                            {if isset($_layoutParams.menu)}
                                {foreach item=it from=$_layoutParams.menu}
                                    {if isset($_layoutParams.item) && $_layoutParams.item == $it.id}
                                        {assign var="_item_style" value='current'}
                                    {else}
                                        {assign var="_item_style" value=''}
                                    {/if}
                                    <li><a class="{$_item_style}" href="{$it.enlace}">{$it.titulo}</a></li>
                                {/foreach}
                            {/if}
                        </ul>
                    </div>
                    <!-- Fin div menu -->

                    <!-- Inicio center_content -->
                    <div class="center_content">
                        <noscript><p>Para el correcto funcionamiento debe tener el soporte de javascript habilitado</p></noscript>
                        <!-- Inicio left_content -->
                        <div class="left_content">
                            <!-- Inicio sidebar_search -->
                            <div class="sidebar_search">
                                <form>
                                    <input type="text" name="" class="search_input" value="search keyword" onclick="this.value=''" />
                                    <input type="image" class="search_submit" src="{$_layoutParams.ruta_img}search.png" />
                                </form>            
                            </div>
                            <!-- Fin sidebar_search -->
                            <!-- Inicio sidebarmenu -->
                            <div class="sidebarmenu">
                                <a class="menuitem submenuheader" href="">Administraci&oacute;n</a>
                                <div class="submenu">
                                    <ul>
                                        <li><a href="{$_layoutParams.root}acl">Roles</a></li>
                                        <li><a href="{$_layoutParams.root}acl/permisos">Permisos</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                    </ul>
                                </div>
                                <a class="menuitem submenuheader" href="" >Sidebar Settings</a>
                                <div class="submenu">
                                    <ul>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                    </ul>
                                </div>
                                <a class="menuitem submenuheader" href="">Add new products</a>
                                <div class="submenu">
                                    <ul>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                        <li><a href="">Sidebar submenu</a></li>
                                    </ul>
                                </div>
                                <a class="menuitem" href="">User Reference</a>
                                <a class="menuitem" href="">Blue button</a>

                                <a class="menuitem_green" href="">Green button</a>

                                <a class="menuitem_red" href="">Red button</a>

                            </div>
                            <!-- Fin sidebarmenu -->            
                        </div> 
                        <!-- Fin left_content -->
                        <!-- Inicio right_content -->
                        <div class="right_content">
                            <!-- Hasta aquí debemos validar para mostrar esta parte solo cuando se haya logueado el usuario -->
                        {/if}

                        {include file=$_contenido}

                        {if Session::get('logged_in')}
                        </div><!-- end of right content-->    
                    </div>  
                    <!-- Fin center_content -->
                    <div class="clear"></div>
                </div>
                <!-- Fin main_content -->
            {/if}

            {if Session::get('logged_in')}
                <div class="footer">
                    <div class="left_footer">{$_layoutParams.configs.app_name} | Desarrollado por <a href="http://www.mercadolimanorte.com">SICOM S.A</a></div>
                    <div class="right_footer"><a href="http://www.mercadolimanorte.com"><img src="{$_layoutParams.ruta_img}indeziner_logo.gif" alt="SICOM S.A." title="SICOM S.A." /></a></div>
                </div>
            {else}
                <div class="footer_login">
                    <div class="left_footer_login">{$_layoutParams.configs.app_name} | Desarrollado por <a href="http://www.mercadolimanorte.com">SICOM S.A</a></div>
                    <div class="right_footer_login"><a href="http://www.mercadolimanorte.com"><img src="{$_layoutParams.ruta_img}indeziner_logo.gif" alt="SICOM S.A." title="SICOM S.A." /></a></div>
                </div>
            {/if}
        </div>	
        <!-- Fin main_container -->
    </body>
</html>