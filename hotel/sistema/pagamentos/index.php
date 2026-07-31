<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
//error_reporting(E_ALL);

include("./config.php");
include("../conexao.php");

$id_reserva = @$_POST['id_reserva'];
if($id_reserva == ""){
   $id_reserva = @$_GET['id_reserva']; 
}

if(@$_POST['porc_servico'] != ""){
    $porc_servico = $_POST['porc_servico'];
}

if(@$_GET['porc_servico'] != ""){
    $porc_servico = $_POST['porc_servico'];
}

//excluir reservas pendentes de finalização
$pdo->query("DELETE FROM reservas WHERE reserva_site = 'Sim' and no_show <= 0 and hora_excluir < curTime() and hora_excluir is not null");  

$query = $pdo->query("SELECT * from reservas where id = '$id_reserva'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas == 0){
    echo 'Tempo para fechar compra finalizado, faça novamente !';

    echo '<script>window.location="../../index.php"</script>';  
    exit();
}else{
    $hospede = $res[0]['hospede'];
    $tipo_quarto = $res[0]['tipo_quarto'];
    $quarto = $res[0]['quarto'];
    $funcionario = $res[0]['funcionario'];
    $check_in = $res[0]['check_in'];
    $check_out = $res[0]['check_out'];
    $valor = $res[0]['valor'];
    $no_show = $res[0]['no_show'];
    $hospedes = $res[0]['hospedes'];
    $obs = $res[0]['obs'];
    $valor_diaria = $res[0]['valor_diaria'];
    $data = $res[0]['data'];
    $desconto = $res[0]['desconto'];
    $forma_pgto = $res[0]['forma_pgto'];
    $ref_pgto = $res[0]['ref_pgto'];

    $query2 = $pdo->query("SELECT * from categorias_quartos where id = '$tipo_quarto'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $nome_tipo = @$res2[0]['nome'];


}

if($ref_pgto != ""){
     require('consultar_pagamento.php');
     if($status_api == 'approved'){
         echo 'Essa reserva Já foi Paga e está aprovada!';  
         exit();  
        }
}

if ($porc_servico > 0) {
    $valor = $valor * $porc_servico / 100;
}

$valor = round($valor, 2);

$valorF = number_format($valor, 2, ',', '.');



$query2 = $pdo->query("SELECT * from hospedes where id = '$hospede'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $nome_cliente = @$res2[0]['nome'];
    $cpf_cliente = @$res2[0]['cpf'];
    $telefone_hospede = @$res2[0]['telefone'];
    $email_cliente = @$res2[0]['email'];

$token_valor = ($valor!="")? sha1($valor) : "";
$doc = $cpf_cliente;
$doc =  str_replace(array(",", ".", "-", "/", " "), "", $doc);
$ref = $_REQUEST["ref"];
$email = $email_cliente;
$gerarDireto = $_REQUEST["gerarDireto"];
$descricao = $descricao;
$nome = $nome_cliente;
$sobrenome = $_REQUEST["sobrenome"];

?>
<html lang="pt-br">
<head>
    <title>Pagamento</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <link href="./assets/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/signin.css" rel="stylesheet">
    <script src="./assets/jquery-3.6.4.min.js"></script>
</head>
<body  class="text-center">


<form action="../painel/rel/reserva_class.php" method="get" style="display:none">
    <input type="hidden" name="id" value="<?=$id_reserva;?>">
     <input type="hidden" name="enviar" value="sim">
    <button id="btn_form" type="submit"></button>
</form>



<div style="max-width: 500px; max-height: 800px; margin: 0 auto;  text-align: center; margin-bottom: 20px; word-break: break-all;" >

<div align="center">
    <img src="../img/logo.png" width="80px">
</div>
<br>

<div id="info_pagamento" style="text-align: center;"> 
        <h4 class="h3 mb-3 font-weight-normal" style=" font-size: 18px; border-radius: 4px;"><span>(Quarto <?=$nome_tipo;?>)</span> <span style="color:green">R$ <?=$valorF;?></span></h4>  


        <?php if($porc_servico > 0){ ?>
         <?php if($pagamento_percentual != 100){ ?>
         <div style="margin-top: -8px; margin-bottom: 8px; font-size: 13px">
            <form action="" method="post" style="display: inline;">
        <input type="hidden" name="id_conta" value="<?= $id_conta ?>">
        <input type="hidden" name="porc_servico" value="<?= $pagamento_percentual ?>">
        <input type="hidden" name="id_reserva" value="<?= $id_reserva ?>">
        <button type="submit" style="background: none; border: none; padding: 0; margin: 0; font-size: 13px; color: #007bff; text-decoration: underline; cursor: pointer;">
            Pagar <?= $pagamento_percentual ?>%
        </button>
    </form>

    /
    
    <!-- Formulário para 100% -->
    <form action="" method="post" style="display: inline;">
        <input type="hidden" name="id_conta" value="<?= $id_conta ?>">
        <input type="hidden" name="porc_servico" value="100">
        <input type="hidden" name="id_reserva" value="<?= $id_reserva ?>">
        <button type="submit" style="background: none; border: none; padding: 0; margin: 0; font-size: 13px; color: #007bff; text-decoration: underline; cursor: pointer;">
            Pagar 100%
        </button>
    </form>

        </div>
        <?php } } ?>
        
        

        <?php if($porc_servico <= 0){ ?>
        <div style="margin-bottom: 8px; font-size: 13px">
             <form action="<?php echo $url_sistema ?>pagamentos/aprovar_pgto.php" method="post" style="display: inline;">       
        <input type="hidden" name="id_reserva" value="<?= $id_reserva ?>">
        <button type="submit" style="background: none; border: none; padding: 0; margin: 0; font-size: 13px; color: #007bff; text-decoration: underline; cursor: pointer;">
           <b><span id="clique_aqui">>>CLIQUE AQUI<<</span></b> 
        </button>
        para confirmar a reserva e deixar para pagar no local
    </form>
        </div>
        <?php } ?>

</div>    

<div  id="paymentBrick_container">
        </div>

        <p><b><small>Confirmaçao da Reserva somente apos confirmação do pagamento</small></b></p>

        <div id="statusScreenBrick_container">
        </div>
        <div class="form-signin" id="form-pago" style="display:none;text-align: center;">
            <h1 class="h3 mb-3 font-weight-normal">Obrigado!</h1>
            <img class="mb-4"  src="<?=$url_sistema;?>pagamentos/assets/check_ok.png" alt="" width="120" height="120">
            <br>
            <h5><?=$MSG_APOS_PAGAMENTO;?></h5>
            <br>
            Código do pagamento: <?php echo $_GET["id"]; ?>
        </div>
    </div>
    <style>
        body{font-family:arial}
    </style>
    <script>
        var payment_check;
        const mp = new MercadoPago('<?=$TOKEN_MERCADO_PAGO_PUBLICO;?>', {
            locale: 'pt-BR'
        });
        const bricksBuilder = mp.bricks();
        const renderPaymentBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    amount: '<?=$valor;?>',
                    payer: {
                        firstName: "<?=$nome;?>",
                        lastName: "<?=$sobrenome;?>",
                        email: "<?=$email;?>",
                        identification: {
                            type: '<?=(strlen($doc)>11? "CNPJ" : "CPF");?>',
                            number: '<?=$doc;?>',
                        },
                        address: {
                            zipCode: '',
                            federalUnit: '',
                            city: '',
                            neighborhood: '',
                            streetName: '',
                            streetNumber: '',
                            complement: '',
                        }
                    },
                },
                customization: {
                    visual: {
                        style: {
                            theme: "dark",
                        },
                    },
                    paymentMethods: {
                        <?php if($ATIVAR_CARTAO_CREDITO=="1"){?>creditCard: "all",<?php } ?>
                        <?php if($ATIVAR_CARTAO_DEBIDO=="1"){?>debitCard: "all",<?php } ?>
                        <?php if($ATIVAR_BOLETO=="1"){?>ticket: "all",<?php } ?>
                        <?php if($ATIVAR_PIX=="1"){?>bankTransfer: "all",<?php } ?>
                        maxInstallments: 12
                    },
                },
                callbacks: {
                    onReady: () => {
                    },
                    onSubmit: ({ selectedPaymentMethod, formData }) => {

                        formData.external_reference = '<?=$ref;?>';
                        formData.description = '<?=$descricao;?>';
                        var id_reserva = '<?=$id_reserva;?>';
                        var porc_servico = '<?=$porc_servico;?>';

                        return new Promise((resolve, reject) => {
                            fetch("<?=$url_sistema;?>pagamentos/process_payment.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify(formData),
                            })
                            .then((response) => response.json())
                            .then((response) => {
                // receber o resultado do pagamento
                                if(response.status==true){
                                    window.location.href = "<?=$url_sistema;?>pagamentos/index.php?id="+response.id+'&id_reserva='+id_reserva+'&porc_servico='+porc_servico;
                                }
                                if(response.status!=true){
                                    alert(response.message);
                                }
                                resolve();
                            })
                            .catch((error) => {
                                reject();
                            });
                        });
                    },
                    onError: (error) => {
                        console.error(error);
                    },
                },
            };
            window.paymentBrickController = await bricksBuilder.create(
                "payment",
                "paymentBrick_container",
                settings
                );
        };

        const renderStatusScreenBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    paymentId: '<?=$_GET["id"];?>',
                },
                customization: {
                    visual: {
                        hideStatusDetails: false,
                        hideTransactionDate: false,
                        style: {
            theme: 'dark', // 'default' | 'dark' | 'bootstrap' | 'flat'
        }
    },
    backUrls: {
        //'error': '<http://<your domain>/error>',
        //'return': '<http://<your domain>/homepage>'
    }
},
callbacks: {
    onReady: () => {
        check("<?=$_GET["id"];?>", "<?=$_GET["id_reserva"];?>");
    },
    onError: (error) => {
    },
},
};
window.statusScreenBrickController = await bricksBuilder.create('statusScreen', 'statusScreenBrick_container', settings);
};

<?php if($_GET["id"]!=""){ ?>
    renderStatusScreenBrick(bricksBuilder);
<?php } else { ?>
    <?php if($valor==""){?>
        alert("O valor do pagamento está vazio.");
    <?php } ?>
    renderPaymentBrick(bricksBuilder);
<?php } ?>
var redi = "<?=$URL_REDIRECIONAR;?>";
function check(id, id_reserva) {
    var settings = {
        "url": "<?=$url_sistema;?>pagamentos/process_payment.php?acc=check&id=" + id + "&id_reserva=" + id_reserva,
        "method": "GET",
        "timeout": 0
    };
    $.ajax(settings).done(function(response) {
        try {
            if (response.status == "pago") {
                $("#statusScreenBrick_container").slideUp("fast");
                $("#form-pago").slideDown("fast");
                if (redi.trim() == "Sim") {
                    setTimeout(() => {
                        //window.location = redi;
                        $("#btn_form").click();
                    }, 6000);
                }
            } else {
                setTimeout(() => {
                    check(id)
                }, 3000);
            }
        } catch (error) {
            alert("Erro ao localizar o pagamento, contacte com o suporte");
        }
    });
}
</script>



</body>
</html>