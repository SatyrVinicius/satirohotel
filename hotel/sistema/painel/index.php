<?php 
@session_start();
require_once("../conexao.php");
require_once("verificar.php");

$data_atual = date('Y-m-d');

$pag_inicial = 'home';
if(@$_SESSION['nivel'] != 'Administrador'){
	require_once("verificar_permissoes.php");
}

if(@$_GET['pagina'] != ""){
	$pagina = @$_GET['pagina'];
}else{
	$pagina = $pag_inicial;
}

// Evita warnings de variáveis não definidas nas classes do menu.
$menuFlags = [
	'home','configuracoes',
	'menu_pessoas','hospedes','funcionarios','fornecedores','usuarios',
	'menu_cadastros','categorias_quartos','quartos','cargos','formas_pgto','grupos','acessos',
	'menu_reservas','reservas','filtrar_reservas','modelo_relatorios','rel_quartos','quadro_reservas','valores_criancas','valores_datas','bloqueio_datas','mapa_reservas',
	'menu_caixa','caixas','rel_caixas',
	'menu_financeiro','pagar','receber','compras','rel_financeiro','rel_lucro',
	'menu_produtos','categorias_produtos','produtos','entradas','saidas','estoque_baixo',
	'menu_servicos','categorias_servicos','servicos',
	'menu_vendas','vendas_produtos','vendas_servicos','lista_vendas','lista_servicos',
	'calendario','marketing','dispositivos',
	'dados_site','banners_site','especificacoes','galeria_site','comentarios'
];

foreach($menuFlags as $flag){
	if(!isset($$flag)){
		$$flag = '';
	}
}

if(isset($$pagina)){
	$$pagina = 'active';
}

if(in_array($pagina, ['hospedes','funcionarios','fornecedores','usuarios'])){
	$menu_pessoas = 'active';
}

if(in_array($pagina, ['categorias_quartos','quartos','cargos','formas_pgto','grupos','acessos'])){
	$menu_cadastros = 'active';
}

if(in_array($pagina, ['reservas','filtrar_reservas','modelo_relatorios','quadro_reservas','valores_criancas','valores_datas','bloqueio_datas','mapa_reservas'])){
	$menu_reservas = 'active';
}

if(in_array($pagina, ['caixas'])){
	$menu_caixa = 'active';
}

if(in_array($pagina, ['pagar','receber','compras'])){
	$menu_financeiro = 'active';
}

if(in_array($pagina, ['categorias_produtos','produtos','entradas','saidas','estoque_baixo'])){
	$menu_produtos = 'active';
}

if(in_array($pagina, ['categorias_servicos','servicos'])){
	$menu_servicos = 'active';
}

if(in_array($pagina, ['vendas_produtos','vendas_servicos','lista_vendas','lista_servicos'])){
	$menu_vendas = 'active';
}

$id_usuario = @$_SESSION['id'];
$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	$nome_usuario = $res[0]['nome'];
	$email_usuario = $res[0]['email'];
	$telefone_usuario = $res[0]['telefone'];
	$senha_usuario = $res[0]['senha'];
	$nivel_usuario = $res[0]['nivel'];
	$foto_usuario = $res[0]['foto'];
	$endereco_usuario = $res[0]['endereco'];

	$nome_usuarioF = mb_strimwidth($nome_usuario, 0, 20, "...");
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="../img/icone.png" type="image/x-icon">

	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />

	 <link rel="stylesheet" href="../../vendors/linericon/style.css">

	<!-- font-awesome icons CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- //font-awesome icons CSS-->

	<!-- side nav css file -->
	<link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
	<!-- //side nav css file -->

	<link rel="stylesheet" href="css/monthly.css">

	<link rel="stylesheet" href="css/style-calendario.css">
	

	<!-- js-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/modernizr.custom.js"></script>

	<!--webfonts-->
	<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
	<!--//webfonts--> 

	<!-- chart -->
	<script src="js/Chart.js"></script>
	<!-- //chart -->

	<!-- Metis Menu -->
	<script src="js/metisMenu.min.js"></script>
	<script src="js/custom.js"></script>
	<link href="css/custom.css" rel="stylesheet">
	<!--//Metis Menu -->
	<style>
		#chartdiv {
			width: 100%;
			height: 295px;
		}
	</style>
	<!--pie-chart --><!-- index page sales reviews visitors pie chart -->
	<script src="js/pie-chart.js" type="text/javascript"></script>
	<script type="text/javascript">

		$(document).ready(function () {
			carregarFotosGaleria();

			$('#demo-pie-1').pieChart({
				barColor: '#2dde98',
				trackColor: '#eee',
				lineCap: 'round',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-2').pieChart({
				barColor: '#20158f',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-3').pieChart({
				barColor: '#c91508',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});


		});

	</script>
	<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->


