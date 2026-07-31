<?php 
@session_start();
$_SESSION['pagina'] = 'quartos';
require_once("cabecalho.php");
 ?>  

        
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
  $descricaoF = mb_strimwidth($descricao, 0, 200, "...");

  $especificacoesF = str_replace('**', ', ', $especificacoes);

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
        

<?php require_once("rodape.php") ?>       