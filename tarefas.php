<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'header.php';

// Buscar tarefas do usuário logado
$stmt = $mysqli->prepare("SELECT id, titulo, descricao, status, prazo, created_at FROM tarefas WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Minhas Tarefas</h2>

<a href="tarefa_nova.php">Criar nova tarefa</a>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Prazo</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($tarefa = $result->fetch_assoc()): ?>
            <tr>
                <td><?=htmlspecialchars($tarefa['titulo'])?></td>
                <td><?=htmlspecialchars($tarefa['descricao'])?></td>
                <td><?=htmlspecialchars($tarefa['status'])?></td>
                <td><?=htmlspecialchars($tarefa['prazo'])?></td>
                <td><?=htmlspecialchars($tarefa['created_at'])?></td>
                <td>
                    <form method="post" action="tarefa_status.php" style="display:inline">
                        <input type="hidden" name="tarefa_id" value="<?=$tarefa['id']?>" />
                        <select name="novo_status" onchange="this.form.submit()">
                            <?php
                            $statuses = ['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída'];
                            foreach ($statuses as $key => $label) {
                                $selected = ($tarefa['status'] === $key) ? 'selected' : '';
                                echo "<option value='$key' $selected>$label</option>";
                            }
                            ?>
                        </select>
                    </form>
                    <!-- Opcional: editar e excluir -->
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>