<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/> <script src="DataTables/datatables.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
		.select2-selection__rendered {
			line-height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}

		.select2-selection {
			height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}
	</style>  

	
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left" style="overflow: scroll; height:100%; scrollbar-width: thin;">
				<nav class="navbar navbar-inverse">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h1><a class="navbar-brand" href="index.php"><span class="fa fa-building-o"></span> Sistema PMS<span class="dashboard_text"><?php echo $nome_sistema ?></span></a></h1>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="sidebar-menu">
							<li class="header">MENU NAVEGAÇÃO</li>
							<li class="treeview <?php echo $home ?>">
								<a href="index.php">
									<i class="fa fa-dashboard"></i> <span>Home</span>
								</a>
							</li>
							<li class="treeview <?php echo $menu_pessoas ?>">
								<a href="#">
									<i class="fa fa-users"></i>
									<span>Pessoas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo $hospedes ?>"><a href="index.php?pagina=hospedes"><i class="fa fa-angle-right"></i> Hóspedes</a></li>

									<li class="<?php echo $funcionarios ?>"><a href="index.php?pagina=funcionarios"><i class="fa fa-angle-right"></i> Funcionários</a></li>

									<li class="<?php echo $fornecedores ?>"><a href="index.php?pagina=fornecedores"><i class="fa fa-angle-right"></i> Fornecedores</a></li>

									<li class="<?php echo $usuarios ?>"><a href="index.php?pagina=usuarios"><i class="fa fa-angle-right"></i> Usuários</a></li>
									
								</ul>
							</li>

							<li class="treeview <?php echo $menu_cadastros ?>">
								<a href="#">
									<i class="fa fa-plus"></i>
									<span>Cadastros</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo $categorias_quartos ?>"><a href="index.php?pagina=categorias_quartos"><i class="fa fa-angle-right"></i> Tipo de Quarto</a></li>

									<li class="<?php echo $quartos ?>"><a href="index.php?pagina=quartos"><i class="fa fa-angle-right"></i> Quartos</a></li>

									<li class="<?php echo $cargos ?>"><a href="index.php?pagina=cargos"><i class="fa fa-angle-right"></i> Cargos</a></li>

									<li class="<?php echo $formas_pgto ?>"><a href="index.php?pagina=formas_pgto"><i class="fa fa-angle-right"></i> Formas Pgto</a></li>

									<?php if($ocultar_acessos != 'Não'){ ?>
									<li class="<?php echo $grupos ?>"><a href="index.php?pagina=grupos"><i class="fa fa-angle-right"></i> Grupos de Acesso</a></li>

									<li class="<?php echo $acessos ?>"><a href="index.php?pagina=acessos"><i class="fa fa-angle-right"></i> Acessos</a></li>
								<?php } ?>
									
								</ul>
							</li>


							<li class="treeview <?php echo $menu_reservas ?>">
								<a href="#">
									<i class="fa fa-calendar"></i>
									<span>Reservas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo $reservas ?>"><a href="index.php?pagina=reservas"><i class="fa fa-angle-right"></i> Reservas</a></li>

									<li class="<?php echo $filtrar_reservas ?>"><a href="index.php?pagina=filtrar_reservas"><i class="fa fa-angle-right"></i> Filtrar Reservas</a></li>

									<li class="<?php echo $modelo_relatorios ?>"><a href="index.php?pagina=modelo_relatorios"><i class="fa fa-angle-right"></i> Modelo Relatórios HTML</a></li>

									<li class="<?php echo $rel_quartos ?>"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalRelQuartos"><i class="fa fa-angle-right"></i> Quartos Disponíveis</a></li>

									<li class="<?php echo $quadro_reservas ?>"><a href="index.php?pagina=quadro_reservas"><i class="fa fa-angle-right"></i> Quadro Reservas</a></li>


									<li class="<?php echo $valores_criancas ?>"><a href="index.php?pagina=valores_criancas"><i class="fa fa-angle-right"></i> Valores Crianças</a></li>

									<li class="<?php echo $valores_datas ?>"><a href="index.php?pagina=valores_datas"><i class="fa fa-angle-right"></i> Valores Datas</a></li>
									
									<li class="<?php echo $bloqueio_datas ?>"><a href="index.php?pagina=bloqueio_datas"><i class="fa fa-angle-right"></i> Bloqueio Datas</a></li>

									<li class="<?php echo $mapa_reservas ?>"><a href="index.php?pagina=mapa_reservas"><i class="fa fa-angle-right"></i> Mapa de Reservas</a></li>
									
								</ul>
							</li>


							<li class="treeview <?php echo $menu_caixa ?>">
								<a href="#">
									<i class="fa fa-briefcase"></i>
									<span>Caixas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
								
								<li class="<?php echo $caixas ?>"><a href="index.php?pagina=caixas"><i class="fa fa-angle-right"></i>Caixas</a></li>
							

								<li class="<?php echo $rel_caixas ?>"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalRelCaixa"><i class="fa fa-angle-right"></i>Relatório de Caixas</a></li>							
									
									
								</ul>
							</li>


							<li class="treeview <?php echo $menu_financeiro ?>">
								<a href="#">
									<i class="fa fa-usd"></i>
									<span>Financeiro</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo $pagar ?>"><a href="index.php?pagina=pagar"><i class="fa fa-angle-right"></i>Contas à Pagar</a></li>

								<li class="<?php echo $receber ?>"><a href="index.php?pagina=receber"><i class="fa fa-angle-right"></i>Contas à Receber</a></li>


								<li class="<?php echo $compras ?>"><a href="index.php?pagina=compras"><i class="fa fa-angle-right"></i>Compras</a></li>

								<li class="<?php echo $rel_financeiro ?>"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalRelFinanceiro"><i class="fa fa-angle-right"></i>Relatórios</a></li>

								<li class="<?php echo $rel_lucro ?>"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalRelLucro"><i class="fa fa-angle-right"></i>Demonstrativo de Lucro</a></li>
									
									
								</ul>
							</li>



							<li class="treeview <?php echo $menu_produtos ?>">
								<a href="#">
									<i class="fa fa-product-hunt"></i>
									<span>Produtos</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo $categorias_produtos ?>"><a href="index.php?pagina=categorias_produtos"><i class="fa fa-angle-right"></i>Categorias</a></li>

								<li class="<?php echo $produtos ?>"><a href="index.php?pagina=produtos"><i class="fa fa-angle-right"></i>Produtos</a></li>

								<li class="<?php echo $entradas ?>"><a href="index.php?pagina=entradas"><i class="fa fa-angle-right"></i>Entradas</a></li>

								<li class="<?php echo $saidas ?>"><a href="index.php?pagina=saidas"><i class="fa fa-angle-right"></i>Saídas</a></li>

								<li class="<?php echo $estoque_baixo ?>"><a href="index.php?pagina=estoque_baixo"><i class="fa fa-angle-right"></i>Estoque Baixo</a></li>
									
									
								</ul>
							</li>


								<li class="treeview <?php echo $menu_servicos ?>">
								<a href="#">
									<i class="fa fa-plane"></i>
									<span>Serviços</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo $categorias_servicos ?>"><a href="index.php?pagina=categorias_servicos"><i class="fa fa-angle-right"></i>Categorias</a></li>

								<li class="<?php echo $servicos ?>"><a href="index.php?pagina=servicos"><i class="fa fa-angle-right"></i>Serviços</a></li>
									
									
								</ul>
							</li>


							<li class="treeview <?php echo $menu_vendas ?>">
								<a href="#">
									<i class="fa fa-money"></i>
									<span>Vendas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo $vendas_produtos ?>"><a href="index.php?pagina=vendas_produtos"><i class="fa fa-angle-right"></i>Produtos</a></li>

								<li class="<?php echo $vendas_servicos ?>"><a href="index.php?pagina=vendas_servicos"><i class="fa fa-angle-right"></i>Serviços</a></li>


								<li class="<?php echo $lista_vendas ?>"><a href="index.php?pagina=lista_vendas"><i class="fa fa-angle-right"></i>Lista de Vendas</a></li>

								<li class="<?php echo $lista_servicos ?>"><a href="index.php?pagina=lista_servicos"><i class="fa fa-angle-right"></i>Lista de Serviços</a></li>
									
									
								</ul>
							</li>


							<li class="treeview <?php echo $calendario ?>">
								<a href="index.php?pagina=calendario">
									<i class="fa fa-calendar"></i> <span>Calendário</span>
								</a>
							</li>


							<?php if($marketing_whats == 'Não'){ ?>
							<li class="treeview <?php echo $marketing ?>">
								<a href="index.php?pagina=marketing">
									<i class="fa fa-whatsapp"></i> <span>Marketing</span>
								</a>
							</li>
						<?php } ?>


						<?php if($seletor_api == 'menuia'){ ?>
								<li class="treeview <?php echo @$dispositivos ?>">
									<a href="index.php?pagina=dispositivos">
										<i class="fa fa-mobile"></i> <span>Dispositivos</span>
									</a>
								</li>
							<?php } 
							?>



							<li class="treeview <?php echo @$menu_site ?>">
								<a href="#">
									<i class="fa fa-firefox"></i>
									<span>Site</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$dados_site ?>"><a href="index.php?pagina=dados_site"><i class="fa fa-angle-right"></i>Dados do Site</a></li>

								<li class="<?php echo @$banners_site ?>"><a href="index.php?pagina=banners_site"><i class="fa fa-angle-right"></i>Banners Site</a></li>

								<li class="<?php echo @$especificacoes ?>"><a href="index.php?pagina=especificacoes"><i class="fa fa-angle-right"></i>Área de Lazer</a></li>

								<li class="<?php echo $galeria_site ?>"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalGaleria"><i class="fa fa-angle-right"></i>Galeria Site</a></li>

								<li class="<?php echo $comentarios ?>"><a href="index.php?pagina=comentarios"><i class="fa fa-angle-right"></i>Comentários</a></li>
								
									
									
								</ul>
							</li>


						</ul>
					</div>
					<!-- /.navbar-collapse -->
				</nav>
			</aside>
		</div>
		<!--left-fixed -navigation-->
		
		<!-- header-starts -->
		<div class="sticky-header header-section " style="display:inline-flex;">
			<div class="header-left" >
				<!--toggle button start-->
				<button id="showLeftPush" data-toggle="collapse" data-target=".collapse"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				<div class="profile_details_left" ><!--notifications of menu start -->
					<ul class="nofitications-dropdown ">
						<li class="dropdown head-dpdn ">

							<?php 
							$query = $pdo->query("SELECT * from reservas where check_out = '$data_atual' and (hora_checkout = '' or hora_checkout is null)");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							$total_checkout = @count($res);
							 ?>
							<a href="#" class="dropdown-toggle <?php echo $reservas ?>" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-calendar" style="color:#FFF"></i><span class="badge"><?php echo $total_checkout ?></span></a>
							<ul class="dropdown-menu">
								<li>
									<div class="notification_header">
										<h3>Você possui <?php echo $total_checkout ?> Checkout para Hoje!</h3>
									</div>
								</li>

								<?php 
									$query = $pdo->query("SELECT * from reservas where check_out = '$data_atual' and (hora_checkout = '' or hora_checkout is null) order by id asc limit 10");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							$linhas = @count($res);

							if($linhas > 0){
								for($i=0; $i<$linhas; $i++){
									$hospede = $res[$i]['hospede'];
									$quarto = $res[$i]['quarto'];

									$query2 = $pdo->query("SELECT * from quartos where id = '$quarto'");
									$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
									$numero_quarto = @$res2[0]['numero'];

									$query2 = $pdo->query("SELECT * from hospedes where id = '$hospede'");
									$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
									$nome_hospede = @$res2[0]['nome'];
								 ?>

								<li><a href="#">
									<div class="user_img"><img src="images/1.jpg" alt=""></div>
									<div class="notification_desc">
										<p><?php echo $nome_hospede ?> <span style="color:red !important">(<?php echo $numero_quarto ?>)</span></p>
										
									</div>
									<div class="clearfix"></div>	
								</a></li>
								
								<?php } } ?>
								
								<li>
									<div class="notification_bottom">
										<a href="index.php?pagina=reservas"><small>Ver Check-Outs</small></a>
									</div> 
								</li>
							</ul>
						</li>
						


						<li class="dropdown head-dpdn">

							<?php 
							$query = $pdo->query("SELECT * from pagar where data_venc = curDate() and pago = 'Não'");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							$total_checkout = @count($res);
							 ?>
							<a href="#" class="dropdown-toggle <?php echo $pagar ?>" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-money" style="color:#FFF"></i><span class="badge"><?php echo $total_checkout ?></span></a>
							<ul class="dropdown-menu">
								<li>
									<div class="notification_header">
										<h3>Você possui <?php echo $total_checkout ?> Contas à Pagar Hoje!</h3>
									</div>
								</li>

								<?php 
									$query = $pdo->query("SELECT * from pagar where data_venc = curDate() and pago = 'Não' order by id asc limit 10");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							$linhas = @count($res);

							if($linhas > 0){
								for($i=0; $i<$linhas; $i++){
									$descricao = $res[$i]['descricao'];
									$valor = $res[$i]['valor'];
									$valorF = number_format($valor, 2, ',', '.');
								 ?>

								<li><a href="#">
									<div class="user_img"><img src="images/1.jpg" alt=""></div>
									<div class="notification_desc">
										<p><?php echo $descricao ?> <span style="color:red !important">R$(<?php echo $valorF ?>)</span></p>
										
									</div>
									<div class="clearfix"></div>	
								</a></li>
								
								<?php } } ?>
								
								<li>
									<div class="notification_bottom">
										<a href="index.php?pagina=pagar"><small>Ver Contas</small></a>
									</div> 
								</li>
							</ul>
						</li>


					</ul>



				


					<div class="clearfix"> </div>
				</div>
				
			</div>
			<div class="header-right">


				<div class="search-box esc">
					<form class="input" id="formBuscaReservaPdf">
						<input class="sb-search-input input__field--madoka" placeholder="Código da Reserva" type="search" id="input-31" name="id"/>						
						<label class="input__label" for="input-31">
							<svg class="graphic" width="100%" height="100%" viewBox="0 0 404 77" preserveAspectRatio="none">
								<path d="m0,0l404,0l0,77l-404,0l0,-77z"/>
							</svg>
						</label>
					</form>					
				</div><!--//end-search-box-->

				
				<div class="profile_details">		
					<ul>
						
						<li class="dropdown profile_details_drop" style="display: flex; align-items: center; width: 100%;">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="display: flex; align-items: center; text-decoration: none; width: 100%;">
        
        <!-- Div que empurra nome + foto para a direita e dá ainda mais espaço -->
        <div style="display: flex; align-items: center; gap: 10px; margin-left: auto; max-width: 350px; overflow: hidden;">
            
            <!-- Imagem de Perfil -->
            <span class="prfil-img">
                <img src="<?php echo $url_sistema ?>painel/images/perfil/<?php echo rawurlencode($foto_usuario) ?>" alt="" width="50px" height="50px">
            </span> 
            
            <!-- Nome do Usuário -->
            <div class="user-name esc" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <p style="margin: 0; font-size: 14px; font-weight: bold;">
                    <?php echo $nome_usuarioF ?>
                </p>
                <span style="display: block; font-size: 12px; color: #777;">
                    <?php echo $nivel_usuario ?>
                </span>
            </div>

        </div>

        <!-- Ícones de Dropdown -->
        <i class="fa fa-angle-down lnr" style="margin-left: 10px;"></i>
        <i class="fa fa-angle-up lnr" style="display: none;"></i>
    </a>
    
    <ul class="dropdown-menu drp-mnu">
		<li class="<?php echo $configuracoes ?>"> <a href="javascript:void(0)" data-toggle="modal" data-target="#modalConfig"><i class="fa fa-cog"></i> Configurações</a> </li>
		<li> <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPerfil"><i class="fa fa-user"></i> Perfil</a> </li>
        <li> <a href="logout.php"><i class="fa fa-sign-out"></i> Sair</a> </li>
    </ul>
