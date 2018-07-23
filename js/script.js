$('[data-toggle="popover"]').popover({
    trigger: 'toggle'
});

$(".aOpcao").on("click", function () {
    var valorID = $(this).attr('id');
    var separar = valorID.split("*");

    if(separar[1] != ""){
        var inc = separar[1].split("|");
        var info = separar[0].split("_");

        var html;
        var grupoEmpresa = info[0].replace(" & "," - ");

        $.ajax({
            type: "POST",
            url: "funcao/funcaoBuscaChamado.php",
            data: {0: inc},
            success: function (data) {
                $("#ajaxReturn").html(data);
                $("#myModal").modal("show");
                if (info[2] == "NOPRAZO") {
                    $("#h4ModalTitulo").html("<blockquote class='blockquote text-center'>" +
                        "<p class='mb-0'>GRUPO DESIGNADO <strong>- "+grupoEmpresa+"</strong></p>" +
                        "<footer class='blockquote-footer'> Prioridade "+info[1]+" - "+
                        "<cite title='Source Title'> incidente dentro do Prazo</cite>" +
                        "</footer>" +
                        "</blockquote>");
                }else{
                    $("#h4ModalTitulo").html("<blockquote class='blockquote text-center'>" +
                        "<p class='mb-0'>GRUPO DESIGNADO <strong>- "+grupoEmpresa+"</strong></p>" +
                        "<footer class='blockquote-footer'> Prioridade "+info[1]+" - "+
                        "<cite class='citeVencido' title='Source Title'> incidente Vencido</cite>" +
                        "</footer>" +
                        "</blockquote>");
                }
            },
            error: function () {
                alert("AJAX - ERRO");
            }
        });
    }
});

$(document).ready(function () {
    $("#tablePainelIncidente").DataTable({
        "paging":   false,
        "info":     false,
        "searching": false,
        "processing": true,
        "serverSide": true
    });
    $("#tableModalInfo").DataTable({
        paging: false,
        info: false,
        searching: false,
        autoWidth: false,
        bAutoWidth: false,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ]
    });
});



// Este evendo é acionado após o carregamento da página
jQuery(window).ready(function() {
    //Após a leitura da pagina o evento fadeOut do loader é acionado, esta com delay para ser perceptivo em ambiente fora do servidor.
    jQuery("#loader").delay(2000).fadeOut("slow");
});