$(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var empleados       = $(".empleados"); //Add button ID  
    var lista_empleados = "";
    
    $('.empleados').each(function(i, obj) {
        console.log(this.innerText );
        lista_empleados += "<option value="+this.innerText+">"+this.innerText+"</option>\n";
    });

    var x = 1; //initlal text box count
    $(add_button).click(function(e)
    { //on add input button click
        e.preventDefault();
        if(x < max_fields)//max input box allowed
        { 
            // <input type="text" name="Descripcion_'+x+'" required style="width: 300px;height: 100px;">\

            $(wrapper).append(
                '<div class="input_Productos Productos" id=nuevo_producto_'+x+'>\
                    <p>Descripcion <span class="reqq">*</span></p>\
                    <textarea name="Descripcion_'+x+'" cols=40 rows=4 required></textarea>\
                    <br>\
                    <p >Area de produccion <span class="reqq">*</p></span>\
                    <select name="produccion_'+x+'" required>'+lista_empleados+' \
                    </select>\
                    <p>Cantidad <span class="reqq">*</span></p>\
                    <input type="number" name="cantidad_'+x+'" required>\
                    <br><br>\
                    <label for="P_Unit">P. Unit <span class="reqq">*</span></label>\
                    <input type="number" name="Punit_'+x+'" required step=".01">\
                    <label for="importe">Importe <span class="reqq">*</span></label>\
                    <input type="number" name="importe_'+x+'" required step=".01">\
                    <br><br>\
                    <label for="statuss">Status <span class="reqq">*</span></label>\
                    <select name="statuss_'+x+'" required>\
                        <option value="Diseño">Diseño</option>\
                        <option value="Produccion">Produccion</option>\
                        <option value="Espera">Espera</option>\
                        <option value="Finalizado">Finalizado</option>\
                    </select>\
                    <br><br>\
                    <h3>Referencias</h3>\
                    <input type="file" name="file_REF_'+x+'[]" value="Añadir referencia" multiple></input>\
                    <br><br>\
                    <h3>Disenos</h3>\
                    <input type="file" name="file_DIS_'+x+'[]" value="Añadir diseno" multiple></input>\
                    <br><br>\
                    <div class="input-group-append">\
                        <button class=" remove_field" type="button">Remover Producto</button>\
                    </div></div>'); //add input box
            x++; //text box increment
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
        })
    });