<?php
require_once 'db.php';
require_once 'header.php';

$nome = $email = $senha = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } else {
        // Verificar se email já existe
        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = 'E-mail já cadastrado.';
        } else {
            // Criar hash da senha e inserir
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $nome, $email, $senha_hash);
            if ($stmt->execute()) {
                header('Location: login.php?msg=registrado');
                exit;
            } else {
                $erro = 'Erro ao cadastrar.';
            }
        }
        $stmt->close();
    }
}
?>

<h2>Registrar</h2>

<?php if ($erro): ?>
    <p style="color:red;"><?=htmlspecialchars($erro)?></p>
<?php endif; ?>

<form method="post">
    Nome: <input type="text" name="nome" value="<?=htmlspecialchars($nome)?>" required /><br />
    E-mail: <input type="email" name="email" value="<?=htmlspecialchars($email)?>" required /><br />
    Senha: <input type="password" name="senha" required /><br />
    <button type="submit">Registrar</button>
</form>

<?php
// fechar body e html aqui, se não for incluído em outro arquivo
?>