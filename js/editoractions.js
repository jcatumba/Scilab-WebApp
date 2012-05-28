function saveFile(archivo){
    content = editor.session.getValue();
    var request = $.ajax({
        url: "db-interaction/create-delete-file.php",
        type: "POST",
        data: {file : content, directory : archivo, action : "guardar"},
        dataType: "html"
    });
    
    request.done(function() {
        alert("Se ha guardado el archivo.");
    });
    request.fail(function(jqXHR, textStatus) {
        alert("Solicitud fallida (guardar archivo): "+textStatus)
    });
}

function startProcess(shmid){
    var request = $.ajax({
        url: "db-interaction/interactive.php",
        type: "POST",
        data: {action: 'start', shm : shmid},
        dataType: "html"
    });
    
    request.done(function(data) {
        return data;
    });
    request.fail(function(jqXHR, textStatus) {
        alert("Solicitud fallida (iniciar proceso): "+textStatus)
    });
}

function evaluateText(comando,shmid){
    var request = $.ajax({
        url: "db-interaction/interface.php",
        type: "POST",
        data: {command : comando, idshm : shmid},
        dataType: "html"
    });
    
    request.done(function(data) {
        $('#consoleoutput').append(data);
    });
    request.fail(function(jqXHR, textStatus) {
        alert("Se ha producido un error (ejecutar): "+textStatus)
    });
}
