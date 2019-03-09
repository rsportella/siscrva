$(document).ready(function () {
    $(".alert").fadeIn(function () {
        $(this).delay(2500).fadeOut(1500);
    });
    $('.icon_princ').tooltip();

    $('.dataTable').dataTable({
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "aaSorting": [[0, "asc"]],
        "oLanguage": {
            "sProcessing": "Carregando...",
            "sLengthMenu": "Listar _MENU_ por página",
            "sZeroRecords": "Nenhum arquivo encontrado.",
            "sInfo": "_START_ até _END_, de _TOTAL_ encontrado.",
            "sInfoEmpty": "0 registros encontrados",
            "sInfoFiltered": "(_MAX_ registros no sistema)",
            "sInfoPostFix": "",
            "sSearch": "Procurar",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sPrevious": "Anterior",
                "sNext": "Próximo",
                "sLast": "Último"
            }
        },
    });

    $(".dataTables_filter input").first().focus();

    $('input').keyup(function () {
        this.value = this.value.toLocaleUpperCase();
    });

    $('select[name="selectcpfcnpj[]"]').change(function () {
        var val = $(this).val(), input = $("input[name='cpfcnpj'], input[name='cpfcnpj[]']." + $(this).attr('class').split(' ')[1]);
        input.prop("disabled", false).val('');
        input.focus();
        if ($(this).val() === 'cpf') {
            $(input).mask("999.999.999-99");
        } else if ($(this).val() === 'cnpj') {
            $(input).mask("99.999.999/9999-99");
        } else if ($(this).val() === '-') {
            input.prop("disabled", true);
        }
    });
    $('input[name="cliente"]').blur(function () {
        var valor = $(this).val();
        if (valor.length >= 5) {
            $.post("cliente.php?modal=buscacliente", {valor: valor},
                    function (data, status) {
                        if (data == 'Not') {
                            if (confirm("Deseja adicionar este cliente?")) {
                                location = './cliente.php?modal=novo&balcao';
                            } else {
                                $('input[name="cliente"]').val('');
                            }
                        } else {
                            $('input[name="idcliente"]').val(data.split(' - ')[0]);
                            $('input[name="cliente"]').val(data.split(' - ')[1]).prop("disabled", true);
                        }
                    });
        }
    });
    $('select[name="estado"]').change(function () {
        if ($(this).val()) {
            $('select[name="cidade"]').hide();
            $('#carregando').show();
            $.getJSON('./cidades.ajax.php?search=', {idestado: $(this).val(), ajax: 'true'}, function (j) {
                var options = '<option value="">- Escolha uma cidade -</option>';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].idcidade + '">' + j[i].nome + '</option>';
                }
                $('select[name="cidade"]').html(options).show();
                $('#carregando').hide();
            });
        } else {
            $('select[name="cidade"]').html('<option value="">– Escolha um estado –</option>');
        }
    });
});

$(function () {


    var CpfCnpjMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
    },
            cpfCnpjpOptions = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
                }
            };


    $('[data-toggle="tooltip"]').tooltip();
    $("a[class='excluir']").click(function () {
        var whats = confirm("Deseja realmente executar está tarefa.\nEsta operação não podera ser desfeita!");
        if (whats) {
            return true;
        }
        return false;
    });
    $(".celular").mask("(99) 9 9999.9999");
    $(".data").mask("99/99/9999");
    $(".placa").mask("SSS9999");
    $(".cpf").mask("999.999.999-99");
    $(".pessoa").mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
    $(".valor").mask('000.000.000.000.000,00', {reverse: true});
});