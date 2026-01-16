<?php
require_once __DIR__ . '/../../Infraestrutura/conexaoBanco.php';
require_once __DIR__ . '/../../Infraestrutura/Repository/PdoClienteRepository.php';
require_once __DIR__ . '/Cliente.php';

$pdo = ConexaoBanco::conectarBanco();
$dados = new PdoClienteRepository();
$modelCliente = new Cliente($dados);

if (isset($_POST['nome'])) { //verifica se a pessoa clicou em cadastrar, atraves do submit
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $email = $_POST['email'] ?? null;

    if (isset($nome) && isset($telefone) && isset($email)) {
        try {
            $modelCliente->repository->salvarCliente($id, $nome, $telefone, $email);
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            echo "<p style='color:red'>" . $e->getMessage() . "</p>";
        }
    } else {
        echo "Preencha todos os campos";
    }
}

$delete = $_GET['delete'] ?? null;
if ($delete == "sim") {
    $id = ($_GET['id']);
    $modelCliente->repository->deletarCliente($id);
    header("Location: index.php");
    exit;
}

$update = $_GET['update'] ?? null;
if ($update == "sim") {
    $id = ($_GET['id']);
    $dadosCliente = $modelCliente->repository->buscaClientePorId($id);
}

$dados = $modelCliente->repository->buscaTodosClientes();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section id="esquerda">
        <form method="POST">
            <h2>Cadastrar Cliente</h2>

            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?php if (isset($update)) {
                                                                echo $dadosCliente['nome'];
                                                            }  ?>">
            <label for=" telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php if (isset($update)) {
                                                                        echo $dadosCliente['telefone'];
                                                                    }  ?>">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php if (isset($update)) {
                                                                    echo $dadosCliente['email'];
                                                                }  ?>">

            <input type="submit" value="<?php if (isset($update)) {
                                            echo "Atualizar";
                                        } else {
                                            echo "Cadastrar";
                                        }
                                        ?>">
        </form>
    </section>

    <section id="direita">
        <table>
            <tr id="titulo">
                <th>Id</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>

            <?php
            if (count($dados) > 0) {
                foreach ($dados as $cliente) {
                    echo "<tr>";
                    echo "<td>" . $cliente['id'] . "</td>";
                    echo "<td>" . $cliente['nome'] . "</td>";
                    echo "<td>" . $cliente['telefone'] . "</td>";
                    echo "<td>" . $cliente['email'] . "</td>"; ?>
                    <td>
                        <a href="index.php?id=<?= $cliente['id'] ?>&update=sim">Editar</a>
                        <a href="index.php?id=<?= $cliente['id'] ?>&delete=sim"
                            onclick="return confirm('Tem certeza que deseja excluir?')">
                            Excluir
                        </a>
                    </td> <?php
                            echo "</tr>";
                        }
                    } else {
                        echo "Nenhum registro de clientes!";
                    }
                            ?>
        </table>
    </section>

</body>

</html>