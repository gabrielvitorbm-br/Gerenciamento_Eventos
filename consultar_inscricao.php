<?php
    require_once('cabecalho.php');
?>

<div class="row mb-3">
    <div class="col d-flex justify-content-between align-items-center">
        <h2>📝 Inscrições Realizadas</h2>
        <a href="nova_inscricao.php" class="btn btn-success">
            + Registrar Nova Inscrição
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID Inscrição</th>
                        <th scope="col">Participante</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Palestra</th>
                        <th scope="col">Evento</th>
                        <th scope="col" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        require_once('conexao.php');

                        try {
                            $sql = "SELECT i.id, p.nome AS nome_participante, p.cpf, pa.titulo AS titulo_palestra, e.nome AS nome_evento
                                    FROM inscricao i
                                    INNER JOIN participante p ON i.id_participante = p.id
                                    INNER JOIN palestra pa ON i.id_palestra = pa.id
                                    INNER JOIN evento e ON pa.id_evento = e.id
                                    ORDER BY i.id DESC";
                            
                            $stmt = $pdo->query($sql);
                            $inscricoes = $stmt->fetchAll();

                            if (count($inscricoes) > 0) {
                                foreach ($inscricoes as $ins) {
                                    echo "<tr>";
                                    echo "<td>{$ins['id']}</td>";
                                    echo "<td><strong>{$ins['nome_participante']}</strong></td>";
                                    echo "<td>{$ins['cpf']}</td>";
                                    echo "<td>{$ins['titulo_palestra']}</td>";
                                    echo "<td><span class='badge bg-info text-dark'>{$ins['nome_evento']}</span></td>";
                                    
                                    echo "<td class='text-center'>
                                            <a href=\"alterar_inscricao.php?id={$ins['id']}\" class='btn btn-warning btn-sm'>Editar</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>Nenhuma inscrição registrada ainda. Vincule um participante a uma palestra clicando no botão verde!</td></tr>";
                            }

                        } catch (Exception $e) {
                            echo "<tr><td colspan='6' class='text-center text-danger'>Erro ao carregar inscrições: " . $e->getMessage() . "</td></tr>";
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