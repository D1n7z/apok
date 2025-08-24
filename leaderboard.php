<?php
// leaderboard.php

$jsonFile = __DIR__ . '/leaderboard.json';
if (!file_exists($jsonFile)) {
    echo "Arquivo não encontrado.";
    exit;
}

$data = json_decode(file_get_contents($jsonFile), true);
if (!$data) {
    echo "Erro ao ler JSON.";
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
    <style>
        table { border-collapse: collapse; width: 60%; margin: 30px auto; }
        th, td { border: 1px solid #888; padding: 8px 12px; text-align: center; }
        th { background: #eee; }
        body { font-family: Arial, sans-serif; background: #fafafa; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Leaderboard</h1>
    <table>
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