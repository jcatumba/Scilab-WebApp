function openFile(directorio){
    if ($('input:checked').length>1){
        alert('No puede abrir más de un archivo a al vez.');
    } else {
        var file = $('input:checked').val();
        location.href = "editor.php?file=\""+directorio+"/"+file+"\"";
    }
}

function createNewFile(directorio) {
    var filename = prompt("Inserte el nombre del archivo");
    var request = $.ajax({
        url: "db-interaction/create-delete-file.php",
        type: "POST",
        data: {file : filename, directory: directorio, action : "crear"},
        dataType: "html"
    });
    
    request.done(function() {
        alert("Se ha creado el archivo "+filename);
        location.reload();
    });
    
    request.fail(function(jqXHR, textStatus) {alert("Solicitud fallida: "+textStatus)});
}

function deleteFiles(directorio) {
    $('input:checked').each(function (){
        var request = $.ajax({
            url: "db-interaction/create-delete-file.php",
            type: "POST",
            data: {file : $(this).val(), directory : directorio, action : "borrar"},
            dataType: "html"
        });
        
        request.done(function() {
            alert("Se han borrado los archivos.");
            location.reload();
        });
    
        request.fail(function(jqXHR, textStatus) {alert("Solicitud fallida: "+textStatus)});
    });
}
