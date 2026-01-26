<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }
    </style>
</head>

<body>
    <h2>Relatório de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $cliente): ?>
                <tr>
                    <td><?= $cliente['id'] ?></td>
                    <td><?= $cliente['nome'] ?></td>
                    <td><?= $cliente['telefone'] ?></td>
                    <td><?= $cliente['email'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>