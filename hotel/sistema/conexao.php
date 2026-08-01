<?php 

//definir fuso horário
date_default_timezone_set('America/Sao_Paulo');

$modo_teste = 'Não';

//dados conexão bd VPS
$servidor = getenv('DB_HOST') ?: 'localhost';
$banco = getenv('DB_NAME') ?: 'hotel_db';
$usuario = getenv('DB_USER') ?: 'hoteluser';
$senha = getenv('DB_PASS') ?: 'Jumentor0x1n';

try {
	$pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8mb4", $usuario, $senha, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	]);
} catch (PDOException $e) {
	die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}


function getBaseUrl($pathPrefix = '') {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');

    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');

    $documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $scriptFile = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');

    $basePath = '';

    if (!empty($documentRoot) && !empty($scriptFile) && strpos($scriptFile, $documentRoot) === 0) {
        $relativePath = substr($scriptFile, strlen($documentRoot));
        $relativeDir = dirname($relativePath);

        if ($relativeDir !== '/' && $relativeDir !== '.' && $relativeDir !== '\\') {
            $basePath = str_replace('\\', '/', $relativeDir);
        }
    } elseif (!empty($scriptName)) {
        $scriptDir = dirname($scriptName);

        if ($scriptDir !== '/' && $scriptDir !== '.' && $scriptDir !== '\\') {
            $basePath = str_replace('\\', '/', $scriptDir);
        }
    }

    if ($basePath !== '') {
        $basePath = '/' . trim($basePath, '/');
    }

    if ($pathPrefix !== '') {
        $pathPrefix = '/' . trim($pathPrefix, '/');
        $basePath .= $pathPrefix;
    }

    return $scheme . '://' . $host . $basePath . '/';
}

$url_sistema = getBaseUrl('/sistema');

//variaveis globais
$nome_sistema = 'Nome Sistema';
$email_sistema = 'contato@hugocursos.com.br';
$telefone_sistema = '(31)97527-5084';

$query = $pdo->query("SELECT * from config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas == 0){
	$pdo->query("INSERT INTO config SET nome = '$nome_sistema', email = '$email_sistema', telefone = '$telefone_sistema', logo = 'logo.png', logo_rel = 'logo.jpg', icone = 'icone.png', api_whatsapp = 'Não', no_show = '50', dias_cancelamento = '30', taxa_cancelamento = '30', marca_dagua = 'Sim', prazo_devolucao = '7', marketing_whats = 'Não', tempo_reserva = '15', pagamento_percentual = '100'");
}else{
$nome_sistema = $res[0]['nome'];
$email_sistema = $res[0]['email'];
$telefone_sistema = $res[0]['telefone'];
$endereco_sistema = $res[0]['endereco'];
$instagram_sistema = $res[0]['instagram'];
$logo_sistema = $res[0]['logo'];
$logo_rel = $res[0]['logo_rel'];
$icone_sistema = $res[0]['icone'];
$api_whatsapp = $res[0]['api_whatsapp'];
$token = $res[0]['token'];
$instancia = $res[0]['instancia'];
$token_whatsapp = $res[0]['token'];
$instancia_whatsapp = $res[0]['instancia'];
$no_show = $res[0]['no_show'];
$dias_cancelamento = $res[0]['dias_cancelamento'];
$taxa_cancelamento = $res[0]['taxa_cancelamento'];
$marca_dagua = $res[0]['marca_dagua'];
$info_reserva = $res[0]['info_reserva'];
$info_checkin = $res[0]['info_checkin'];
$prazo_devolucao = $res[0]['prazo_devolucao'];
$ativo_sistema = $res[0]['ativo'];
$ocultar_acessos = $res[0]['ocultar_acessos'];
$marketing_whats = $res[0]['marketing_whats'];
$tipo_api = $res[0]['tipo_api'];
$seletor_api = $res[0]['tipo_api'];
$tempo_reserva = $res[0]['tempo_reserva'];
$pagamento_percentual = $res[0]['pagamento_percentual'];
$porc_servico = $res[0]['pagamento_percentual'];
$impressao_automatica = $res[0]['impressao_automatica'];
$fundo_login = $res[0]['fundo_login'];
$public_key_mp = $res[0]['public_key'];
$access_token_mp = $res[0]['access_token'];
if($fundo_login == ""){
	$fundo_login = 'sem-foto.png';
}

$whatsapp_sistema = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema);
}	


if($ativo_sistema != 'Sim' and $ativo_sistema != ''){ ?>
	<style type="text/css">
		@media only screen and (max-width: 700px) {
		  .imgsistema_mobile{
		    width:300px;
		  }    
		}
	</style>
	<div style="text-align: center; margin-top: 100px">
	<img src="<?php echo $url_sistema ?>img/bloqueio.png" class="imgsistema_mobile">	
	</div>
<?php 
exit();
} 
 ?>
