$(function() {
    $("#searchButton").on("click", function(ev){
        ev.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/usuarios/'+$("#searchInput").val().trim(),
            success: function(data,status) {
                debugger;
                if (status=="success"){
                    var resultados=$("#results");
                    resultados.empty(); // Vaciar resultados antes de agregar nuevos usuarios
                    for (var i = 0; i < data.length; i++) {
                        var userCard=$('<div class="user-card">');
                        userCard.css({"display": "flex"});
                        userCard.css({"align-items": "center"});
                        userCard.css({"padding": "10px"});
                        userCard.css({"border-bottom": "1px solid #dbdbdb"});

                        var foto=$('<img class="user-avatar">');
                        foto.css({"width": "50px"});
                        foto.css({"height": "50px"});
                        foto.css({"border-radius": "50%"});
                        foto.css({"margin-right": "10px"});
                        foto.attr("src", "../imagenes/"+data[i].foto ? "../imagenes/"+data[i].foto : "../imagenes/perfil.png");

                        var userInfo=$('<div class="user-info">');
                        userInfo.css({"flex-grow": "1"});

                        var h3=$("<h3>");
                        h3.css({"margin":"0"});
                        h3.css({"font-size": "16px"});
                        h3.append('<a href="http://localhost:8000/buscausuario/'+data[i].id+'" class="perfil">'+data[i].username+'</a>');

                        userInfo.append(h3);
                        userCard.append(foto, userInfo);
                        resultados.append(userCard);
                    }
                }else if (status=="nocontent"){
                    $("#results").empty();
                    $("#results").append($("<p>").text("No se han encontrado usuarios").css({"color": "red"}));
                }
            },
            error: function(xhr, status, error) {
                
            }
        });
    });
});
