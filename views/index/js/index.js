$(function(){

    enable_only_number();

    $("#num-op").attr("placeholder", "Orden de Producción");
    $("#btn-filtrar").html("Filtrar");

    function valida_parametros() {
        let numOP = $("#num-op").val();

        if (numOP == "") {
            swal("Alerta!", "Por favor ingrese el Número de OP", "warning");
            $("#num-op").focus();
            return false;
        }

        return true;
    }

    $("#btn-filtrar").on("click", function () {
        if (valida_parametros()) {

            let numOP = $("#num-op").val();
            
            $.ajax({
                method:'POST',
                url:'/ORACLEOP/index/getTraslados',
                data:{numOP:numOP},
                beforeSend:function(){
                    $("#btn-filtrar").attr("disabled",true).html("Cargando... ").append("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>");
                },
                success:function(result){
                    $("#btn-filtrar").attr("disabled",false).html("Filtrar").children(this).remove();
                    
                    if(result.res!=null){
                        
                        let head = "";
                        let body = "";

                        head += "<tr> \
                                    <th width='10%'>NRO TRASLADO</th> \
                                    <th width='10%'>F. CREACION</th> \
                                    <th width='20%'>NOMBRE TRASLADO</th> \
                                    <th width='10%'>COD. ARTICULO</th> \
                                    <th width='35%'>DESCRIPCION ARTICULO</th> \
                                    <th width='5%'>#</th> \
                                </tr>";
                                    
                        for(var i = 0; i < result.res.length; i++){
                            body += "<tr> \
                                        <td width='10%'>"+result.res[i].NroTraslado+"</td> \
                                        <td width='10%'>"+result.res[i].FechaCreacion+"</td> \
                                        <td width='20%'>"+result.res[i].NombreTraslado+"</td> \
                                        <td width='10%'>"+result.res[i].codarticulo+"</td> \
                                        <td width='35%'>"+result.res[i].nomarticulo+"</td> \
                                        <td width='5%'><a class='btn btn-info' href='https://192.168.1.207:8080/ORACLEOP/index/imprimir/"+result.res[i].NroTraslado+"' target='_blank'><i class='fa fa-print'></i></a></td> \
                                    </tr>";
                        }

                        $(".head").html('').html(head);
                        $(".body").html('').html(body);
                    }else{
                        swal("Alerta!", "No existen Ordenes de Traslado para la OP ingresada", "warning");
                    }
                }
            });

        }
    });

});

function enable_only_number() {
    $('.only-number').unbind('keyup');
    $('.only-number').keyup(function () {
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
}