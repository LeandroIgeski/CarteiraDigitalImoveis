
<?php
	$msg = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include __DIR__ . '/../../config/conexao.php';
		$login = $_POST['login'] ?? '';
		$senha = $_POST['senha'] ?? '';
		$nome = $_POST['nome'] ?? '';
		$email = $_POST['email'] ?? '';
		$cpf = $_POST['cpf'] ?? '';

		if ($login && $senha && $nome && $email && $cpf) {
			$sql = "INSERT INTO usuarios (login, senha, nome, email, cpf) VALUES (?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("sssss", $login, $senha, $nome, $email, $cpf);
			if ($stmt->execute()) {
				$msg = "Usuário cadastrado com sucesso!";
				header("Location: index.php?page=login");
				exit;
			} else {
				$msg = "Erro ao cadastrar usuário.";
			}
		} else {
			$msg = "Preencha todos os campos.";
		}
	}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Cadastro de Usuário</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
	<style>
		body {
			background: linear-gradient(45deg, cyan, yellow);
			font-family: 'Montserrat', Arial, sans-serif;
			min-height: 100vh;
			margin: 0;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.cadastro-box {
			background: rgba(0,0,0,0.8);
			color: #fff;
			padding: 40px 32px;
			border-radius: 20px;
			min-width: 350px;
			box-shadow: 0 0 20px #222;
		}
		.cadastro-box h2 {
			font-weight: 700;
			margin-bottom: 24px;
		}
		.btn-primary {
			font-weight: 700;
			letter-spacing: 1px;
		}
	</style>
</head>
<body>
	<div class="cadastro-box">
		<h2>Cadastro de Usuário</h2>
		<?php if($msg) echo "<p style='color:yellow;'>$msg</p>"; ?>
		<form method="POST">
			<input type="text" name="login" placeholder="Login" required class="form-control mb-2">
			<input type="password" name="senha" placeholder="Senha" required class="form-control mb-2">
			<input type="text" name="nome" placeholder="Nome" required class="form-control mb-2">
			<input type="email" name="email" placeholder="Email" required class="form-control mb-2">
			<input type="text" name="cpf" placeholder="CPF" required class="form-control mb-2">
			<button type="submit" class="btn btn-primary w-100">Cadastrar</button>
		</form>
		<br>
		<a href="index.php?page=login" style="color: #0ff; text-decoration: underline;">Já possui conta? Faça login</a>
	</div>
</body>
</html>
