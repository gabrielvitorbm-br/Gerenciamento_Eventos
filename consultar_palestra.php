<?php
    require_once('cabecalho.php');
?>

<div class="row mb-3">
    <div class="col d-flex justify-content-between align-items-center">
        <h2>🎤 Lista de Palestras</h2>
        <a href="nova_palestra.php" class="btn btn-success">
            + Cadastrar Nova Palestra
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
                        <th scope="col">Título da Palestra</th>
                        <th scope="col">Palestrante</th>
                        <th scope="col">Data</th>
                        <th scope="col">Horário</th>
                        <th scope="col">Evento Vinculado</th>
                        <th scope="col" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        require_once('conexao.php');

                        try {
                            
                            $sql = "SELECT p.*, e.nome AS nome_evento 
                                    FROM palestra p 
                                    INNER JOIN evento e ON p.id_evento = e.id 
                                    ORDER BY p.data_palestra ASC, p.horario ASC";
                            
                            $stmt = $pdo->query($sql);
                            $palestras = $stmt->fetchAll();

                            if (count($palestras) > 0) {
                                foreach ($palestras as $p) {
                                    
                                    $data_formatada = date('d/m/Y', strtotime($p['data_palestra']));
                                    
                                    $hora_formatada = date('H:i', strtotime($p['horario']));

                                    echo "<tr>";
                                    echo "<td>{$p['id']}</td>";
                                    echo "<td><strong>{$p['titulo']}</strong></td>";
                                    echo "<td>{$p['palestrante']}</td>";
                                    echo "<td>{$data_formatada}</td>";
                                    echo "<td>{$hora_formatada}</td>";
                                    echo "<td><span class='badge bg-primary'>{$p['nome_evento']}</span></td>";
                                    echo "<td class='text-center'>
                                            <a href='alterar_palestra.php?id={$p['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center text-muted py-4'>Nenhuma palestra cadastrada ainda. Clique no botão verde para adicionar a primeira!</td></tr>";
                            }

                        } catch (Exception $e) {
                            echo "<tr><td colspan='7' class='text-center text-danger'>Erro ao carregar palestras: " . $e->getMessage() . "</td></tr>";
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