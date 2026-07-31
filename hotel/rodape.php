 <!--================ start footer Area  =================-->	
        <footer class="footer-area section_gap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">Horário de Atendimento</h6>
                            <p><b>Segunda a Sexta</b> Das 08:00 as 18:00</p>
                            <p><b>Sábados</b> Das 08:00 as 12:00</p>
                        </div>
                    </div>
                  						
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">Contate-nos</h6>
                            <p><b>Email </b><?php echo $email_sistema ?></p>	
                            <p><b>Whatsapp </b><a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $whatsapp_sistema ?>" target="_blank"><?php echo $telefone_sistema ?></a></p>	
                           
                        </div>
                    </div>
                   				
                </div>
                <div class="border_line"></div>
                <div class="row footer-bottom d-flex justify-content-between align-items-center">
                    <p class="col-lg-8 col-sm-12 footer-text m-0"><?php echo $endereco_sistema ?></p>
                    <div class="col-lg-4 col-sm-12 footer-social">
                        <b>Redes Sociais</b>
                        <a href="<?php echo $instagram_sistema ?>"><i class="fa fa-instagram" target="_blank" title="Ir para o Instagram"></i></a>
                        <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $whatsapp_sistema ?>" target="_blank" title="Ir para o Whatsapp"><i class="fa fa-whatsapp"></i></a>
                       
                    </div>
                </div>
            </div>
        </footer>
		<!--================ End footer Area  =================-->
        

        
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
        <script src="js/jquery.ajaxchimp.min.js"></script>
        <script src="js/mail-script.js"></script>
        <script src="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="vendors/nice-select/js/jquery.nice-select.js"></script>
        <script src="js/mail-script.js"></script>
        <script src="js/stellar.js"></script>
        <script src="vendors/lightbox/simpleLightbox.min.js"></script>
        <script src="js/custom.js"></script>

          <!-- Mascaras JS -->
<script type="text/javascript" src="sistema/painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

    </body>
</html>



<div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content ">

            <div class="modal-header">

                <h4 class="modal-title" id="exampleModalLabel"><span id="titulo_quarto"></span></h4>

                <button id="btn-fechar-esp" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>
            

            <div class="modal-body">               

             <div id="descricao_quarto"></div>

             <div id="listar_esp" style="margin-top: 10px">                  

                </div>


                <div id="listar-fotos" style="margin-top: 10px">                    

                </div>

            </div>



        </div>

    </div>

</div>     


<script type="text/javascript">
    function detalheQuarto(id, nome, descricao){
        $('#titulo_quarto').text(nome);
        $('#descricao_quarto').text(descricao);
        listarDetalhes(id);
        carregarFotos(id)        
        $('#modalDetalhes').modal('show');
    }

    function listarDetalhes(id){
        $.ajax({

        url: 'ajax/listar-esp.php',

        method: 'POST',

        data: {id},

        dataType: "html",



        success:function(result){

            $("#listar_esp").html(result);

            $('#mensagem-esp').text('');

        }

    });
    }


    function carregarFotos(id){

        $.ajax({

       url: 'ajax/listar-fotos.php',

        method: 'POST',

        data: {id},

        dataType: "html",



        success:function(result){

            $("#listar-fotos").html(result);

            $('#mensagem-excluir-foto').text('');

        }

    });

    }

</script>