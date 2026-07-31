<?php 
@session_start();
$_SESSION['pagina'] = 'sobre';
require_once("cabecalho.php");
 ?>  
     
       

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

                            
                            

                               
                           
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                        <img class="img-fluid" src="sistema/img/<?php echo $foto_sobre_pagina ?>" alt="img">
                  
                    </div>
                </div>
            </div>
        </section>
        <!--================ About History Area  =================-->
        
        

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
        
     <br>

     <iframe src="<?php echo $mapa ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        
<?php require_once("rodape.php") ?>       