<?php

$apiUrl = 'https://apimemories.onrender.com/leaderboard'; 
$data = null; 
$errorMessage = ''; 

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 45); 
$response = curl_exec($ch);

// Verifica se ocorreu um erro no cURL (como timeout)
if (curl_errno($ch)) {
    if (curl_errno($ch) == CURLE_OPERATION_TIMEDOUT) {
        $errorMessage = "O servidor do leaderboard demorou muito para responder. Por favor, tente atualizar a página em alguns instantes.";
    } else {
        $errorMessage = "Não foi possível carregar o leaderboard. Erro de conexão.";
    }
} else {
    
    $data = json_decode($response, true);
    if (!$data || isset($data['erro'])) {
        $errorMessage = "Erro ao buscar os dados do leaderboard. A API pode estar temporariamente indisponível.";
        $data = null; 
    } else {
        usort($data, function($a, $b) {
            $npA = floatval($a['n_percentage']);
            $npB = floatval($b['n_percentage']);
            if ($npA !== $npB) {
                return $npB <=> $npA;
            }
            $rtA = floatval($a['run_time']);
            $rtB = floatval($b['run_time']);
            if ($rtA !== $rtB) {
                return $rtA <=> $rtB;
            }
            return strcmp($a['nome'], $b['nome']);
        });
    }
}

curl_close($ch);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilo para a mensagem de aviso/erro */
        .info-box {
            background-color: #fffbe6;
            border: 1px solid #ffe58f;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #8a6d3b;
        }
        .error-box {
            background-color: #f2dede;
            border-color: #ebccd1;
            color: #a94442;
        }
    </style>
</head>
<body>
    <h1>Leaderboard</h1>

    <div class="info-box">
        <strong>Atenção:</strong> Se o serviço esteve inativo, o carregamento inicial do leaderboard pode demorar até um minuto. Obrigado pela paciência!
    </div>

    <?php if ($errorMessage): ?>
        <div class="info-box error-box">
            <p><?php echo htmlspecialchars($errorMessage); ?></p>
        </div>
    <?php elseif ($data): ?>
        <table class="leaderboard">
            <tr>
                <th>Nome</th>
                <th>n_percentage</th>
                <th>run_time</th>
            </tr>
            <?php foreach ($data as $entry): ?>
            <tr>
                <td><?= htmlspecialchars($entry['nome']) ?></td>
                <td><?= htmlspecialchars($entry['n_percentage']) ?></td>
                <td><?= htmlspecialchars($entry['run_time']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>