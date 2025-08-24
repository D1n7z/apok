<?php
    include_once("generateToken.php");

    $retorno = processForgotPassword();

    if($retorno){
        $email = $retorno[0];
        $hash_token = $retorno[1];
        error_log("Email para envio: " . $email);
        error_log("Token gerado: " . $hash_token);
        
        $data = [
            "email" => $email,
            "token" => $hash_token
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents('https://mailer.up.railway.app/send-email', false, $context);
        if($result === FALSE){
            error_log("Erro ao enviar solicitação para o servidor.");
        }
    } else{
        error_log("Erro ao processar a solicitação de recuperação de senha.");
    }

?>