<?php
if($tipo_api == "word_mensagens"){
  $url = "http://api.wordmensagens.com.br/send-doc";


  $post_request = array(
    'instance' => $instancia,
    'to' => $telefone_envio,
    'token' => $token,
    'message' => $mensagem,
    'licence' => 'hugocursos',
    "url" => $url_envio
  );

  $post_request = json_encode($post_request);
  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_request);
    $result = curl_exec($ch);
    curl_close($ch);
   

   echo $result;
}


if($tipo_api == "menuia"){
  $mensagem = 'Detalhamento';
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://chatbot.menuia.com/api/create-message',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
  'appkey' => $token,
  'authkey' => $instancia,
  'to' => $telefone_envio,
  'message' => $mensagem,
  'file' => $url_envio,
  'sandbox' => 'false'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

}
?>
  