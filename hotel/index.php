<?php 
@session_start();
$_SESSION['pagina'] = 'home';
require_once("cabecalho.php");
 ?>  
 
        <!--================Banner Area =================-->

         <?php 
                $query = $pdo->query("SELECT * from banners_site where ativo = 'Sim' order by id desc limit 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){

    $id = $res[0]['id'];
  $titulo = $res[0]['titulo'];
  $descricao = $res[0]['descricao'];
  $subtitulo = $res[0]['subtitulo'];
  $link = $res[0]['link'];
  $foto = $res[0]['foto']; 

  $ocultar_link = '';
  if($link == ""){
    $ocultar_link = 'none';
  }

  $ocultar_textos = '';
  if($descricao == "" and $subtitulo == ""){
    $ocultar_textos = 'none';
  }
?>

        <section class="banner_area">
            <div class="booking_table d_flex align-items-center">
            	<div class="overlay bg-parallax" data-stellar-ratio="0.9" data-stellar-vertical-offset="0" data-background="" style="background: url('sistema/painel/images/banners/<?php echo $foto ?>') no-repeat scroll center 0/cover;
  opacity: 0.50;"></div>
				<div class="container">
					<div class="banner_content text-center" style="display:<?php echo $ocultar_textos ?>">
						<h6><?php echo $subtitulo ?></h6>
						<h2><?php echo $titulo ?></h2>
						<p><?php echo $descricao ?></p>
						<a style="display:<?php echo $ocultar_link ?>" href="<?php echo $link ?>" class="btn theme_btn button_hover">Veja Mais</a>
					</div>
				</div>
            </div>
            <div class="hotel_booking_area position">
                <div class="container">
                    <div class="hotel_booking_table">
                        <div class="col-md-3">
                            <h2>Faça<br> Sua Reserva</h2>
                        </div>
                        <div class="col-md-9">
                            <div class="boking_table">
                                 <form method="post" action="checkout.php" target="_blank">
                                <div class="row">
                                   
                                    <div class="col-md-4">
                                        <div class="book_tabel_item">
                                            <div class="form-group">
                                                <div class='input-group date ' >
                                                    <input style="color:white;" type='date' class="form-control" placeholder="Entrada" name="checkin" value="<?php echo date('Y-m-d') ?>" />
                                                   
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class='input-group date' >
                                                    <input style="color:white" type='date' class="form-control" placeholder="Saída" name="checkout" value="<?php echo date('Y-m-d') ?>" />
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="book_tabel_item">
                                            <div class="input-group">
                                                <select class="wide" required="" name="adultos">
                                                    <option  data-display="Adultos" value="1">Adultos</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                   
                                                </select>
                                            </div>
                                            <div class="input-group">
                                                <select class="wide" required="" name="criancas" id="hospedes_criancas" onchange="criarCamposIdade()">
                                                    <option data-display="Crianças">Crianças</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="book_tabel_item">
                                            <div class="input-group">
                                               
                                            </div>
                                            <button type="submit" class="book_now_btn button_hover" >Reservar</button>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row" id="linha-criancas">
                                  
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
     <?php } ?>
        <!--================Banner Area =================-->
        
        <!--================ Accomodation Area  =================-->
        <?php 
               $query = $pdo->query("SELECT * from categorias_quartos where ativo = 'Sim' order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
             ?>
        <section class="accomodation_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <h2 class="title_color">Quartos</h2>
                    <p>Escolha uma de nossas categorias de quartos</p>
                </div>
                <div class="row mb_30">

                     <?php 
              for($i=0; $i<$linhas; $i++){
  $id = $res[$i]['id'];
  $nome = $res[$i]['nome'];
  $nome_url = $res[$i]['nome_url'];
  $descricao = $res[$i]['descricao'];
  $especificacoes = $res[$i]['especificacoes'];
  $valor = $res[$i]['valor'];
  $foto = $res[$i]['foto']; 
  $ativo = $res[$i]['ativo'];
  
  $valorF = number_format($valor, 2, ',', '.');  
  $descricaoF = mb_strimwidth($descricao, 0, 100, "...");

  $especificacoesF = @str_replace('**', ', ', $especificacoes);

  if($linhas < 5){
    $col = 'cell-md-6';
    $heig = '320px';
  }else{
    $col = 'cell-md-4';
    $heig = '276px';
  }
             ?>

                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <a style="cursor: pointer;" href="quarto/<?php echo $id ?>"><img src="sistema/painel/images/quartos/<?php echo $foto ?>" alt="" width="270" height=""></a>
                               
                            </div>
                            <a style="cursor: pointer;" onclick="detalheQuarto(<?php echo $id ?>, '<?php echo $nome ?>', '<?php echo $descricao ?>')"><h4 class="sec_h4"><?php echo $nome ?></h4></a>
                            <h6 style="color:#6f1205">R$ <?php echo $valorF ?><small>/Diária</small></h6>
                            <div style="">
                            <?php 
                                 $query2 = $pdo->query("SELECT * from especificacoes_quartos where cat_quartos = '$id' order by id asc");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$linhas2 = @count($res2);
if($linhas2 > 0){
for($i2=0; $i2<$linhas2; $i2++){                             ?>
                            <span style="font-size:12px; color:#616161"><i class="fa fa-check text-verde"></i><?php echo $res2[$i2]['texto'] ?></span><br>
                        <?php } } ?>
                        </div>
                        </div>
                    </div>

                <?php } ?>
                   
               
                
                </div>
            </div>
        </section>
    <?php } ?>
        <!--================ Accomodation Area  =================-->
        

        <?php 
               $query = $pdo->query("SELECT * from especificacoes order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
             ?>
        <!--================ Facilities Area  =================-->
        <section class="facilities_area section_gap">
            <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background="">  
            </div>
            <div class="container">
                <div class="section_title text-center">
                    <h2 class="title_w">Serviços e Lazer</h2>
                    <p>Desfrute de todos os nosso serviços e áreas de lazer</p>
                </div>
                <div class="row mb_30">

                     <?php 
             for($i=0; $i<$linhas; $i++){
  $id = $res[$i]['id'];
  $nome = $res[$i]['nome'];
  $foto = $res[$i]['foto'];
  $descricao = $res[$i]['descricao'];
         ?>

                    <div class="col-lg-4 col-md-6">
                        <div class="facilities_item">
                            <h4 class="sec_h4"><i class="<?php echo $foto ?>"></i><?php echo $nome ?></h4>
                            <p><?php echo $descricao ?></p>
                        </div>
                    </div>

                <?php } ?>
                  
                </div>
            </div>
        </section>
    <?php } ?>
        <!--================ Facilities Area  =================-->
        
        <!--================ About History Area  =================-->
        <section class="about_history_area section_gap">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 d_flex align-items-center">
                        <div class="about_content ">
                            <h2 class="title title_color"><?php if($titulo_sobre != ""){ ?><h2><?php echo $titulo_sobre ?></h2><?php } ?></h2>
                            <p>  
                                <?php if($descricao_sobre1 != ""){ ?><?php echo $descricao_sobre1 ?><?php } ?>
                                
                                 <?php if($descricao_sobre2 != ""){ ?><br><br><?php echo $descricao_sobre2 ?><?php } ?>

                                 <?php if($descricao_sobre3 != ""){ ?><br><br><?php echo $descricao_sobre3 ?><?php } ?>
                            </p>

                            
                            

                               
                            <a href="sobre.php" class="button_hover theme_btn_two">Ver mais sobre</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <?php if($foto_video_sobre == "Vídeo"){ ?>
               <iframe class="video-mobile" width="100%" height="350" src="<?php echo $video_sobre_index ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen id="target-video"></iframe>
                <?php }else{ ?>
                        <img class="img-fluid" src="sistema/img/<?php echo $foto_sobre_pagina ?>" alt="img">
                    <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <!--================ About History Area  =================-->
        
        <!--================ Testimonial Area  =================-->

        <?php 
                       $query = $pdo->query("SELECT * from comentarios order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
         ?>
        <section class="testimonial_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <h2 class="title_color">Comentários e Avaliações</h2>
                    <p>Confira alguns comentários e avaliações dos últimos hóspedes</p>
                </div>
                <div class="testimonial_slider owl-carousel">

                            <?php 
             for($i=0; $i<$linhas; $i++){
  $id = $res[$i]['id'];
  $nome = $res[$i]['nome'];
  $foto = $res[$i]['foto'];
  $texto = $res[$i]['texto'];
         ?>
                    <div class="media testimonial_item">
                        <img class="rounded-circle" src="sistema/painel/images/comentarios/<?php echo $foto ?>" alt="" style="width:80px; height: 80px">
                        <div class="media-body">
                            <p><?php echo $texto ?></p>
                            <a href="#"><h4 class="sec_h4"><?php echo $nome ?></h4></a>
                            <div class="star">
                                <a href="#"><i class="fa fa-star"></i></a>
                                <a href="#"><i class="fa fa-star"></i></a>
                                <a href="#"><i class="fa fa-star"></i></a>
                                <a href="#"><i class="fa fa-star"></i></a>
                                <a href="#"><i class="fa fa-star"></i></a>
                            </div>
                        </div>
                    </div>    
                 
                   <?php } ?>
                   
                </div>
            </div>
        </section>
        <!--================ Testimonial Area  =================-->

    <?php } ?>
        

          <?php 
               $query = $pdo->query("SELECT * from galeria_site order by id desc limit 18");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
             ?>
        <!--================ Latest Blog Area  =================-->
        <section class="latest_blog_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <h2 class="title_color">Galeria de Fotos do Hotél</h2>
                   
                </div>
                <div class="row mb_30">
                     <?php 
              for($i=0; $i<$linhas; $i++){
  $id_foto = $res[$i]['id'];
  $foto = $res[$i]['foto'];
  ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-recent-blog-post">
                            <div class="thumb">
                                <img class="img-fluid" src="sistema/painel/images/galeria/<?php echo $foto ?>" alt="post">
                            </div>
                           
                        </div>
                    </div>
                   <?php } ?>
                 
                </div>
            </div>
        </section>
        <!--================ Recent Area  =================-->
    <?php } ?>
        

