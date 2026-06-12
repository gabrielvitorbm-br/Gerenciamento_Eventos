<?php
    require_once('cabecalho.php');
?>

<div class="row mb-3">
    <div class="col d-flex justify-content-between align-items-center">
        <h2>👥 Lista de Participantes</h2>
        <a href="novo_participante.php" class="btn btn-success">
            + Cadastrar Novo Participante
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome do Participante</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Descrição</th> <th scope="col" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        require_once('conexao.php');

                        try {
                            
                            $stmt = $pdo->query("SELECT * FROM participante ORDER BY nome ASC");
                            $participantes = $stmt->fetchAll();

                            if (count($participantes) > 0) {
                                foreach ($participantes as $p) {
                                   
                                    $desc = !empty($p['descricao']) ? htmlspecialchars($p['descricao']) : '<em class="text-muted">Sem descrição</em>';

                                    echo "<tr>";
                                    echo "<td>{$p['id']}</td>";
                                    echo "<td><strong>{$p['nome']}</strong></td>";
                                    echo "<td>{$p['email']}</td>";
                                    echo "<td>{$p['cpf']}</td>";
                                    echo "<td>{$desc}</td>"; 
                                    echo "<td class='text-center'>
                                            <a href='alterar_participante.php?id={$p['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>Nenhum participante cadastrado ainda. Clique no botão verde para adicionar o primeiro!</td></tr>";
                            }

                        } catch (Exception $e) {
                            echo "<tr><td colspan='6' class='text-center text-danger'>Erro ao carregar participantes: " . $e->getMessage() . "</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    require_once('rodape.php');
?>