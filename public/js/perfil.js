$(function(){
    $("#seguidoo").on("click", function(ev){
        $.getJSON("/mostrarsiguiendo/" + $("#user").val(), function(mios) {
            creaModal(ev, mios);
        });
    });

    $("#segui").on("click", function(ev){
        $.getJSON("/mostrarseguidores/" + $("#user").val(), function(seguidores) {
            creaModal(ev, seguidores);
        });
    });
});

function creaModal(ev, seguidores) {
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
    });
}
