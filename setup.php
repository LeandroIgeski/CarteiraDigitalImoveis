<?php
/**
 * Script de inicialização do banco de dados
 * Execute uma única vez para criar as tabelas
 * Acesse: http://localhost/Adm/setup.php
 */

// Configurações do banco
$hostname = "localhost";
$usuario = "root";
$senha = "";

// Conectar sem banco de dados (para criar banco)
$conn = new mysqli($hostname, $usuario, $senha);

if ($conn->connect_errno) {
    die("<h2 style='color:red;'>❌ Erro ao conectar MySQL: " . $conn->connect_error . "</h2>");
}

// Ler arquivo SQL
$sql_file = __DIR__ . '/database.sql';
if (!file_exists($sql_file)) {
    die("<h2 style='color:red;'>❌ Arquivo database.sql não encontrado!</h2>");
}

$sql_content = file_get_contents($sql_file);

// Executar comandos SQL
$commands = array_filter(array_map('trim', explode(';', $sql_content)), function($cmd) {
    return !empty($cmd) && strpos($cmd, '--') === false;
});

$success_count = 0;
$error_count = 0;
$messages = [];

foreach ($commands as $command) {
    if (trim($command) === '') continue;
    
    if ($conn->multi_query($command)) {
        // Consumir todos os resultados
        while ($conn->more_results()) {
            $conn->next_result();
        }
        $success_count++;
        $messages[] = "✅ Executado: " . substr($command, 0, 50) . "...";
    } else {
        $error_count++;
        $messages[] = "❌ Erro: " . $conn->error;
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Setup - Inicialização do Banco</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, cyan, yellow);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .setup-box {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 40px;
            border-radius: 15px;
            max-width: 600px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .setup-box h1 {
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .message {
            padding: 8px 12px;
            margin: 5px 0;
            border-radius: 5px;
            font-family: monospace;
            font-size: 0.9em;
        }
        .success {
            background: rgba(0, 255, 0, 0.1);
            border-left: 3px solid #00ff00;
            color: #00ff00;
        }
        .error {
            background: rgba(255, 0, 0, 0.1);
            border-left: 3px solid #ff0000;
            color: #ff0000;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .btn-link-home {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="setup-box">
        <h1>⚙️ Setup do Banco de Dados</h1>
        
        <div class="messages">
            <?php foreach ($messages as $msg): ?>
                <div class="message <?php echo strpos($msg, '✅') === 0 ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="summary">
            <p><strong>Resultado:</strong></p>
            <p>✅ Sucesso: <?php echo $success_count; ?></p>
            <p>❌ Erros: <?php echo $error_count; ?></p>
            
            <?php if ($error_count === 0): ?>
                <h3 style="color: #00ff00; margin-top: 20px;">🎉 Banco de dados inicializado com sucesso!</h3>
                <p>Usuário de teste criado:</p>
                <p><strong>Login:</strong> teste</p>
                <p><strong>Senha:</strong> teste123</p>
            <?php else: ?>
                <h3 style="color: #ff6666; margin-top: 20px;">⚠️ Houve erros na inicialização!</h3>
                <p>Verifique os erros acima e tente novamente.</p>
            <?php endif; ?>
        </div>

        <div class="btn-link-home">
            <a href="index.php?page=login" class="btn btn-primary">Ir para Login</a>
        </div>
    </div>
</body>
</html>
