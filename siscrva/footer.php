<footer>
    <div class="container">
        <div class="col-md-10 col-md-offset-1 text-right">
            <h6 style="font-size:14px;font-weight:100;">SisCRVA <img src="img/logoIS.png" title="" style="width: 20px; margin: 0 10px" /> <a href="http://infosulst.com.br/" style="color: #000;" target="_blank" title="IServices - Soluções & Tecnologias">IServices</a></h6>
        </div>   
    </div>
</footer>
<br><br><br>
<script src="js/bootstrap.js"></script>
<script src="js/dataTables.js"></script>
<script src="js/bootstrap-dataTables.js"></script>
<script src="js/tableTools.js"></script>
<script src="js/mask.js"></script>

<script src="js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        var CpfCnpjMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
        },
                cpfCnpjpOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
                    }
                };

        $(function () {
            $('.pessoa').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
            $('.gerarnota').on('click', function () {
                var link = "cupom?parametros=" + $("#nota").val();
                window.open(link);
            });
        });
    });
</script>
</body>
</html>