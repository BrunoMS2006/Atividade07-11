<?php
include("conecta.php");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$message = ""; 


if (isset($_POST["add_user"])) {
    $funcionario_cod = trim($_POST["funcionario_cod"]);
    $funcionario_nome = trim($_POST["funcionario_nome"]);
    $funcionario_cargo = trim($_POST["funcionario_cargo"]);

    if (!empty($funcionario_cod) && !empty($funcionario_nome) && !empty($funcionario_cargo)) {
        $sql = "INSERT INTO funcionarios (funcionario_cod, funcionario_nome, funcionario_cargo) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $funcionario_cod, $funcionario_nome, $funcionario_cargo);

        if ($stmt->execute()) {
            $message = "Funcionário cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar funcionário";
        }
        $stmt->close();
    }
}


if (isset($_POST["edit_user"])) {
    $funcionario_cod = trim($_POST["usu_cod"]);
    $funcionario_nome = trim($_POST["nome"]);
    $funcionario_cargo = trim($_POST["cargo"]);

    if (!empty($funcionario_cod) && !empty($funcionario_nome) && !empty($funcionario_cargo)) {
        $sql = "UPDATE funcionarios SET funcionario_nome = ?, funcionario_cargo = ? WHERE funcionario_cod = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $funcionario_nome, $funcionario_cargo, $funcionario_cod);

        if ($stmt->execute()) {
            $message = "Funcionário editado com sucesso!";
        } else {
            $message = "Erro ao editar funcionário";
        }
        $stmt->close();
    }
}


if (isset($_POST["delete_user"])) {
    $funcionario_cod = trim($_POST["usu_cod"]);

    if (!empty($funcionario_cod)) {
        $sql = "DELETE FROM funcionarios WHERE funcionario_cod = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $funcionario_cod);

        if ($stmt->execute()) {
            $message = "Funcionário excluído com sucesso!";
        } else {
            $message = "Erro ao excluir funcionário";
        }
        $stmt->close();
    }
}


$sql = "SELECT * FROM funcionarios";
$usuarios = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <style>
        .buttoninicial{
            border-bottom: 3px;
            border-radius: 4px;
            background-color: blueviolet;
            display: flex;
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            position: left;
            width: 50px;
            color: aliceblue;
            height: 50px;

        }
    </style>
</head>
<body>
<h2>Cadastro de Funcionários</h2>
<a href="index.html" class="buttoninicial">Tela inicial</a>

<?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>


<form method="POST">
    <label for="funcionario_cod">Código:</label>
    <input type="number" id="funcionario_cod" name="funcionario_cod" required>

    <label for="funcionario_nome">Nome:</label>
    <input type="text" id="funcionario_nome" name="funcionario_nome" required>

    <label for="funcionario_cargo">Cargo:</label>
    <input type="text" id="funcionario_cargo" name="funcionario_cargo" required>

    <button type="submit" name="add_user">Cadastrar</button>
</form>


<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($usuario = $usuarios->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['funcionario_cod']) ?></td>
                <td><?= htmlspecialchars($usuario['funcionario_nome']) ?></td>
                <td><?= htmlspecialchars($usuario['funcionario_cargo']) ?></td>
                <td class="actions">
                    <button onclick="openModal(<?= htmlspecialchars($usuario['funcionario_cod']) ?>, '<?= htmlspecialchars($usuario['funcionario_nome']) ?>', '<?= htmlspecialchars($usuario['funcionario_cargo']) ?>')">Editar</button>

                    <!-- Formulário para Excluir Funcionário -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="usu_cod" value="<?= htmlspecialchars($usuario['funcionario_cod']) ?>">
                        <button type="submit" name="delete_user">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Editar Funcionário</h3>
        <form method="POST">
            <input type="hidden" id="usu_cod" name="usu_cod">
            <label for="edit_nome">Nome:</label>
            <input type="text" id="edit_nome" name="nome" required>

            <label for="edit_cargo">Cargo:</label>
            <input type="text" id="edit_cargo" name="cargo" required>

            <button type="submit" name="edit_user">Salvar Alterações</button>
        </form>
    </div>
</div>

<script>
// Função para abrir o modal
function openModal(cod, nome, cargo) {
    document.getElementById('usu_cod').value = cod;
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_cargo').value = cargo;
    document.getElementById('editModal').style.display = 'block';
}

// Função para fechar o modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Fechar o modal se o usuário clicar fora dele
window.onclick = function(event) {
    if (event.target == document.getElementById('editModal')) {
        closeModal();
    }
}
</script>
</body>
</html>
