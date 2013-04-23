// contiene una instancia de XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();
// contiene la direcci�n del archivo de lado del servidor 
//var serverAddress = "http://sistcorp.com/seguridad/perfil/validatePerfilAjax";
// cuando se configura como true, muestra mensajes de error detallados
var showErrors = true;
// inicializa la cache para las peticiones de validaci�n 
var cache = new Array();

// crea una instancia XMLHttpRequest
function createXmlHttpRequestObject() {
    // almacenará la referencia al objeto XMLHttpRequest
    var xmlHttp;
    // esto debe funcionar para todos los navegadores excepto IE6 y m�s antiguos
    try {
        // intenta crear el objeto XMLHttpRequest
        xmlHttp = new XMLHttpRequest();
    } catch (e) {
        // asume IE6 o m�s antiguo
        var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
                "MSXML2.XMLHTTP.5.0",
                "MSXML2.XMLHTTP.4.0",
                "MSXML2.XMLHTTP.3.0",
                "MSXML2.XMLHTTP",
                "Microsoft.XMLHTTP");
        // prueba cada id hasta que uno funciona
        for (var i = 0; i < XmlHttpVersions.length && !xmlHttp; i++) {
            try {
                // prueba a crear el objeto XMLHttpRequest
                xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
            } catch (e) {
            } // ignora error potencial
        }
    }
    // devuelve el objeto creado o muestra un mensaje de error
    if (!xmlHttp)
        displayError("Error al crear el objeto XMLHttpRequest.");
    else
        return xmlHttp;
}

// funci�n que muestra un mensaje de error
function displayError($message) {
    // ignora errores si showErrors es falso
    if (showErrors) {
        // devuelve error mostrando Off
        showErrors = false;
        // muestra mensaje error

        alert("Error encontrado: \n" + $message);
        // reintenta validaci�n despu�s de 10 segundos
        setTimeout("validate();", 10000);
    }
}

// la funci�n maneja la validaci�n para todos los campos del formulario
function validate(inputValue, fieldID, serverAddress) {
    // sólo continúa si xmlHttp no está vacío
    if (xmlHttp) {
        // si recibimos parámetros no-null, los añadimos al cache en el
        // formulario del string de petición a enviar al servidor para validación
        if (fieldID) {
            // codifica valores para añadirlos de modo seguro a una petición string HTTP
            inputValue = encodeURIComponent(inputValue);
            fieldID = encodeURIComponent(fieldID);
            // add the values to the queue
            cache.push("inputValue=" + inputValue + "&fieldID=" + fieldID);
        }
        // prueba a conectar al servidor
        try {
            // contin�a s�lo si el objeto MLHttpRequest est� completo o no iniciado
            // y el cache no est� vac�o
            if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0) && cache.length > 0) {
                // configura un nuevo set de par�metros desde el cache
                var cacheEntry = cache.shift();
                // hace una petici�n al servidor para validar los datos extra�dos
                xmlHttp.open("POST", serverAddress, true);
                // Los datos a enviarse est�n codificados como un formulario
                xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlHttp.onreadystatechange = handleRequestStateChange;
                xmlHttp.send(cacheEntry);
            }
        } catch (e) {
            // muestra un error cuando falla la conexi�n al servidor
            displayError(e.toString());
        }
    }
}

// funci�n que maneja la respuesta HTTP
function handleRequestStateChange() {
    // cuando readyState es 4, leemos la respuesta del servidor
    if (xmlHttp.readyState == 4) {
        // contin�a s�lo si el status HTTP es "OK"
        if (xmlHttp.status == 200) {
            try {
                // lee la respuesta del servidor
                readResponse();
            } catch (e) {
                // muestra mensaje de error
                displayError(e.toString());
            }
        } else {
            // muestra mensaje de error
            displayError(xmlHttp.statusText);
        }
    }
}

// lee respuesta del servidor 
function readResponse() {
    // recupera la respuesta del servidor 
    var response = xmlHttp.responseText;
    // �error del servidor?
    if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0)
        throw(response.length == 0 ? "Server error." : response);
    // obtiene la respuesta en formato XML(se asume que la respuesta es XML v�lido)
    responseXml = xmlHttp.responseXML;
    // obtiene el document element
    xmlDoc = responseXml.documentElement;
    result = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
    fieldID = xmlDoc.getElementsByTagName("fieldid")[0].firstChild.data;
    // muestra el error o bien oculta el error
    if (result == 0) {
        $('#' + fieldID + 'Failed').removeClass('ok').removeClass('hidden').addClass('nook');
    } else if (result == 1) {
        $('#' + fieldID + 'Failed').removeClass('nook').removeClass('hidden').addClass('ok');
    }
    // llama a validate() de nuevo, en caso que haya valores en la cache
    setTimeout("validate();", 500);
}

// Método para limpiar los campos de un formulario
function limpiaForm(miForm) {
    // recorremos todos los campos que tiene el formulario
    $(':input', miForm).each(function() {
        var type = this.type;
        var tag = this.tagName.toLowerCase();
        //limpiamos los valores de los campos…
        if (type == 'text' || type == 'password' || tag == 'textarea') {
            this.value = "";
            // excepto de los checkboxes y radios, le quitamos el checked
            // pero su valor no debe ser cambiado
        } else if (type == 'checkbox' || type == 'radio') {
            //this.checked = false;
            // los selects le ponesmos el indice a -
        } else if (tag == 'select') {
            this.selectedIndex = 0;
        }
    });
}

$(document).on("ready", funciones);
function funciones(ev) {
    $(":input:first").focus();
}