</li>

					</ul>
				</div>
				<div class="clearfix"> </div>				
			</div>
			<div class="clearfix"> </div>	
		</div>
		<!-- //header-ends -->




		<!-- main content start-->
		<div id="page-wrapper">
			<?php 
			require_once('paginas/'.$pagina.'.php');
			?>
		</div>





	</div>

	<!-- new added graphs chart js-->
	
	<script src="js/Chart.bundle.js"></script>
	<script src="js/utils.js"></script>
	
	
	
	<!-- Classie --><!-- for toggle left push menu script -->
	<script src="js/classie.js"></script>
	<script>
		var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
		showLeftPush = document.getElementById( 'showLeftPush' ),
		body = document.body;

		showLeftPush.onclick = function() {
			classie.toggle( this, 'active' );
			classie.toggle( body, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
			disableOther( 'showLeftPush' );
		};


		function disableOther( button ) {
			if( button !== 'showLeftPush' ) {
				classie.toggle( showLeftPush, 'disabled' );
			}
		}
	</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->

	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- side nav js -->
	<script src='js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
		$('.sidebar-menu').SidebarNav();

		// Fallback para ambientes sem rewrite (ex: php -S)
		$('.sidebar-menu').on('click', 'a', function(e){
			var href = $(this).attr('href');
			if(!href || href === '#' || $(this).data('toggle')){
				return;
			}

			if(href === 'index.php' || /^https?:/i.test(href) || /^index\.php\?/i.test(href) || /^javascript:/i.test(href)){
				return;
			}

			e.preventDefault();
			window.location = 'index.php?pagina=' + href.replace(/^\/+|\/+$/g, '');
		});
	</script>
	<!-- //side nav js -->
	
	
	
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->

<!-- SweetAlert JS -->
<script src="js/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="js/sweetalert1.min.css">
<script src="js/alertas.js"></script>



	<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

	
</body>
</html>






<!-- Modal Perfil -->
<div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Alterar Dados</h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-perfil">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-6">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome_perfil" name="nome" placeholder="Seu Nome" value="<?php echo $nome_usuario ?>" required>							
						</div>

						<div class="col-md-6">							
								<label>Email</label>
								<input type="email" class="form-control" id="email_perfil" name="email" placeholder="Seu Nome" value="<?php echo $email_usuario ?>" required>							
						</div>
					</div>


					<div class="row">
						<div class="col-md-4">							
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone_perfil" name="telefone" placeholder="Seu Telefone" value="<?php echo $telefone_usuario ?>" required>							
						</div>

						<div class="col-md-4">							
								<label>Senha</label>
								<input type="password" class="form-control" id="senha_perfil" name="senha" placeholder="Senha" value="<?php echo $senha_usuario ?>" required>							
						</div>

						<div class="col-md-4">							
								<label>Confirmar Senha</label>
								<input type="password" class="form-control" id="conf_senha_perfil" name="conf_senha" placeholder="Confirmar Senha" value="" required>							
						</div>

						
					</div>


					<div class="row">
						<div class="col-md-12">	
							<label>Endereço</label>
							<input type="text" class="form-control" id="endereco_perfil" name="endereco" placeholder="Seu Endereço" value="<?php echo $endereco_usuario ?>" >	
						</div>
					</div>
					


					<div class="row">
						<div class="col-md-8">							
								<label>Foto</label>
								<input type="file" class="form-control" id="foto_perfil" name="foto" value="<?php echo $foto_usuario ?>" onchange="carregarImgPerfil()">							
						</div>

						<div class="col-md-4">								
							<img src="<?php echo $url_sistema ?>painel/images/perfil/<?php echo rawurlencode($foto_usuario) ?>"  width="80px" id="target-usu">								
							
						</div>

						
					</div>


					<input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>">
				

				<br>
				<small><div id="msg-perfil" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>


<!-- Modal Progresso Rel Quartos -->
<div class="modal fade" id="modalGerandoRelQuartos" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Gerando PDF</h4>
			</div>
			<div class="modal-body" style="text-align:center;">
				<div style="font-size: 28px; margin-bottom: 8px;"><i class="fa fa-spinner fa-spin text-primary"></i></div>
				<div id="statusGerandoRelQuartos">Preparando relatório...</div>
				<small class="text-muted">Você pode continuar navegando enquanto o arquivo é gerado.</small>
				<div id="acoesGerandoRelQuartos" style="margin-top: 15px; display: none;">
					<a id="btnDownloadPdfGerado" class="btn btn-success btn-sm" href="#" download>
						<i class="fa fa-download"></i> Baixar PDF
					</a>
				</div>
			</div>
			<div class="modal-footer" style="justify-content: center;">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>








<!-- Modal Config -->
<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Editar Configurações</h4>
				<button id="btn-fechar-config" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-config">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-4">							
								<label>Nome do Projeto</label>
								<input type="text" class="form-control" id="nome_sistema" name="nome_sistema" placeholder="Delivery Interativo" value="<?php echo @$nome_sistema ?>" required>							
						</div>

						<div class="col-md-4">							
								<label>Email Sistema</label>
								<input type="email" class="form-control" id="email_sistema" name="email_sistema" placeholder="Email do Sistema" value="<?php echo @$email_sistema ?>" >							
						</div>


						<div class="col-md-4">							
								<label>Telefone Sistema</label>
								<input type="text" class="form-control" id="telefone_sistema" name="telefone_sistema" placeholder="Telefone do Sistema" value="<?php echo @$telefone_sistema ?>" required>							
						</div>

					</div>


					<div class="row">
						<div class="col-md-6">							
								<label>Endereço <small>(Rua Número Bairro e Cidade)</small></label>
								<input type="text" class="form-control" id="endereco_sistema" name="endereco_sistema" placeholder="Rua X..." value="<?php echo @$endereco_sistema ?>" >							
						</div>

						<div class="col-md-6">							
								<label>Instagram</label>
								<input type="text" class="form-control" id="instagram_sistema" name="instagram_sistema" placeholder="Link do Instagram" value="<?php echo @$instagram_sistema ?>">							
						</div>
					</div>



					<div class="row">
						<div class="col-md-3">							
								<label>No Show (Entrada %) </label>
								<input type="number" class="form-control"  name="no_show" value="<?php echo @$no_show ?>" >							
						</div>

						<div class="col-md-3">							
								<label>Cancelamento Grátis (Dias)</label>
								<input type="number" class="form-control"  name="dias_cancelamento" value="<?php echo @$dias_cancelamento ?>" >							
						</div>

						<div class="col-md-3">							
								<label>Taxa Cancelamento  %</label>
								<input type="number" class="form-control"  name="taxa_cancelamento" value="<?php echo @$taxa_cancelamento ?>" >							
						</div>

						<div class="col-md-3">							
								<label>Prazo Devolução</label>
								<input type="number" class="form-control"  name="prazo_devolucao" value="<?php echo @$prazo_devolucao ?>" placeholder="Dias">							
						</div>
					</div>



					<div class="row">
						<div class="col-md-3">							
								<label>Api Whatsapp</label>
								<select name="api_whatsapp" class="form-control">
									<option value="Sim" <?php if($api_whatsapp == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($api_whatsapp == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>						
						</div>

						<div class="col-md-3">							
								<label>Menuia</label>
								<select name="tipo_api" class="form-control">
									<option value="menuia" <?php if($tipo_api == 'menuia'){ ?> selected <?php } ?>>Menuia</option>
									<option value="word_mensagens" <?php if($tipo_api == 'word_mensagens'){ ?> selected <?php } ?>>Word Mensagens</option>
								</select>						
						</div>

						<div class="col-md-3">							
								<label>Token Whatsapp (appkey)</label>
								<input type="text" class="form-control"  name="token" value="<?php echo @$token ?>" >							
						</div>

						<div class="col-md-3">							
								<label>Instância (Authkey)</label>
								<input type="text" class="form-control"  name="instancia"  value="<?php echo @$instancia ?>">							
						</div>

						
					</div>



						<div class="row">

							<div class="col-md-3">							
								<label>Marca D'agua</label>
								<select name="marca_dagua" class="form-control">
									<option value="Sim" <?php if($marca_dagua == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($marca_dagua == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>						
						</div>

						<div class="col-md-3">		
							<label>Ocultar Acessos</label>
								<select name="ocultar_acessos" class="form-control">
									<option value="Sim" <?php if($ocultar_acessos == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($ocultar_acessos == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>	
						</div>

						<div class="col-md-3">		
							<label>Ocultar Marketing</label>
								<select name="marketing_whats" class="form-control">
									<option value="Sim" <?php if($marketing_whats == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($marketing_whats == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>	
						</div>

						<div class="col-md-3">							
								<label>Tempo Reserva</label>
								<input type="text" class="form-control"  name="tempo_reserva"  value="<?php echo @$tempo_reserva ?>" placeholder="Excluir pré-reserva minutos">							
						</div>
					</div>


					<?php if($nivel_usuario == 'Administrador'){ ?>
					<div class="row">	

						<div class="col-md-6">	
							<label>Access Token Mercado Pago</label>
							<input type="text" class="form-control"  name="access_token"  value="<?php echo @$access_token_mp ?>" placeholder="Chave Access Token do Mercado Pago">	
						</div>


						<div class="col-md-6">							
								<label>Public Key Mercado Pago</label>
								<input type="text" class="form-control"  name="public_key"  value="<?php echo @$public_key_mp ?>" placeholder="Chave Public Key do Mercado Pago">						
						</div>


					</div>
				<?php }else{ ?>
					<input type="hidden" class="form-control"  name="access_token"  value="<?php echo @$access_token_mp ?>" placeholder="Chave Access Token do Mercado Pago">	
					<input type="hidden" class="form-control"  name="public_key"  value="<?php echo @$public_key_mp ?>" placeholder="Chave Public Key do Mercado Pago">		
				<?php } ?>



					<div class="row">	

						<div class="col-md-3">	
							<label>Pagamento Pré Reserva %</label>
							<input type="number" class="form-control"  name="pagamento_percentual"  value="<?php echo @$pagamento_percentual ?>" placeholder="Ex 50%">	
						</div>


						<div class="col-md-3">							
								<label>Impressão Automática</label>
								<select name="impressao_automatica" class="form-control">
									<option value="Sim" <?php if($impressao_automatica == 'Sim'){ ?> selected <?php } ?>>Sim</option>
									<option value="Não" <?php if($impressao_automatica == 'Não'){ ?> selected <?php } ?>>Não</option>
								</select>						
						</div>


					</div>







					<div class="row">						
						<div class="col-md-12">	
							<label>Informações da Reserva</label>
							<textarea class="form-control" maxlength="2000" name="info_reserva"><?php echo $info_reserva ?></textarea>
						</div>
					</div>

					<div class="row">						
						<div class="col-md-12">	
							<label>Informações do CheckIn</label>
							<textarea class="form-control" maxlength="2000" name="info_checkin"><?php echo $info_checkin ?></textarea>
						</div>
					</div>




				
					

					<div class="row">
						<div class="col-md-4">						
								<div class="form-group"> 
									<label>Logo (*PNG)</label> 
									<input class="form-control" type="file" name="foto-logo" onChange="carregarImgLogo();" id="foto-logo">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $logo_sistema ?>"  width="80px" id="target-logo">									
								</div>
							</div>


							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Ícone (*Png)</label> 
									<input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto-icone">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $icone_sistema ?>"  width="50px" id="target-icone">									
								</div>
							</div>

						
					</div>




					<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Logo Relatório (*Jpg)</label> 
									<input class="form-control" type="file" name="foto-logo-rel" onChange="carregarImgLogoRel();" id="foto-logo-rel">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo @$logo_rel ?>"  width="80px" id="target-logo-rel">									
								</div>
							</div>




						<div class="col-md-4">
							<div class="form-group">
								<label>Fundo Login <small>(Imagem) 1920 x 1080</small></label>
								<input class="form-control" type="file" name="fundo_login" onChange="carregarImgFundo();"
									id="fundo_login">
							</div>
						</div>
						<div class="col-md-2">
							<div id="divImg">
								<img src="../img/<?php echo $fundo_login ?>" width="80px" id="target-fundo">
								<a title="Excluir Imagem" href="#" onclick="excluirImg('Fundo')"><i class="fa fa-close text-danger"></i></a>
							</div>
						</div>


						
					</div>					
				

				<br>
				<small><div id="msg-config" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>









<!-- Modal Rel Financeiro -->
<div class="modal fade" id="modalRelFinanceiro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório Financeiro</h4>
				<button id="btn-fechar-rel" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" id="formRelFinanceiro">
			<div class="modal-body">	
			<div class="row">
				<div class="col-md-4">
					<label>Data Inicial</label>
					<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				<div class="col-md-4">
					<label>Data Final</label>
					<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				<div class="col-md-4">
					<label>Filtro Data</label>
					<select name="filtro_data" class="form-control">
						<option value="data_lanc">Data de Lançamento</option>
						<option value="data_venc">Data de Vencimento</option>
						<option value="data_pgto">Data de Pagamento</option>
					</select>
				</div>
			</div>		


			<div class="row">				
				<div class="col-md-4">
					<label>Entradas / Saídas</label>
					<select name="filtro_tipo" class="form-control">
						<option value="receber">Entradas / Ganhos</option>
						<option value="pagar">Saídas / Despesas</option>
					</select>
				</div>

				<div class="col-md-4">
					<label>Tipo Lançamento</label>
					<select name="filtro_lancamento" class="form-control">
						<option value="">Tudo</option>
						<option value="Conta">Ganhos / Despesas</option>
						<option value="Venda">Vendas</option>
						<option value="Serviço">Serviços</option>
						<option value="Entrada">No-Show</option>
						<option value="Restante">Check-In</option>
						<option value="Cancelamento">Devoluções</option>
						<option value="Compra">Compras</option>
					</select>
				</div>
				<div class="col-md-4">
					<label>Pendentes / Pago</label>
					<select name="filtro_pendentes" class="form-control">
						<option value="">Tudo</option>
						<option value="Não">Pendentes</option>
						<option value="Sim">Pago</option>
					</select>
				</div>			
			</div>		
				
						

			</div>
			<div class="modal-footer">       
				<button type="button" class="btn btn-primary" onclick="gerarRelatorioFinanceiro()">Gerar</button>
			</div>
			</form>
		</div>
	</div>
</div>







<!-- Modal Rel Lucro -->
<div class="modal fade" id="modalRelLucro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Lucro</h4>
				<button id="btn-fechar-rel" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" id="formRelLucro">
			<div class="modal-body">	
			<div class="row">
				<div class="col-md-4">
					<label>Data Inicial</label>
					<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				<div class="col-md-4">
					<label>Data Final</label>
					<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				
			</div>		


								

			</div>
			<div class="modal-footer">       
				<button type="button" class="btn btn-primary" onclick="gerarRelatorioLucro()">Gerar</button>
			</div>
			</form>
		</div>
	</div>
</div>





<!-- Modal Rel Quartos -->
<div class="modal fade" id="modalRelQuartos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Quartos Disponíveis</h4>
				<button id="btn-fechar-rel" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="rel/quartos_prepare.php" id="formRelQuartos">
			<div class="modal-body">	
			<div class="row">
				<div class="col-md-4">
					<label>Data Inicial</label>
					<input id="data_inicial_rel" type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>" onchange="verificarData()">
				</div>

				<div class="col-md-4">
					<label>Data Final</label>
					<input id="data_final_rel" type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>" onchange="verificarData()">
				</div>

				
			</div>		


								

			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary" id="btnGerarRelQuartos">Gerar</button>
			</div>
			</form>
		</div>
	</div>
</div>









<!-- Modal Rel Financeiro -->
<div class="modal fade" id="modalRelCaixa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório Caixas</h4>
				<button id="btn-fechar-rel" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" id="formRelCaixa">
			<div class="modal-body">	
			<div class="row">
				<div class="col-md-4">
					<label>Data Inicial</label>
					<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				<div class="col-md-4">
					<label>Data Final</label>
					<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				<div class="col-md-4">
					<label>Operador</label>
					<select class="form-control sel350" aria-label="Default select example" name="operador" id="operador" style="width:100%" >
				<option value="">Selecionar Operador</option>
				<?php 

				$query = $pdo->query("SELECT * FROM usuarios order by nome asc");

				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				for($i=0; $i < @count($res); $i++){

					foreach ($res[$i] as $key => $value){}

						?>	

					<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?> </option>



				<?php } ?>			

				

			</select>
				</div>
			</div>		



			</div>
			<div class="modal-footer">       
				<button type="button" class="btn btn-primary" onclick="gerarRelatorioCaixas()">Gerar</button>
			</div>
			</form>
		</div>
	</div>
</div>







<!-- Modal Galeria -->
<div class="modal fade" id="modalGaleria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Galeria do Site</h4>
				<button id="btn-fechar-rel" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">	
				
				<form id="form_galeria" method="POST" enctype="multipart/form-data" >
				<div class="row">
					<div class="col-md-8">
						<input type="file" class="form-control" id="imggaleria" name="imggaleria[]" multiple="multiple">
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary" type="submit" id="btn_galeria">Salvar</button>
					</div>
				</div>
				
			</form>


				<hr>
				
				<div id="listar_fotos_galeria">
					
				</div>
				<small><div align="center" id="mensagem_excluir_galeria"></div></small>							

			</div>
						
		</div>
	</div>
</div>


<script type="text/javascript">

	$(document).ready(function() {

		$('.sel350').select2({

					dropdownParent: $('#modalRelCaixa')

				});

	});

</script>

<script type="text/javascript">
	function carregarImgPerfil() {
    var target = document.getElementById('target-usu');
    var file = document.querySelector("#foto_perfil").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>






 <script type="text/javascript">
	$("#form-perfil").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-perfil.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#msg-perfil').text('');
				$('#msg-perfil').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-perfil').click();
					location.reload();				
						

				} else {

					$('#msg-perfil').addClass('text-danger')
					$('#msg-perfil').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>






 <script type="text/javascript">
	$("#form-config").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-config.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#msg-config').text('');
				$('#msg-config').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-config').click();
					location.reload();				
						

				} else {

					$('#msg-config').addClass('text-danger')
					$('#msg-config').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>




<script type="text/javascript">
	function carregarImgLogo() {
    var target = document.getElementById('target-logo');
    var file = document.querySelector("#foto-logo").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgLogoRel() {
    var target = document.getElementById('target-logo-rel');
    var file = document.querySelector("#foto-logo-rel").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgIcone() {
    var target = document.getElementById('target-icone');
    var file = document.querySelector("#foto-icone").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }


    function verificarData(){
    	var dataInicial = $('#data_inicial_rel').val();
    	var dataFinal = $('#data_final_rel').val();
    	var dataAtual = "<?=$data_atual?>";

    	var dataIni = new Date(dataInicial);
    	var dataFin = new Date(dataFinal);
    	var dataHoje = new Date(dataAtual);

    	if(dataIni < dataHoje){
    		alert('Você não pode colocar uma data Retroativa!');
    		$('#data_inicial_rel').val(dataAtual);
    	}

    	if(dataFin < dataHoje){
    		alert('Você não pode colocar uma data Retroativa!');
    		$('#data_final_rel').val(dataAtual);
    	}
    }

    var timerRelQuartos = null;
    var inicioRelQuartos = 0;

    $("#formRelQuartos").submit(function(e){
    	e.preventDefault();

    	$("#btnGerarRelQuartos").prop('disabled', true);
    	inicioRelQuartos = Date.now();
    	$("#statusGerandoRelQuartos").text('Preparando relatório...');

    	$('#modalGerandoRelQuartos').modal({backdrop: false, keyboard: false});
    	$('#modalGerandoRelQuartos').modal('show');

    	clearInterval(timerRelQuartos);
    	timerRelQuartos = setInterval(function(){
    		var segundos = Math.floor((Date.now() - inicioRelQuartos) / 1000);
    		$("#statusGerandoRelQuartos").text('Gerando PDF... ' + segundos + 's');
    	}, 1000);

    	$.ajax({
    		url: 'rel/quartos_prepare.php',
    		type: 'POST',
    		data: $(this).serialize(),
    		dataType: 'json',
    		success: function(resp){
    			if(resp && resp.status === 'ok' && resp.url){
    				window.open(resp.url, '_blank');
    				$('#modalRelQuartos').modal('hide');
    			}else{
    				alert('Não foi possível gerar o relatório no momento.');
    			}
    		},
    		error: function(){
    			alert('Erro ao gerar relatório de quartos. Tente novamente.');
    		},
    		complete: function(){
    			clearInterval(timerRelQuartos);
    			$("#btnGerarRelQuartos").prop('disabled', false);
    			$("#statusGerandoRelQuartos").text('Finalizado.');
    			setTimeout(function(){
    				$('#modalGerandoRelQuartos').modal('hide');
    			}, 600);
    		}
    	});
    });





function carregarFotosGaleria(){

		$.ajax({
        url: 'paginas/galeria_site/listar-fotos.php',
        method: 'POST',
        data: {},
        dataType: "html",

        success:function(result){
            $("#listar_fotos_galeria").html(result);
            $('#mensagem_excluir_galeria').text('');
        }
    });
	}



$("#form_galeria").submit(function () {
	
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/galeria_site/salvar-fotos.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_excluir_galeria').text('');
            $('#mensagem_excluir_galeria').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {                
               carregarFotosGaleria(); 
            } else {

                $('#mensagem_excluir_galeria').addClass('text-danger')
                $('#mensagem_excluir_galeria').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});




</script>





<script type="text/javascript">
	

	function excluirImg(p){


		 const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
            cancelButton: "btn btn-danger me-1",
            container: 'swal-whatsapp-container'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Deseja Excluir?",
        text: "Você não conseguirá recuperá-lo novamente!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Excluir!",
        cancelButtonText: "Não, Cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza a requisição AJAX para excluir o item
            $.ajax({
                url: 'excluir_imagens.php',
                method: 'POST',
                data: { p },
                dataType: "html",
                success: function (mensagem) {
                    if (mensagem.trim() == "Excluído com Sucesso") {
                        // Exibe mensagem de sucesso após a exclusão
                        swalWithBootstrapButtons.fire({
                            title: mensagem,
                            text: 'Fecharei em 1 segundo.',
                            icon: "success",
                            timer: 1000,
                            timerProgressBar: true,
                            confirmButtonText: 'OK',
                            customClass: {
                             container: 'swal-whatsapp-container'
                             }
                        });
                       	location.reload();
                    } else {
                        // Exibe mensagem de erro se a requisição falhar
                        swalWithBootstrapButtons.fire({
                            title: "Opss!",
                            text: mensagem,
                            icon: "error",
                            confirmButtonText: 'OK',
                            customClass: {
                             container: 'swal-whatsapp-container'
                             }
                        });
                    }
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Fecharei em 1 segundo.",
                icon: "error",
                timer: 1000,
                timerProgressBar: true,
            });
        }
    });
		
	}
</script>



<script type="text/javascript">
	function carregarImgFundo() {
		var target = document.getElementById('target-fundo');
		var file = document.querySelector("#fundo_login").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}

	function abrirPdfAssincrono(urlPreparar, dados, label){
		dados = dados || {};
		dados._tentativas = dados._tentativas || 0;
		$('#statusGerandoRelQuartos').text(label ? label : 'Preparando relatório...');
		$('#acoesGerandoRelQuartos').hide();
		$('#btnDownloadPdfGerado').attr('href', '#');
		$('#modalGerandoRelQuartos').modal({backdrop: 'static', keyboard: false});
		$('#modalGerandoRelQuartos').modal('show');

		$.ajax({
			url: urlPreparar,
			type: 'POST',
			data: dados,
			dataType: 'json',
			success: function(resp){
				if(resp && resp.status === 'ok' && resp.url){
					$('#statusGerandoRelQuartos').text('PDF pronto.');
					$('#btnDownloadPdfGerado').attr('href', resp.url);
					$('#acoesGerandoRelQuartos').show();
				} else if(resp && resp.status === 'processing'){
					if(dados._tentativas < 8){
						dados._tentativas++;
						$('#statusGerandoRelQuartos').text('Gerando em segundo plano...');
						setTimeout(function(){
							abrirPdfAssincrono(urlPreparar, dados, label);
						}, 600);
					} else {
						$('#statusGerandoRelQuartos').text('O PDF ainda está sendo gerado. Tente novamente em alguns segundos.');
						setTimeout(function(){
							$('#modalGerandoRelQuartos').modal('hide');
						}, 1200);
					}
				}else{
					var msg = resp && resp.message ? resp.message : 'Não foi possível gerar o PDF agora.';
					if(resp && resp.debug && resp.debug.erro){
						msg += ' (' + resp.debug.erro + ')';
					}
					alert(msg);
					$('#modalGerandoRelQuartos').modal('hide');
				}
			},
			error: function(){
				alert('Erro ao gerar PDF.');
				$('#modalGerandoRelQuartos').modal('hide');
			},
			complete: function(){
				if(dados._tentativas === 0){
					setTimeout(function(){
						if($('#acoesGerandoRelQuartos').is(':hidden')){
							$('#modalGerandoRelQuartos').modal('hide');
						}
					}, 300);
				}
			}
		});
	}

	function gerarRelatorioFinanceiro(){
		abrirPdfAssincrono('rel/financeiro_prepare.php', {
			dataInicial: $('input[name="dataInicial"]').eq(0).val(),
			dataFinal: $('input[name="dataFinal"]').eq(0).val(),
			filtro_data: $('select[name="filtro_data"]').val(),
			filtro_tipo: $('select[name="filtro_tipo"]').val(),
			filtro_lancamento: $('select[name="filtro_lancamento"]').val(),
			filtro_pendentes: $('select[name="filtro_pendentes"]').val()
		}, 'Gerando relatório financeiro...');
	}

	function gerarRelatorioLucro(){
		abrirPdfAssincrono('rel/lucro_prepare.php', {
			dataInicial: $('#modalRelLucro input[name="dataInicial"]').val(),
			dataFinal: $('#modalRelLucro input[name="dataFinal"]').val()
		}, 'Gerando relatório de lucro...');
	}

	function gerarRelatorioCaixas(){
		abrirPdfAssincrono('rel/caixas_prepare.php', {
			dataInicial: $('#modalRelCaixa input[name="dataInicial"]').val(),
			dataFinal: $('#modalRelCaixa input[name="dataFinal"]').val(),
			operador: $('#modalRelCaixa select[name="operador"]').val()
		}, 'Gerando relatório de caixas...');
	}

	function gerarRelatorioProdutos(){
		abrirPdfAssincrono('rel/produtos_prepare.php', {}, 'Gerando relatório de produtos...');
	}

	function gerarRelatorioServicos(){
		abrirPdfAssincrono('rel/servicos_prepare.php', {}, 'Gerando relatório de serviços...');
	}

	function gerarRelatorioEstoque(){
		abrirPdfAssincrono('rel/estoque_prepare.php', {}, 'Gerando relatório de estoque...');
	}

	function abrirDetalhamentoCaixa(id){
		abrirPdfAssincrono('rel/detalhamento_caixa_prepare.php', {id: id}, 'Gerando detalhamento da caixa...');
	}

	$(function(){
		$('#formBuscaReservaPdf').on('submit', function(e){
			e.preventDefault();
			var id = $(this).find('input[name="id"]').val();
			if(id){
				abrirPdfAssincrono('rel/reserva_prepare.php', {id: id}, 'Gerando reserva em PDF...');
			}
		});
	});
</script>