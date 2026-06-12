<?php
    ob_start(); 
    require_once('cabecalho.php');
    require_once('conexao.php');

    if (!isset($_GET['id'])) {
        echo "<div class='alert alert-danger text-center mt-4'>Erro: Nenhuma inscrição selecionada.</div>";
        require_once('rodape.php');
        exit();
    }

    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $acao = $_POST['acao'];

        if ($acao == 'excluir') {
            try {
                $stmt_delete = $pdo->prepare("DELETE FROM inscricao WHERE id = ?");
                if ($stmt_delete->execute([$id])) {
                    header("Location: consultar_inscricao.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao cancelar inscrição.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
            }
        } 
        elseif ($acao == 'salvar') {
            $id_participante = $_POST['id_participante'];
            $id_palestra = $_POST['id_palestra'];

            try {
                $stmt_update = $pdo->prepare("UPDATE inscricao SET id_participante = ?, id_palestra = ? WHERE id = ?");
                if ($stmt_update->execute([$id_participante, $id_palestra, $id])) {
                    echo "<div class='alert alert-success text-center mt-3' role='alert'>Inscrição alterada com sucesso!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao atualizar os dados.</div>";
                }
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: Esse participante já está inscrito nessa palestra!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
                }
            }
        }
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM inscricao WHERE id = ?");
        $stmt->execute([$id]);
        $inscricao = $stmt->fetch();

        if (!$inscricao) {
            echo "<div class='alert alert-warning text-center mt-4'>Inscrição não encontrada.</div>";
            require_once('rodape.php');
            exit();
        }

        $participantes = $pdo->query("SELECT id, nome, cpf FROM participante ORDER BY nome ASC")->fetchAll();
        
        $sql_palestras = "SELECT p.id, p.titulo, e.nome AS nome_evento 
                          FROM palestra p 
                          INNER JOIN evento e ON p.id_evento = e.id 
                          ORDER BY p.titulo ASC";
        $palestras = $pdo->query($sql_palestras)->fetchAll();
    } catch (Exception $e) {
        die("Erro ao carregar dados: " . $e->getMessage());
    }
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">✏️ Editar ou Cancelar Inscrição</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Participante</label>
                        <select name="id_participante" class="form-select" required>
                            <?php foreach ($participantes as $part): ?>
                                <option value="<?= $part['id'] ?>" <?= $part['id'] == $inscricao['id_participante'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($part['nome']) ?> (CPF: <?= $part['cpf'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Palestra Vinculada</label>
                        <select name="id_palestra" class="form-select" required>
                            <?php foreach ($palestras as $pal): ?>
                                <option value="<?= $pal['id'] ?>" <?= $pal['id'] == $inscricao['id_palestra'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($pal['titulo']) ?> [Evento: <?= htmlspecialchars($pal['nome_evento']) ?>]
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="consultar_inscricao.php" class="btn btn-outline-secondary">Voltar / Cancelar</a>
                        
                        <div>
                            <button type="submit" name="acao" value="excluir" class="btn btn-danger px-4 me-2" onclick="return confirm('Tem certeza que deseja remover esta inscrição permanentemente?');">
                                Cancelar Inscrição
                            </button>
                            <button type="submit" name="acao" value="salvar" class="btn btn-warning px-4 fw-bold">
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php
    require_once('rodape.php');
    ob_end_flush();
?>