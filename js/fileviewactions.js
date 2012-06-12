function openFile(){
    if ($('input:checked').length>1){
        alert('No puede abrir más de un archivo a al vez.');
    } else {
        var file = $('input:checked').val();
        location.href = "editor.php?file="+file;
    }
}

function downloadFiles(directorio){

    if ($('input:checked').length==1){
        location.href = directorio+"/"+$('input:checked').val();
    } else {
        var request;
        $('input:checked').each(function (){
            request = $.ajax({
                url: "db-interaction/create-delete-file.php",
                type: "POST",
                data: {file: $(this).val(), directory : directorio, action : "comprimir"},
                dataType: "html"
            });
        });
        request.done(function() {
            alert("Se ha efectuado la solicitud.");
            location.href = directorio+"/files.zip";
        });
        request.fail(function(jqXHR, textStatus) {alert("Solicitud fallida (comprimir): "+textStatus)});
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
    
    request.fail(function(jqXHR, textStatus) {alert("Solicitud fallida (crear archivo): "+textStatus)});
}

function deleteFiles(directorio) {
    var request;
    $('input:checked').each(function (){
        request = $.ajax({
            url: "db-interaction/create-delete-file.php",
            type: "POST",
            data: {file : $(this).val(), directory : directorio, action : "borrar"},
            dataType: "html"
        });
    });
    request.done(function() {
        alert("Se han borrado los archivos.");
        location.reload();
    });
    
    request.fail(function(jqXHR, textStatus) {alert("Solicitud fallida (borrar archivos): "+textStatus)});
}
