$(function (){
    $("#description").richText();
    $('#archivo').imageUploader({
        imagesInputName: 'photos',
        preloadedInputName: 'old',
        maxSize: undefined,
        maxFiles: 1
    });

    $("#boton").on("click", function(ev){
        ev.preventDefault();
        var formdata=new FormData();
        var descripcion=$("#description").val();
        var publicacion={
            descripcion: descripcion,
            tipo_id: 2
        }

        var archivos = $("input[type=file]")[0].files;

        for (var i = 0; i < archivos.length; i++) {
            formdata.append('archivos[]', archivos[i]);
        }

        var $images = $('#archivo .uploaded-image img');

        // Usa .map() para crear un array de los valores de src
        var archivos = $images.map(function() {
            return $(this).attr('src');
        }).get();

        formdata.append("archivos",archivos);

        formdata.append('publicacion',JSON.stringify(publicacion));


        $.ajax({
            url: "http://localhost:8000/api/addpublicacion",
            type: 'POST',
            dataType: 'json',
            data: formdata,
            contentType: false,
            processData: false,
            success: function () {
                window.location.href="http://localhost:8000/main";
            },
            error: function (error) {
                alert("Error al crear la historia ya que debe de haber solo 1 archivo");
            }
        });

    })
})