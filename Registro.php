<?php
include("conecta.php");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$message = ""; 

if (isset($_POST["add_registro"])) {
    $registro_cod = trim($_POST["registro_cod"]);
    $registro_data = trim($_POST["registro_data"]);
    $funcionario_cod = trim($_POST["funcionario_cod"]);
    $registro_hora = trim($_POST["registro_hora"]);

    if (!empty($registro_cod) && !empty($registro_data) && !empty($funcionario_cod) && !empty($registro_hora)) {
        $sql = "INSERT INTO registro (registro_cod, registro_data, funcionario_cod, registro_hora) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $registro_cod, $registro_data, $funcionario_cod, $registro_hora);

        if ($stmt->execute()) {
            $message = "Registro cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar o registro.";
        }
        $stmt->close();
    }
}

// Editar registro
if (isset($_POST["edit_registro"])) {
    $registro_cod = trim($_POST["registro_cod"]);
    $registro_data = trim($_POST["registro_data"]);
    $funcionario_cod = trim($_POST["funcionario_cod"]);
    $registro_hora = trim($_POST["registro_hora"]);

    if (!empty($registro_cod) && !empty($registro_data) && !empty($funcionario_cod) && !empty($registro_hora)) {
        $sql = "UPDATE registro SET registro_data = ?, funcionario_cod = ?, registro_hora = ? WHERE registro_cod = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $registro_data, $funcionario_cod, $registro_hora, $registro_cod);

        if ($stmt->execute()) {
            $message = "Registro editado com sucesso!";
        } else {
            $message = "Erro ao editar o registro.";
        }
        $stmt->close();
    }
}

// Excluir registro
if (isset($_POST["delete_registro"])) {
    $registro_cod = trim($_POST["registro_cod"]);

    if (!empty($registro_cod)) {
        $sql = "DELETE FROM registro WHERE registro_cod = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $registro_cod);

        if ($stmt->execute()) {
            $message = "Registro excluído com sucesso!";
        } else {
            $message = "Erro ao excluir o registro.";
        }
        $stmt->close();
    }
}

// Selecionar registros para exibição
$sql = "SELECT * FROM registro";
$usuarios = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros</title>
</head>
<body>
<h2>Cadastro de Registros</h2>
<a href="index.html" class="buttoninicial">Tela inicial</a>

<?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">
    <label for="registro_cod">Código:</label>
    <input type="number" id="registro_cod" name="registro_cod" required>

    <label for="registro_data">Data:</label>
    <input type="date" id="registro_data" name="registro_data" required>

    <label for="funcionario_cod">Código Funcionário:</label>
    <input type="number" id="funcionario_cod" name="funcionario_cod" required>

    <label for="registro_hora">Horário:</label>
    <input type="time" id="registro_hora" name="registro_hora" required>

    <button type="submit" name="add_registro">Cadastrar</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Código Funcionário</th>
            <th>Horário</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($usuario = $usuarios->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['registro_cod']) ?></td>
                <td><?= htmlspecialchars($usuario['registro_data']) ?></td>
                <td><?= htmlspecialchars($usuario['funcionario_cod']) ?></td>
                <td><?= htmlspecialchars($usuario['registro_hora']) ?></td>
                <td class="actions">
                    <button onclick="openModal(<?= htmlspecialchars($usuario['registro_cod']) ?>, '<?= htmlspecialchars($usuario['registro_data']) ?>', '<?= htmlspecialchars($usuario['funcionario_cod']) ?>', '<?= htmlspecialchars($usuario['registro_hora']) ?>')">Editar</button>

                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="registro_cod" value="<?= htmlspecialchars($usuario['registro_cod']) ?>">
                        <button type="submit" name="delete_registro">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Editar Registro</h3>
        <form method="POST">
            <input type="hidden" id="edit_registro_cod" name="registro_cod">
            <label for="edit_registro_data">Data:</label>
            <input type="date" id="edit_registro_data" name="registro_data" required>

            <label for="edit_funcionario_cod">Código Funcionário:</label>
            <input type="number" id="edit_funcionario_cod" name="funcionario_cod" required>

            <label for="edit_registro_hora">Horário:</label>
            <input type="time" id="edit_registro_hora" name="registro_hora" required>

            <button type="submit" name="edit_registro">Salvar Alterações</button>
        </form>
    </div>
</div>

<script>
function openModal(cod, data, funcionario_cod, hora) {
    document.getElementById('edit_registro_cod').value = cod;
    document.getElementById('edit_registro_data').value = data;
    document.getElementById('edit_funcionario_cod').value = funcionario_cod;
    document.getElementById('edit_registro_hora').value = hora;
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('editModal')) {
        closeModal();
    }
}
</script>
</body>
</html>
