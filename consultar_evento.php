<?php
    require_once('cabecalho.php');
?>

<div class="row mb-3">
    <div class="col d-flex justify-content-between align-items-center">
        <h2>📅 Lista de Eventos</h2>
        <a href="novo_evento.php" class="btn btn-success">
            + Cadastrar Novo Evento
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
                        <th scope="col">Nome do Evento</th>
                        <th scope="col">Data</th>
                        <th scope="col">Local</th>
                        <th scope="col">Descrição</th> <th scope="col" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        require_once('conexao.php');

                        try {
                            // Busca os eventos no banco de dados, ordenando pela data mais próxima
                            $stmt = $pdo->query("SELECT * FROM evento ORDER BY data_evento ASC");
                            $eventos = $stmt->fetchAll();

                            if (count($eventos) > 0) {
                                foreach ($eventos as $evento) {
                                   
                                    $data_formatada = date('d/m/Y', strtotime($evento['data_evento']));
                                    
                                    
                                    $descricao = !empty($evento['descricao']) ? htmlspecialchars($evento['descricao']) : '<em class="text-muted">Sem descrição</em>';

                                    echo "<tr>";
                                    echo "<td>{$evento['id']}</td>";
                                    echo "<td><strong>{$evento['nome']}</strong></td>";
                                    echo "<td>{$data_formatada}</td>";
                                    echo "<td>{$evento['local_evento']}</td>";
                                    echo "<td>{$descricao}</td>"; 
                                    echo "<td class='text-center'>
                                            <a href='alterar_evento.php?id={$evento['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>Nenhum evento cadastrado ainda. Clique no botão verde para adicionar o primeiro!</td></tr>";
                            }

                        } catch (Exception $e) {
                            echo "<tr><td colspan='6' class='text-center text-danger'>Erro ao carregar eventos: " . $e->getMessage() . "</td></tr>";
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