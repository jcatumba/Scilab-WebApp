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

function execute(option){
    if (option=="all") {
        codigo = editor.session.getValue();
        var sendcomand = $.ajax({
            url: "http://localhost:8000/simple/compute",
            type: "GET",
            data: {session:%s, code:2*2, timeout: 60},
            dataType: "json"
        });
        $("#consoleoutput").append(codigo);
    } else if (option=="sel") {
        codigo = editor.session.getTextRange(editor.getSelectionRange());
        $("#consoleoutput").append(codigo);
    }
}
