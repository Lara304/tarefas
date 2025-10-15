<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarefa_id = intval($_POST['tarefa_id'] ?? 0);
    $novo_status = $_POST['novo_status'] ?? '';

    $valores_validos = ['pendente', 'em_andamento', 'concluida'];

    if ($tarefa_id > 0 && in_array($novo_status, $valores_validos)) {
        // Verificar se tarefa pertence ao usuário
        $stmt = $mysqli->prepare("SELECT id FROM tarefas WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $tarefa_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->close();
            // Atualizar status
            $stmt = $mysqli->prepare("UPDATE tarefas SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param('si', $novo_status, $tarefa_id);
            $stmt->execute();
        }
    }
}

header('Location: tarefas.php');
exit;