<?php
session_start();

$current = basename($_SERVER['PHP_SELF']); // Nome do arquivo atual

function menuItem($file, $label, $current) {
    $style = ($file === $current) ? 'font-weight:bold;' : '';
    echo "<a href='$file' style='$style'>$label</a> | ";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Mini Sistema Tarefas</title>
</head>
<body>
<nav>
<?php if (isset($_SESSION['user_id'])): ?>
    <?php
    menuItem('tarefas.php', 'Minhas Tarefas', $current);
    menuItem('tarefa_nova.php', 'Nova Tarefa', $current);
    menuItem('logout.php', 'Sair', $current);
    ?>
<?php else: ?>
    <?php
    menuItem('login.php', 'Login', $current);
    menuItem('registrar.php', 'Registrar', $current);
    ?>
<?php endif; ?>
</nav>
<hr />