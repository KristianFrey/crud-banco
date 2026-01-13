<?php
require_once __DIR__ . '/../../Infraestrutura/conexaoBanco.php';
require_once __DIR__ . '/../../Infraestrutura/Repository/PdoClienteRepository.php';

$pdo = ConexaoBanco::conectarBanco();
$clienteRepository = new PdoClienteRepository($pdo);

$dados = $clienteRepository->buscaTodosClientes();
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
        <form>
            <h2>Cadastrar Cliente</h2>

            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome">

            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone">

            <label for="email">Email</label>
            <input type="email" name="email" id="email">

            <input type="submit" value="Cadastrar">
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
                        <a href="#">Editar</a>
                        <a href="#">Excluir</a>
                    </td> <?php
                            echo "</tr>";
                        }
                    } ?>
        </table>
    </section>

</body>

</html>