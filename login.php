<?php
require_once 'db.php';
require_once 'header.php';

session_start();

$email = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $stmt = $mysqli->prepare("SELECT id, senha_hash FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id, $senha_hash);
        if ($stmt->fetch()) {
            if (password_verify($senha, $senha_hash)) {
                $_SESSION['user_id'] = $id;
                header('Location: tarefas.php');
                exit;
            } else {
                $erro = 'Senha incorreta.';
            }
        } else {
            $erro = 'Usuário não encontrado.';
        }
        $stmt->close();
    }
}
?>

<h2>Login</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'registrado'): ?>
    <p style="color:green;">Registrado com sucesso! Faça login.</p>
<?php endif; ?>

<?php if ($erro): ?>
    <p style="color:red;"><?=htmlspecialchars($erro)?></p>
<?php endif; ?>

<form method="post">
    E-mail: <input type="email" name="email" value="<?=htmlspecialchars($email)?>" required /><br />
    Senha: <input type="password" name="senha" required /><br />
    <button type="submit">Entrar</button>
</form>