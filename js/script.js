$('[data-toggle="popover"]').popover({
    trigger: 'toggle'
});

$(document).ready(function () {
    $.ajax({
        'url': "http://10.196.6.99/PAINEL_INCIDENTES/tbody.php",
        'method': "GET",
        'contentType': 'application/json'
    }).done( function(data) {
        $('#tablePainelIncidente').dataTable( {
            "aaData": data,
            "paging":   false,
            "info":     false,
            "searching": false,
            "ordering" : false
        });
        $("#loader").delay(2000).fadeOut("slow");

        var qtd = $("tbody tr").length;
        var dado = [];
        var comando = [];

        for(var i=1; i <= qtd; i++){
            dado.push($("tbody tr:nth-child("+i+") td:first-child").text());
            comando.push("tbody tr:nth-child("+i+") td:first-child");
        }

        var obj = {};
        var k=1;

        for(var i=0; i< dado.length; i++){
            for(var j=0; j < dado.length; j++){
                if(dado[i] === dado[j]){
                    obj[dado[i]] = k;
                    k++;
                }
            }
            k=1;
        }

        var key = [];

        for(var prop in obj){
            key.push(obj[prop]);
        }

        m=0;
        n=0;

        for(var i=0; i < qtd; i++){
            var cls = $(comando[i]).text()+"|"+$(comando[i]).next().text();
            if(i === m){
                $(comando[i])
                    .attr({
                    rowspan: key[n]
                    }).addClass("firstClass")
                    .next()
                    .css({
                        'font-weight': '700',
                        'font-size': '13px',
                        'padding-left' : '10px',
                        'padding-top': '5px',
                        'text-transform':'uppercase'
                    })
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Baixo' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Baixo' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Média' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Média' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Alto' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Alto' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Crítico' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Crítico' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Total' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Total' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|%' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|%' href='#' role='button'></a>");

                m += key[n];
                n++;
            }else{
                $(comando[i]).remove();
                $(comando[i]).css({
                    'font-weight': '700',
                    'font-size': '13px',
                    'padding-left' : '10px',
                    'padding-top': '5px',
                    'text-transform':'uppercase'
                })
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Baixo' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Baixo' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Média' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Média' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Alto' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Alto' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Crítico' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Crítico' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|Total' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|Total' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-primary "+cls+"|%' href='#' role='button'></a>")
                    .next()
                    .wrapInner("<a class='btn btn-danger "+cls+"|%' href='#' role='button'></a>");
            }
        }

        $("tbody tr:last-child td:first-child").attr("colspan","2").css('font-size','14px').next().remove();

        $("a.btn").on("click",function () {

            if ($(this).text() !== "0") {

                var cls = $(this).attr("class").split("|");
                var splt = cls[0].split(" ");

                var tipo = splt[1];
                var empresa = splt[2];
                var grupo = "%" + cls[1] + "%";
                var prioridade = cls[2];

                if (tipo === "btn-primary") {
                    tipo = "NOPRAZO";
                } else {
                    tipo = "VENCIDO";
                }

                var second;

                switch (prioridade) {
                    case "Baixo":
                        second = 28800;
                        break;

                    case "Média":
                        second = 21600;
                        break;

                    case "Alto":
                        second = 14400;
                        break;

                    case "Crítico":
                        second = 7200;
                        break;
                }

                switch (empresa) {
                    case "+2X":
                        empresa = "B2BR BUSINESS TO BUS INF DO BRASIL LTDA";
                        break;
                    case "CSC":
                        empresa = "CSC BRASIL SISTEMAS LTDA";
                        break;
                    case "STEFANINI":
                        empresa = "STEFANINI CONS ASSESSORIA INFORMATICA SA";
                        break;
                    case "TIVIT":
                        empresa = "TIVIT TERC DE PROC SERV TECN S/A";
                        break;
                }

                $.ajax({
                    type: 'POST',
                    url: 'http://10.196.6.99/PAINEL_INCIDENTES/modalDataPainel.php',
                    data: {tipo: tipo, empresa: empresa, grupo: grupo, prioridade: prioridade, second: second},
                    success: function (data) {
                        $("div#modalDataPainel").modal('show');
                        $(".modal-body").html(data);
                        $("#tabs").tabs({
                            collapsible: true
                        });
                        $("img#imgModalClose").on("click", function () {
                            $("div#modalDataPainel").modal('hide');
                        });
                    }

                });

            }
        });

    });


});