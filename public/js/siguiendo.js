$(function() {
    $(document).on("click", ".btn-siguiendo", function(ev) {
        ev.preventDefault();
        var boton = $(this);
        $.ajax({
            type: 'DELETE',
            url: '/removeseguidor/' + $("#id").val(),
            success: function(data, status) {
                boton.removeClass("btn-siguiendo").addClass("btn-follow").val("Seguir");
                var seguidores = parseInt($("#seguidores").text()) - 1;
                $("#seguidores").text(seguidores);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $(document).on("click", ".btn-follow", function(ev) {
        ev.preventDefault();
        var btn = $(this);
        $.ajax({
            type: 'POST',
            url: '/addseguidor/' + $("#id").val(),
            success: function(data, status) {
                btn.removeClass("btn-follow").addClass("btn-siguiendo").val("Siguiendo");
                var seguidores = parseInt($("#seguidores").text()) + 1;
                $("#seguidores").text(seguidores);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $("#seguidores").on("click", function(ev) {
        $.getJSON("/mostrarseguidores/" + $("#id").val(), function(seguidores) {
            $.getJSON("/mostrarsiguiendo/" +$("#idlogueado").val(), function(siguiendomios){
                creaModal(ev, seguidores,siguiendomios);
            });
        });
    });

    $("#siguiendo").on("click", function(ev) {
        $.getJSON("/mostrarsiguiendo/" + $("#id").val(), function(siguiendo) {
            creaModal(ev, siguiendo);
        });
    });
});

function creaModal(ev, seguidores,siguiendo_logueado) {
    ev.preventDefault();

    // Crear contenedor del modal
    var modal = $("<div></div>").css({
        "position": "fixed",
        "left": "0",
        "top": "0",
        "width": "100%",
        "height": "100%",
        "backgroundColor": "rgba(0,0,0,0.5)",
        "zIndex": 99,
        "display": "flex",
        "justifyContent": "center",
        "alignItems": "center"
    }).appendTo("body");

    // Crear visualizador del modal
    var visualizador = $("<div></div>").css({
        "width": "400px",
        "height": "70%",
        "backgroundColor": "white",
        "borderRadius": "10px",
        "overflowY": "auto",
        "position": "relative",
        "padding": "20px"
    }).appendTo(modal);

    // Crear botón de cerrar
    var closer = $("<span>&times;</span>").css({
        "position": "absolute",
        "top": "10px",
        "right": "20px",
        "fontSize": "24px",
        "cursor": "pointer"
    }).appendTo(visualizador).click(function() {
        modal.remove();
    });

    // Crear encabezado del modal
    var header = $("<div>Seguidores</div>").css({
        "textAlign": "center",
        "fontSize": "20px",
        "padding": "10px 0",
        "borderBottom": "1px solid #ddd",
        "marginBottom": "10px"
    }).appendTo(visualizador);

    // Crear contenedor de seguidores
    var contenedorSeguidores = $("<div></div>").appendTo(visualizador);

    // Añadir seguidores al contenedor
    seguidores.forEach(seguidor => {
        var followerDiv = $("<div></div>").css({
            "display": "flex",
            "alignItems": "center",
            "padding": "10px 0",
            "borderBottom": "1px solid #ddd",
            "justifyContent": "space-between"
        }).appendTo(contenedorSeguidores);

        var followerInfo = $("<div></div>").css({
            "display": "flex",
            "alignItems": "center"
        }).appendTo(followerDiv);

        var followerImage = $("<img>").attr("src", "../imagenes/" + (seguidor.foto || "perfil.png")).css({
            "width": "40px",
            "height": "40px",
            "borderRadius": "50%",
            "marginRight": "10px"
        }).appendTo(followerInfo);

        var followerName = $("<div></div>").text(seguidor.nombre).css({
            "fontSize": "16px"
        }).appendTo(followerInfo);

        var sigue=Array.from(siguiendo_logueado).some(siguiendo=>siguiendo.id==seguidor.id);

        if (sigue){
            var siguiendoButton= $("<input type='button' value='Siguiendo'/>").css({
                "backgroundColor": "#EAEDED",
                "color": "#262626",
                "border": "1px solid #dbdbdb",
                "borderRadius": "8px",
                "padding": "8px 16px",
                "fontSize": "14px",
                "fontWeight": "bold",
                "cursor": "pointer",
                "outline": "none",
                "transition": "background-color 0.3s, color 0.3s, border-color 0.3s",
                "marginLeft": "20%"
            }).appendTo(followerDiv).on("click", function(ev){
                    ev.preventDefault();
                    var boton = $(this);
                    $.ajax({
                        type: 'DELETE',
                        url: '/removeseguidor/' + seguidor.id,
                        success: function(data, status) {
                            boton.css({
                                'background-color': '#0095f6', // Color de fondo
                                'color': 'white', // Color del texto
                                'border': 'none', // Sin borde
                                'border-radius': '8px', // Bordes redondeados
                                'padding': '8px 16px', // Espaciado interior
                                'font-size': '14px', // Tamaño del texto
                                'font-weight': 'bold', // Negrita
                                'cursor': 'pointer', // Cambio de cursor al pasar
                                'outline': 'none', // Sin contorno al hacer clic
                                'transition': 'background-color 0.3s', // Transición suave del color de fondo
                                'margin-left': '20%' // Margen izquierdo
                            }).val('Seguir');
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });
        }else{
            var seguirButton = $("<input type='button' value='Seguir'/>").css({
                'background-color': '#0095f6', // Color de fondo
                'color': 'white', // Color del texto
                'border': 'none', // Sin borde
                'border-radius': '8px', // Bordes redondeados
                'padding': '8px 16px', // Espaciado interior
                'font-size': '14px', // Tamaño del texto
                'font-weight': 'bold', // Negrita
                'cursor': 'pointer', // Cambio de cursor al pasar
                'outline': 'none', // Sin contorno al hacer clic
                'transition': 'background-color 0.3s', // Transición suave del color de fondo
                'margin-left': '20%' // Margen izquierdo
            }).appendTo(followerDiv).click(function() {
                var btn = $(this);
                $.ajax({
                    type: 'POST',
                    url: '/addseguidor/' + seguidor.id,
                    success: function(data, status) {
                        btn.css({
                            "backgroundColor": "#EAEDED",
                            "color": "#262626",
                            "border": "1px solid #dbdbdb",
                            "borderRadius": "8px",
                            "padding": "8px 16px",
                            "fontSize": "14px",
                            "fontWeight": "bold",
                            "cursor": "pointer",
                            "outline": "none",
                            "transition": "background-color 0.3s, color 0.3s, border-color 0.3s",
                            "marginLeft": "20%"
                        }).val('Siguiendo');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        }

        
    });
}
