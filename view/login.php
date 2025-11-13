<?php
include __DIR__ . '/../config/conexao.php';

$erro = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($login) && !empty($senha)) {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE login = ? AND senha = ?");
        $stmt->bind_param("ss", $login, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Salva dados na sessão
            $_SESSION['usuario'] = $login;
            // Identificar a chave 'id' independentemente de case (algumas tabelas usam 'Id')
            $user_id_val = $user['id'] ?? $user['Id'] ?? $user['ID'] ?? (isset($user[0]) ? $user[0] : null);
            // Garantir que gravamos um inteiro para session persistence
            $_SESSION['usuario_id'] = intval($user_id_val ?? 0);
            // Regenera ID da sessão para segurança e garante que os dados sejam persistidos
            session_regenerate_id(true);
            session_write_close();
            header("Location: index.php?page=home");
            exit;
        } else {
            $erro = "Login ou senha inválidos!";
        }
    } else {
        $erro = "Preencha login e senha!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Administração de Imóveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(45deg, cyan, yellow); font-family:'Montserrat',Arial,sans-serif; display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0; }
        .tela-login { background: rgba(0,0,0,0.8); padding:40px 32px; border-radius:20px; color:#fff; min-width:350px; box-shadow:0 0 20px #222; }
        .tela-login h1 { font-weight:700; margin-bottom:24px; }
        .btn-primary { font-weight:700; letter-spacing:1px; }
        a { color:#0ff; text-decoration:underline; }
    </style>
</head>
<body>
    <div class="tela-login">
        <h1>Login</h1>
        <?php if($erro) echo "<p class='text-danger'>$erro</p>"; ?>
        <form method="POST">
            <input type="text" name="login" placeholder="Digite seu login" required class="form-control mb-3">
            <input type="password" name="senha" placeholder="Digite sua senha" required class="form-control mb-3">
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <br>
        <a href="index.php?page=cadastrarUsuario">Não possui conta? Cadastre-se agora</a>
    </div>
</body>
</html>
