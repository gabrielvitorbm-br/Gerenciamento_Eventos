<?php
    ob_start(); 
    require_once('cabecalho.php');
    require_once('conexao.php');

    if (!isset($_GET['id'])) {
        echo "<div class='alert alert-danger text-center mt-4'>Erro: Nenhum evento selecionado para edição.</div>";
        require_once('rodape.php');
        exit();
    }

    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $acao = $_POST['acao']; 

        if ($acao == 'excluir') {
            try {
                $stmt_delete = $pdo->prepare("DELETE FROM evento WHERE id = ?");
                if ($stmt_delete->execute([$id])) {
                    header("Location: consultar_evento.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao excluir the evento.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
            }
        } 
        elseif ($acao == 'salvar') {
            $nome = $_POST['nome'];
            $data_evento = $_POST['data_evento'];
            $local_evento = $_POST['local_evento'];
            $descricao = $_POST['descricao'];

            try {
                $stmt_update = $pdo->prepare("UPDATE evento SET nome = ?, data_evento = ?, local_evento = ?, descricao = ? WHERE id = ?");
                if ($stmt_update->execute([$nome, $data_evento, $local_evento, $descricao, $id])) {
                    echo "<div class='alert alert-success text-center mt-3' role='alert'>Evento atualizado com sucesso!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao atualizar o evento.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
            }
        }
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM evento WHERE id = ?");
        $stmt->execute([$id]);
        $evento = $stmt->fetch();

        if (!$evento) {
            echo "<div class='alert alert-warning text-center mt-4'>Evento não encontrado no banco de dados.</div>";
            require_once('rodape.php');
            exit();
        }
    } catch (Exception $e) {
        die("Erro ao buscar evento: " . $e->getMessage());
    }
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">✏️ Editar ou Excluir Evento</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome do Evento</label>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($evento['nome']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data do Evento</label>
                            <input type="date" name="data_evento" class="form-control" value="<?= $evento['data_evento'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Local / Plataforma</label>
                            <input type="text" name="local_evento" class="form-control" value="<?= htmlspecialchars($evento['local_evento']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição do Evento</label>
                        <textarea name="descricao" class="form-control" rows="4"><?= htmlspecialchars($evento['descricao']) ?></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="consultar_evento.php" class="btn btn-outline-secondary">Voltar</a>
                        
                        <div>
                            <button type="submit" name="acao" value="excluir" class="btn btn-danger px-4 me-2" onclick="return confirm('ATENÇÃO: Tem certeza que deseja excluir este evento definitivamente?');">
                                Excluir Evento
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