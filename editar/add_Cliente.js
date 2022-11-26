$(document).ready(function() {
    var wrapper   		= $(".cliente_fields_wrap"); //Fields wrapper
    var add_button_cliente      = $(".add_field_button_cliente"); //Add button ID
    var prev_Clientes   = $(".prev_Clientes"); //Lista de clientes previos

    $(add_button_cliente).click(function(e)
    { //on add input button click
        e.preventDefault();
        
           
        $(wrapper).append(
            '<p style="display: inline-block;">Nombre <span class="reqq">*</span></p>\
            <input type="text" name="nombre" required>\
            <br>\
            <p style="display: inline-block;">Correo <span class="reqq">*</span></p>\
            <input type="text" name="correo" required>\
            <p>Empresa</p>\
            <input type="text" name="empresa" >\
            <br><br>\
            <label for="Telefono" >Telefono </label>\
            <input type="text" name="telefono">\
            <label for="Celular" >Celular </label>\
            <input type="text" name="celular">\
                </div></div>'); //add input box

        prev_Clientes.remove();
        add_button_cliente.remove();
      
    });
    
    
});