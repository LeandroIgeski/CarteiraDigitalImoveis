
<?php
	$msg = "";
	$ia_result = null;

	function gerarAnaliseIA($valor_imovel, $valor_aluguel, $localizacao) {
		// Obter chave da API de variável de ambiente ou usar padrão (REMOVA ANTES DE FAZER PUSH)
		$api_key = getenv('HUGGINGFACE_API_KEY') ?: 'sua_chave_aqui'; 
		if ($api_key === 'sua_chave_aqui') {
			return '<div style="color:red;">⚠️ Chave da API Hugging Face não configurada. Configure a variável de ambiente HUGGINGFACE_API_KEY</div>';
		}
		$prompt = "Analise o seguinte imóvel para investimento:\nValor do imóvel: R$ $valor_imovel\nValor do aluguel: R$ $valor_aluguel\nLocalização: $localizacao\nForneça IPTU, custos mensais, lucro mensal, lucro anual, payback, análise e recomendação baseada no mercado brasileiro. Responda em formato de lista.";

		$data = ["inputs" => $prompt];
	$ch = curl_init("https://api-inference.huggingface.co/models/facebook/blenderbot-3B");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
			"Authorization: Bearer $api_key"
		]);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = curl_exec($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		$result = json_decode($response, true);
		if (isset($result[0]['generated_text'])) {
			return $result[0]['generated_text'];
		} else {
			return '<div style="color:red;word-break:break-all;">Não foi possível obter análise da IA.<br>Erro cURL: ' . htmlspecialchars($curl_error) . '<br>Resposta da API: <pre>' . htmlspecialchars($response) . '</pre></div>';
		}
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include __DIR__ . '/../../config/conexao.php';

		$valor_imovel = trim($_POST['valor_imovel'] ?? '');
		$valor_aluguel = trim($_POST['valor_aluguel'] ?? '');
		$localizacao = trim($_POST['localizacao'] ?? '');
		$usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : null;

		if (!empty($valor_imovel) && !empty($valor_aluguel) && !empty($localizacao) && !empty($usuario_id)) {
			$iptu = number_format($valor_imovel * 0.012, 2, '.', '');
			$custos_mensais = number_format($valor_imovel * 0.003 + 150, 2, '.', '');
			$lucro_mensal = number_format($valor_aluguel - $custos_mensais - ($iptu/12), 2, '.', '');
			$lucro_anual = number_format($lucro_mensal * 12, 2, '.', '');
			$payback = $lucro_anual > 0 ? number_format($valor_imovel / $lucro_anual, 2, '.', '') : 0;

			// Chama a IA para gerar a análise
			$analise = gerarAnaliseIA($valor_imovel, $valor_aluguel, $localizacao);

			$sql = "INSERT INTO imoveis (valor_imovel, valor_aluguel, localizacao, iptu, custos_mensais, lucro_mensal, lucro_anual, payback, analise, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ddsddddssi", $valor_imovel, $valor_aluguel, $localizacao, $iptu, $custos_mensais, $lucro_mensal, $lucro_anual, $payback, $analise, $usuario_id);
			if ($stmt->execute()) {
				$msg = "Imóvel cadastrado com sucesso!";
				$ia_result = [
					'IPTU' => $iptu,
					'Custos Mensais' => $custos_mensais,
					'Lucro Mensal' => $lucro_mensal,
					'Lucro Anual' => $lucro_anual,
					'Payback' => $payback,
					'Análise' => $analise
				];
			} else {
				$msg = "Erro ao cadastrar imóvel: " . $stmt->error;
			}
		} else {
			$msg = "Erro: Preencha todos os campos obrigatórios ou faça login novamente.";
		}
	}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Cadastrar Imóvel</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			background: linear-gradient(45deg, cyan, yellow);
			min-height: 100vh;
			font-family: Arial, sans-serif;
		}
		.cadastro-box {
			background: rgba(0, 0, 0, 0.7);
			color: #fff;
			padding: 40px;
			border-radius: 15px;
			margin: 40px auto;
			max-width: 500px;
			box-shadow: 0 0 15px rgba(0,0,0,0.3);
		}
		.cadastro-box h2 {
			font-weight: 700;
			margin-bottom: 24px;
		}
		.btn-primary {
			font-weight: 700;
			letter-spacing: 1px;
		}
		.alert {
			font-size: 1.1em;
			border-radius: 10px;
		}
	</style>
</head>
<body>
	<div class="cadastro-box">
		<h2>Cadastrar Imóvel</h2>
		<?php if($msg) echo "<p style='color:yellow;'>$msg</p>"; ?>
		<form method="POST">
			<input type="number" step="0.01" name="valor_imovel" placeholder="Valor do Imóvel" required class="form-control mb-3">
			<input type="number" step="0.01" name="valor_aluguel" placeholder="Valor do Aluguel" required class="form-control mb-3">
			<div style="position:relative;">
				<input type="text" id="localizacao" name="localizacao" placeholder="Localização" required class="form-control mb-3" autocomplete="off">
				<div id="autocomplete-list" class="list-group" style="position:absolute;top:48px;left:0;width:100%;z-index:10;max-height:180px;overflow-y:auto;box-shadow:0 4px 16px rgba(0,0,0,0.3);background:#222;border-radius:0 0 12px 12px;"></div>
			</div>
			<button type="submit" class="btn btn-primary w-100">Cadastrar Imóvel</button>
		</form>
		<!-- Apenas mensagem de cadastro, sem exibir dados automáticos/IA -->
	</div>
	<script>
	// Sugestão de localizações reais usando API do Geoapify (gratuita)
	document.getElementById('localizacao').addEventListener('input', function() {
		var query = this.value;
		var list = document.getElementById('autocomplete-list');
		list.innerHTML = '';
		if (query.length < 3) return;
		fetch('https://api.geoapify.com/v1/geocode/autocomplete?text=' + encodeURIComponent(query) + '&limit=5&apiKey=7d0092a331b94b30873437b090628a0a')
			.then(response => response.json())
			.then(data => {
				if (data.features) {
					data.features.forEach(function(item) {
						var option = document.createElement('button');
						option.type = 'button';
						option.className = 'list-group-item list-group-item-action';
						option.textContent = item.properties.formatted;
						option.onclick = function() {
							document.getElementById('localizacao').value = item.properties.formatted;
							list.innerHTML = '';
						};
						list.appendChild(option);
					});
				}
			});
	});
	</script>
</body>
</html>
