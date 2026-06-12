<?php
    require_once('cabecalho.php');
    require_once('conexao.php');

    
    try {
        $participantes = $pdo->query("SELECT id, nome, cpf FROM participante ORDER BY nome ASC")->fetchAll();
        
        $sql_palestras = "SELECT p.id, p.titulo, e.nome AS nome_evento 
                          FROM palestra p 
                          INNER JOIN evento e ON p.id_evento = e.id 
                          ORDER BY p.titulo ASC";
        $palestras = $pdo->query($sql_palestras)->fetchAll();
    } catch (Exception $e) {
        $participantes = [];
        $palestras = [];
    }
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">📝 Registrar Nova Inscrição</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">1. Escolha o Participante</label>
                        <select name="id_participante" class="form-select" required>
                            <option value="">-- Selecione o Participante --</option>
                            <?php foreach ($participantes as $part): ?>
                                <option value="<?= $part['id'] ?>">
                                    <?= htmlspecialchars($part['nome']) ?> (CPF: <?= $part['cpf'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">2. Escolha a Palestra</label>
                        <select name="id_palestra" class="form-select" required>
                            <option value="">-- Selecione a Palestra --</option>
                            <?php foreach ($palestras as $pal): ?>
                                <option value="<?= $pal['id'] ?>">
                                    <?= htmlspecialchars($pal['titulo']) ?> [Evento: <?= htmlspecialchars($pal['nome_evento']) ?>]
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="consultar_inscricao.php" class="btn btn-outline-secondary">Voltar para a Listagem</a>
                        <button type="submit" class="btn btn-success px-4">Confirmar Inscrição</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $id_participante = $_POST['id_participante'];
                        $id_palestra = $_POST['id_palestra'];

                        try {
                            $stmt = $pdo->prepare("INSERT INTO inscricao (id_participante, id_palestra) VALUES (?, ?)");
                            
                            if ($stmt->execute([$id_participante, $id_palestra])) {
                                echo "<div class='alert alert-success text-center mt-3' role='alert'>Inscrição realizada com sucesso!</div>";
                            } else {
                                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao registrar inscrição.</div>";
                            }
                        } catch (Exception $e) {
                            
                            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                                echo "<div class='alert alert-danger text-center mt-3' role='alert'>⚠️ Atenção: Este participante já está inscrito nesta palestra!</div>";
                            } else {
                                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
                            }
                        }
                    }
                ?>

            </div>
        </div>
    </div>
</div>

<?php
    require_once('rodape.php');
?>