$(function(){
    var index=0;
    $(".next-arrow").on("click", function(){
        var id=$(this).parent().parent().find(".id").val();
        var archivos=[];
        var boton=$(this);
        $.getJSON("http://localhost:8000/apis/publicacion/"+id,function(data){
            archivos=data.foto;
            console.log(archivos);
        
            index++;

            if ((index+1)>archivos.length){
                boton.parent().find(".image-slide").find("img").attr("src","../imagenes/"+archivos[0]);
                index=0;
            }else{
                boton.parent().find(".image-slide").find("img").attr("src","../imagenes/"+archivos[index]);
            }

            var image = boton.parent().find(".image-slide").find("img")[0];
            var aspectRatio = image.naturalWidth / image.naturalHeight;

            if (aspectRatio > 1) {
                image.classList.add('wide');
                image.classList.remove('tall');
            } else {
                image.classList.add('tall');
                image.classList.remove('wide');
            }
        });
    });

    $(".prev-arrow").on("click", function(){
        var id=$(this).parent().parent().find(".id").val();
        var archivos=[];
        var boton=$(this);
        $.getJSON("http://localhost:8000/apis/publicacion/"+id,function(data){
            archivos=data.foto;
            console.log(archivos);
        
            index--;

            if (index==-1){
                boton.parent().find(".image-slide").find("img").attr("src","../imagenes/"+archivos[archivos.length-1]);
                index=archivos.length-1;
            }else{
                boton.parent().find(".image-slide").find("img").attr("src","../imagenes/"+archivos[index]);
            }
        });
    });
});

