$(function(){
    var id_emisor=$("#logueado").val();
    $("#send").prop('disabled', true);
    $(".usuarios").on("click",function(ev){
        ev.preventDefault();
        var id_receptor=$(this).find(".receptor").val();
        $.getJSON("http://localhost:8000/usu/"+id_receptor,function(data,status){
            console.log(data);
            $("#foto").css({
                "borderRadius": "50%"
            });
            $("#foto").attr("src",data.foto? "../imagenes/"+data.foto: "../imagenes/perfil.png")
            $("#usuario").text(data.username)
        });
        
        var container_mensajes=$(".messages");
        container_mensajes.empty();
        
        $.getJSON("http://localhost:8000/mensajes/"+id_emisor+"/"+id_receptor,function(data,status){
            console.log(data);
            for (let i = 0; i < data.length; i++) {
                var mensaje = $("<div></div>").addClass("message");
                var messageContent = $("<div></div>").addClass("message-content").text(data[i].mensaje);



                // Determinar si el mensaje es entrante o saliente
                if (data[i].emisor.id == id_emisor) {
                    mensaje.addClass("outgoing");
                } else {
                    mensaje.addClass("incoming");
                    if (data[i].reportado==true){
                        messageContent.css({"color": "red"});
                    }
                }

                if (mensaje.hasClass("incoming")) {

                    var reportIcon = $("<i></i>").addClass("fas fa-flag report-icon").attr("title", "Reportar mensaje");

                    reportIcon.css({"margin-left": "5%"});

                    reportIcon.on("click", function() {
                        var boton=$(this);
                        var resultado=false;
                        var resultado1=false;
                        if (data[i].reportado==true){
                            resultado=confirm("¿Seguro que deseas cancelar este reporte?");
                        }else{
                            resultado1=confirm("¿Seguro que deseas reportar este mensaje?");
                        }
                         
                        if (resultado==true&&resultado1==false){
                            $.ajax({
                                type: 'POST',
                                url: '/reporr/'+data[i].id,
                                data: JSON.stringify({
                                    id: data[i].id
                                }),
                                success: function(data1,status) {
                                    boton.parent().css({"color": "black"});
                                },
                                contentType: 'application/json',
                                dataType: 'json'
                            });
                        }else if (resultado1==true && resultado==false){
                            $.ajax({
                                type: 'POST',
                                url: '/reporr/'+data[i].id,
                                data: JSON.stringify({
                                    id: data[i].id
                                }),
                                success: function(data1,status) {
                                    boton.parent().css({"color": "red"});
                                },
                                contentType: 'application/json',
                                dataType: 'json'
                            });
                        }
                    });
                }

                mensaje.append(messageContent);
                if (mensaje.hasClass("incoming")) {
                    messageContent.append(reportIcon);
                }
                container_mensajes.append(mensaje);
            }
            $("#message-input").on("input",function(e) {    
                const value = $(this).val();
                if (value.trim() === '') {
                    $("#send").prop('disabled', true);
                } else {
                    $("#send").prop('disabled', false);
                }
            });
            $("#send").on("click", function(ev){
                ev.preventDefault();
                var mensaje=$("#message-input").val();
                if (mensaje==""){
                    alert("No se puede enviar un mensaje vacío");
                }else{
                    var mensajee={
                        mensaje: mensaje,
                        emisor_id: id_emisor,
                        receptor_id: id_receptor,
                        fecha_mensaje: new Date()
                    }
    
                    
            
                    $.ajax({
                        type: 'POST',
                        url: '/addmensaje',
                        data: JSON.stringify(mensajee),
                        success: function(data1,status) {
                            var mensaje = $("<div></div>").addClass("message");
                            var messageContent = $("<div></div>").addClass("message-content").text(data1.mensaje);
            
                            // Determinar si el mensaje es entrante o saliente
                            if (data1.emisor.id == id_emisor) {
                                mensaje.addClass("outgoing");
                            } else {
                                mensaje.addClass("incoming");
                            }
            
                            //mensaje.append(messageContent);
                            //container_mensajes.append(mensaje);
    
                            $("#message-input").val('');
                        },
                        error: function(xhr, status, error) {
                            
                        }
                    });
                }
            });
        });
    });

    $("#busca").on("click", function(ev){
        ev.preventDefault();
        var busqueda=$("#busqueda").val();
        var container_mensajes=$(".messages");
        container_mensajes.empty();
        $.getJSON("http://localhost:8000/busqueda/"+busqueda,function(data,status){
            var id_rec = data[0].id;
            $.getJSON("http://localhost:8000/mensajes/"+id_emisor+"/"+data[0].id,function(data,status){
                console.log(data);
                for (let i = 0; i < data.length; i++) {
                    var mensaje = $("<div></div>").addClass("message");
                    var messageContent = $("<div></div>").addClass("message-content").text(data[i].mensaje);

                    // Determinar si el mensaje es entrante o saliente
                    if (data[i].emisor.id == id_emisor) {
                        mensaje.addClass("outgoing");
                    } else {
                        mensaje.addClass("incoming");
                    }

                    mensaje.append(messageContent);
                    container_mensajes.append(mensaje);
                }
                $("#send").on("click", function(ev){
                    ev.preventDefault();
                    var mensaje=$("#message-input").val();
                    if (mensaje==""){
                        alert("No se puede enviar un mensaje vacío");
                    }else{
                        var mensajee={
                            mensaje: mensaje,
                            emisor_id: id_emisor,
                            receptor_id: id_rec,
                            fecha_mensaje: new Date()
                        }
                
                        $.ajax({
                            type: 'POST',
                            url: '/addmensaje',
                            data: JSON.stringify(mensajee),
                            success: function(data,status) {
                                var mensaje = $("<div></div>").addClass("message");
                                var messageContent = $("<div></div>").addClass("message-content").text(data.mensaje);
                
                                // Determinar si el mensaje es entrante o saliente
                                if (data.emisor.id == id_emisor) {
                                    mensaje.addClass("outgoing");
                                } else {
                                    mensaje.addClass("incoming");
                                }
                
                                mensaje.append(messageContent);
                                container_mensajes.append(mensaje);
    
                                $("#message-input").val('');
                            },
                            error: function(xhr, status, error) {
                                
                            }
                        });
                    }
                });
        });
    });
});
});