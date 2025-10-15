<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'header.php';

$erro = '';
$titulo = '';
$descricao = '';
$prazo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $prazo = trim($_POST['prazo'] ?? '');

    if (empty($titulo)) {
        $erro = 'Título é obrigatório.';
    } else {
        // Validar prazo se preenchido
        if ($prazo !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $prazo)) {
            $erro = 'Prazo inválido. Use o formato YYYY-MM-DD.';
        } else {
            $status = 'pendente';
            $stmt = $mysqli->prepare("INSERT INTO tarefas (user_id, titulo, descricao, status, prazo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('issss', $_SESSION['user_id'], $titulo, $descricao, $status, $prazo === '' ? null : $prazo);
            if ($stmt->execute()) {
                header('Location: tarefas.php');
                exit;
            } else {
                $erro = 'Erro ao criar tarefa.';
            }
            $stmt->close();
        }
    }
}
?>

<h2>Nova Tarefa</h2>

<?php if ($erro): ?>
    <p style="color:red;"><?=htmlspecialchars($erro)?></p>
<?php endif; ?>

<form method="post">
    Título: <input type="text" name="titulo" value="<?=htmlspecialchars($titulo)?>" required /><br />
    Descrição: <textarea name="descricao"><?=htmlspecialchars($descricao)?></textarea><br />
    Prazo: <input type="date" name="prazo" value="<?=htmlspecialchars($prazo)?>" /><br />
    <button type="submit">Criar</button>
</form>