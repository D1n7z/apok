<?php

$apiUrl = 'https://apimemories.onrender.com/leaderboard'; 

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if (!$data || isset($data['erro'])) {
    echo "Erro ao buscar leaderboard.";
    exit;
}

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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Leaderboard</h1>
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
</body>
</html>