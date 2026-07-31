<?php 
@session_start();
$_SESSION['pagina'] = 'contatos';
require_once("cabecalho.php");
 ?>  


   <!--================Contact Area =================-->
        <section class="contact_area section_gap">
            <div class="container">
               
                <div class="row">
                    <div class="col-md-3">
                        <div class="contact_info">
                            <div class="info_item">
                                <i class="lnr lnr-home"></i>
                                <h6>Endereço</h6>
                                <p><?php echo $endereco_sistema ?></p>
                            </div>
                            <div class="info_item">
                                <i class="lnr lnr-phone-handset"></i>
                                <h6><a href="#">Telefone</a></h6>
                                <p><?php echo $telefone_sistema ?></p>
                            </div>
                            <div class="info_item">
                                <i class="lnr lnr-envelope"></i>
                                <h6><a href="#">Email</a></h6>
                                <p><?php echo $email_sistema ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <form class="row contact_form" method="post" id="contactForm" novalidate="novalidate">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Seu Nome" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Seu Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" name="message" id="message" rows="1" placeholder="Mensagem"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <button type="submit" value="submit" class="btn theme_btn button_hover">Enviar</button>
                            </div>
                        </form>
                        <div align="center" id="mensagem_enviar"></div>
                    </div>
                </div>
            </div>
        </section>
        <!--================Contact Area =================-->

<?php require_once("rodape.php") ?>     



    <script type="text/javascript">
        

$("#contactForm").submit(function () {

   event.preventDefault();
    var formData = new FormData(this);
    $("#mensagem_enviar").html("Enviando!!");
    $.ajax({
        url: 'enviar.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) { 
            if (mensagem.trim() == "Enviado") {
              alert('Email Enviado!')
            }else{
                alert(mensagem);
            }

            $("#mensagem_enviar").html("");

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});



      </script>