<iframe src="<?php echo $mapa ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    
<?php require_once("rodape.php") ?>  




<script type="text/javascript">
function criarCamposIdade() {
    let quantidade = parseInt($('#hospedes_criancas').val()) || 0;

    if (quantidade > 8) {
        alert("O número de crianças não pode ser superior a 8.");
        $('#hospedes_criancas').val(8);
        quantidade = 8;
    }

    const linha = document.getElementById('linha-criancas');

    // Salva os valores atuais
    const valoresExistentes = {};
    const inputsAtuais = linha.querySelectorAll('input');
    inputsAtuais.forEach(input => {
        valoresExistentes[input.id] = input.value;
    });

    // Limpa todos os inputs anteriores
    linha.innerHTML = '';

    // Cria os novos inputs
    for (let i = 1; i <= quantidade; i++) {
        const col = document.createElement('div');
        col.className = 'col-md-2';
        col.style.marginBottom = '10px';

        const label = document.createElement('label');
        label.textContent = 'Idade Criança ' + i;
        label.style.fontSize = '12px'; // Define o tamanho da fonte

        const input = document.createElement('input');
        input.type = 'number';
        input.className = 'form-control';
        input.name = 'idade_crianca_' + i;
        input.id = 'idade_crianca_' + i;
        input.required = true;
        input.min = 0;

        // Reaplica valor salvo (se existir)
        if (valoresExistentes[input.id]) {
            input.value = valoresExistentes[input.id];
        }

        input.oninput = function () {
            //calcular();
        };

        col.appendChild(label);
        col.appendChild(input);
        linha.appendChild(col);
    }

    //calcular();
}
</script>
