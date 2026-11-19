<?php
$titulo = "Usuários cadastrados";
require __DIR__ . "/../layouts/header.php";
?>

<section>
    <h1>Usuários cadastrados</h1>

    <div class="tabela-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th>Perfil</th>
                    <th>Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= e($usuario['nome']) ?></td>
                        <td><?= e($usuario['email']) ?></td>
                        <td><?= e($usuario['cpf']) ?></td>
                        <td><?= e($usuario['perfil']) ?></td>
                        <td><?= date('d/m/Y', strtotime($usuario['criado_em'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
