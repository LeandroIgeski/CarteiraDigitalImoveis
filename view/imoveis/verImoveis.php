
<?php
include __DIR__ . '/../../config/conexao.php';
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    header("Location: index.php?page=login");
    exit;
}

$sql = "SELECT * FROM imoveis WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$erro = null;

if (!$result) {
    $erro = "Erro na consulta: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Imóveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, cyan, yellow);
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        .table-box {
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 40px;
            border-radius: 15px;
            margin: 40px auto;
            max-width: 1100px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        th, td { color: #fff; }
        .alert {
            font-size: 1.1em;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="table-box">
        <h2 class="mb-4 text-center">🏠 Meus Imóveis</h2>

        <?php if ($erro): ?>
            <div class="alert alert-danger text-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-exclamation-triangle me-2" viewBox="0 0 16 16">
                    <path d="M7.938 2.016a.13.13 0 0 1 .124 0l6.857 11.856c.027.047.04.1.04.153a.27.27 0 0 1-.04.153.13.13 0 0 1-.124.065H1.205a.13.13 0 0 1-.124-.065.27.27 0 0 1-.04-.153c0-.053.013-.106.04-.153L7.938 2.016zM8 5c-.535 0-.954.462-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 5zm.002 6a.905.905 0 1 0 0 1.81.905.905 0 0 0 0-1.81z"/>
                </svg>
                <strong>Ops!</strong> <?= $erro ?>
            </div>
        <?php else: ?>
            <?php if($result->num_rows == 0): ?>
                <div class="d-flex flex-column align-items-center justify-content-center py-5" style="background: rgba(0,0,0,0.7); border-radius: 15px; box-shadow: 0 0 15px rgba(0,0,0,0.3); max-width: 500px; margin: 40px auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#0dcaf0" class="bi bi-house-door mb-3" viewBox="0 0 16 16">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 2 7.5V14a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3h2v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V7.5a.5.5 0 0 0-.146-.354l-6-6zM13 7.707V14a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-.5.5h-3A.5.5 0 0 1 3 14V7.707l5-5 5 5z"/>
                        <path d="M7.293 2.5a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1-1.414 1.414L8 4.914l-5.293 5.293A1 1 0 0 1 1.293 8.5l6-6z"/>
                    </svg>
                    <h4 class="text-info mb-2">Você não possui imóveis cadastrados!</h4>
                    <p class="text-light">Cadastre seu primeiro imóvel para começar a investir.</p>
                </div>
            <?php else: ?>
                <table class="table table-dark table-striped table-hover align-middle text-center">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th>ID</th>
                            <th>Valor Imóvel</th>
                            <th>Valor Aluguel</th>
                            <th>Localização</th>
                            <th>IPTU</th>
                            <th>Custos Mensais</th>
                            <th>Lucro Mensal</th>
                            <th>Lucro Anual</th>
                            <th>Payback</th>
                            <th>Análise I.A</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td>R$ <?= number_format($row['valor_imovel'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($row['valor_aluguel'], 2, ',', '.') ?></td>
                                <td><?= $row['localizacao'] ?></td>
                                <td>R$ <?= number_format($row['iptu'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($row['custos_mensais'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($row['lucro_mensal'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($row['lucro_anual'], 2, ',', '.') ?></td>
                                <td><?= $row['payback'] ?> anos</td>
                                <td><?= substr($row['analise'], 0, 50) ?>...</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
