$('[data-toggle="popover"]').popover({
    trigger: 'toggle'
});

$(document).ready(function () {
    $.ajax({
        'url': "http://localhost/PAINEL_INCIDENTES/tbody.php",
        'method': "GET",
        'contentType': 'application/json'
    }).done( function(data) {
        $('#tablePainelIncidente').dataTable( {
            "aaData": data,
            "paging":   false,
            "info":     false,
            "searching": false
        });
        $("#loader").delay(2000).fadeOut("slow");

        var qtd = $("tbody tr").length;
        var dado = new Array();
        var comando = new Array();

        for(var i=1; i <= qtd; i++){
            dado.push($("tbody tr:nth-child("+i+") td:first-child").text());
            comando.push("tbody tr:nth-child("+i+") td:first-child");
        }

        var obj = {};
        var k=1;

        for(var i=0; i< dado.length; i++){
            for(var j=0; j < dado.length; j++){
                if(dado[i] == dado[j]){
                    obj[dado[i]] = k;
                    k++;
                }
            }
            k=1;
        }

        var key = new Array();

        for(var prop in obj){
            key.push(obj[prop]);
        }

        m=0;
        n=0;

        for(var i=0; i < qtd; i++){
            if(i == m){
                $(comando[i]).attr({
                    rowspan: key[n]
                }).wrapInner("<span class='vertTD'></span>");

                m += key[n];
                n++;
            }else{
                $(comando[i]).remove();
            }
        }

    });
});