$("#reporte").change(function() {
    var div = $("#reporte_Div");
    var children = div.children();
    var options = $("#reporte");
    var selectedValue = options.val();
    
  
    
    for (let i = 1; i < children.length ; i++) 
    {
        
        console.log(children.eq(i));
        if(selectedValue == i)
        {
            children.eq(i).show();
        }
        else
        {
            children.eq(i).hide();
        }
        
    }
});

