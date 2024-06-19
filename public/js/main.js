$(document).ready(function() {
    function attachEvents() {
        $(".like").off("click").on("click", function(ev) {
            ev.preventDefault();
            var id_publicacion = $(this).closest('.post').find(".id").val();
            var likeButton = $(this);
            $.ajax({
                type: 'POST',
                url: '/addlike/' + id_publicacion,
                contentType: 'application/json',
                data: JSON.stringify({ nombre: 'Like' }),
                success: function(response) {
                    likeButton.attr('src', '../imagenes/corazon.png').removeClass("like").addClass("megusta");
                    attachEvents();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $(".megusta").off("click").on("click", function(ev) {
            ev.preventDefault();
            var id_publicacion = $(this).closest('.post').find(".id").val();
            var likeButton = $(this);
            $.ajax({
                type: 'DELETE',
                url: '/removelike/' + id_publicacion,
                contentType: 'application/json',
                data: JSON.stringify({ nombre: "Like" }),
                success: function(response) {
                    likeButton.attr('src', '../imagenes/like.png').removeClass("megusta").addClass("like");
                    attachEvents();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $(".rt").off("click").on("click", function(ev) {
            ev.preventDefault();
            var id_publicacion = $(this).closest('.post').find(".id").val();
            var button = $(this);
            $.ajax({
                type: 'POST',
                url: '/addrt/' + id_publicacion,
                contentType: 'application/json',
                data: JSON.stringify({ nombre: "RT" }),
                success: function(response) {
                    button.attr('src', '../imagenes/retweet.png').removeClass("rt").addClass("rts");
                    attachEvents();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $(".rts").off("click").on("click", function(ev) {
            ev.preventDefault();
            var id_publicacion = $(this).closest('.post').find(".id").val();
            var button = $(this);
            $.ajax({
                type: 'DELETE',
                url: '/removert/' + id_publicacion,
                contentType: 'application/json',
                data: JSON.stringify({ nombre: "RT" }),
                success: function(response) {
                    button.attr('src', '../imagenes/retweet.png').removeClass('rts').addClass("rt");
                    attachEvents();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $(".guardado").off("click").on("click", function(ev) {
            ev.preventDefault();
            var id_publicacion = $(this).closest('.post').find(".id").val();
            var guardadoButton = $(this);
            $.ajax({
                type: 'POST',
                url: '/addguardado/' + id_publicacion,
                contentType: 'application/json',
                data: JSON.stringify({ nombre: "Guardado" }),
                success: function(response) {
                    guardadoButton.attr('src', '../imagenes/guardar.png').removeClass('guardado').addClass('guardadoo');
                    attachEvents();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $(".guardadoo").off("click").on("click", function(ev) {
            ev.preventDefault();
            var id_publicacion = $(this).closest('.post').find(".id").val();
            var guardadoButton = $(this);
            $.ajax({
                type: 'DELETE',
                url: '/removeguardado/' + id_publicacion,
                contentType: 'application/json',
                data: JSON.stringify({ nombre: "Guardado" }),
                success: function(response) {
                    guardadoButton.attr('src', '../imagenes/guardado.png').removeClass('guardadoo').addClass('guardado');
                    attachEvents();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    }

    attachEvents();

    $(".comentario").on("click", function(ev){
        ev.preventDefault();
        var id_publicacion = $(this).parent().parent().parent().find(".id").val();
        $.getJSON("http://localhost:8000/apis/comentarios/"+id_publicacion, function(data,status){
            creaModal(ev, data,id_publicacion);
        });
    });

    $(".user-circle").on("click", function(ev){
        ev.preventDefault();
        var id_historia=$(this).find(".id").val();
        creaModalHistoria(ev,id_historia);
    });
});


function creaModal(ev, comentarios,id_publicacion) {
    ev.preventDefault();
    var contenedor = $("<div></div>");
    contenedor.css({
        "width": "100%",
        "height": "100%"
    });

    var modal = $("<div></div>");
    modal.css({
        "position": "fixed",
        "left": "0",
        "top": "0",
        "width": "100%",
        "height": "100%",
        "backgroundColor": "rgba(0,0,0,0.5)",
        "zIndex": 99
    });
    $("body").append(modal);

    var visualizador = $("<div></div>");
    visualizador.css({
        "position": "fixed",
        "left": "15%",
        "top": "15%",
        "width": "70%",
        "height": "70%",
        "overflowY": "auto",
        "backgroundColor": "white",
        "zIndex": 100
    });
    $("body").append(visualizador);
    visualizador.append(contenedor);

    var closer = $("<span>X</span>");
    closer.css({
        "padding": "5px",
        "position": "fixed",
        "top": "0",
        "right": "0",
        "zIndex": 101
    });
    $("body").append(closer);

    closer.click(function () {
        modal.remove();
        visualizador.remove();
        closer.remove();
    });

    var div = $("<div></div>");

    var input = $("<input>");
    input.css({
        "width": "40%",
        "height": "50px",
        "border": "1px solid black",
        "borderRadius": "10px",
        "marginTop": "10px",
        "marginLeft": "10px",
        "marginRight": "10px",
        "marginBottom": "10px"
    });
    input.attr({
        "type": "text",
        "placeholder": "Introduzca su mensaje"
    });
    visualizador.append(input);

    var boton = $("<input>");
    boton.css({
        "width": "40%",
        "height": "50px",
        "border": "1px solid black",
        "borderRadius": "10px",
        "marginTop": "10px",
        "marginLeft": "10px",
        "marginRight": "10px",
        "marginBottom": "10px"
    });
    boton.attr({
        "type": "button",
        "value": "Enviar",
        "disabled": true
    });

    div.append(input);
    div.append(boton);

    contenedor.append(div);

    input.on('input', function() {
        const value = $(this).val();
        if (value.trim() === '') {
            boton.prop('disabled', true);
        } else {
            boton.prop('disabled', false);
        }
    });

    boton.on("click", function(){
        if (input.val()==""){

        }else{
            $.ajax({
                type: 'POST',
                url: '/addcomentario/'+id_publicacion,
                data: JSON.stringify({mensaje: input.val()}),
                success: function(response) {
                    alert("Comentario añadido");
                },
                error: function(xhr, status, error) {
                    
                }
            });
        }
        
    });

    for (let i = 0; i < comentarios.length; i++) {
        var comentario = $("<div></div>");
        comentario.addClass("comment");
        var datosuser = $("<div></div>");
        datosuser.css({
            "display": "flex"
        });
        var imagen = $("<img>");
        imagen.css({
            "width": "10%",
            "borderRadius": "50%",
            "border": "1px solid black"
        });
        if (comentarios[i].usuario.foto) {
            imagen.attr("src", "../imagenes/" + comentarios[i].usuario.foto);
        } else {
            imagen.attr("src", "../imagenes/perfil.png");
        }
        var usuario = $("<div></div>");
        usuario.css({
            "marginLeft": "3%",
            "marginTop": "2%"
        });
        usuario.addClass("username");
        usuario.html(comentarios[i].usuario.usuario);
        var textocomentario = $("<p></p>");
        textocomentario.html(comentarios[i].texto);
        contenedor.append(comentario);
        comentario.append(datosuser);
        datosuser.append(imagen);
        datosuser.append(usuario);
        comentario.append(textocomentario);
    }
}

function creaModalHistoria(ev, id_historia) {
    ev.preventDefault();
    var contenedor = $("<div></div>");
    contenedor.css({
        "width": "100%",
        "height": "100%"
    });

    var modal = $("<div></div>");
    modal.css({
        "position": "fixed",
        "left": "0",
        "top": "0",
        "width": "100%",
        "height": "100%",
        "backgroundColor": "rgba(0,0,0,0.5)",
        "zIndex": 99
    });
    $("body").append(modal);

    var visualizador = $("<div></div>");
    visualizador.css({
        "position": "fixed",
        "left": "35%",
        "top": "15%",
        "width": "30%",
        "height": "70%",
        "backgroundColor": "white",
        "zIndex": 100
    });
    $("body").append(visualizador);
    visualizador.append(contenedor);

    var closer = $("<span>X</span>");
    closer.css({
        "padding": "5px",
        "position": "fixed",
        "top": "0",
        "right": "0",
        "zIndex": 101
    });
    $("body").append(closer);

    closer.click(function () {
        modal.remove();
        visualizador.remove();
        closer.remove();
    });

    var div = $("<div></div>");

    var anterior=$("<span></span>").html("&#9664;").addClass("prev-arrow");
    div.append(anterior);
    var siguiente=$("<span></span>").html("&#9654;").addClass("next-arrow");
    div.append(siguiente);
    div.css({
        "position": "absolute",
        "top": "0",
        "left": "0",
        "width": "100%",
        "height": "100%",
        "overflow": "hidden"
    });

    var fotos=[];
    var indice=0;

    $.getJSON("http://localhost:8000/historia/"+id_historia, function(data) {
        fotos=data[0].fotos;
        console.log(fotos);
        var img=$("<img></img>").attr("src","../imagenes/"+fotos[0]);
        img.css({
            "width": "100%",
            "height": "100%",
            "object-fit": "contain"
        });
        div.append(img);
        anterior.on("click", function(){
            indice--;
            if (indice==-1){
                img.attr("src","../imagenes/"+fotos[fotos.length-1]);
                indice=fotos.length-1;
            }else{
                img.attr("src","../imagenes/"+fotos[indice]);
            }
        });
        siguiente.on("click", function(){
            indice++;
            if ((indice+1)>fotos.length){
                img.attr("src","../imagenes/"+fotos[0]);
                index=0;
            }else{
                img.attr("src","../imagenes/"+fotos[indice]);
            }
        });
    });

    


    contenedor.append(div);
}

