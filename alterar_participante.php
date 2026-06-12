<?php
    ob_start(); 
    require_once('cabecalho.php');
    require_once('conexao.php');

    if (!isset($_GET['id'])) {
        echo "<div class='alert alert-danger text-center mt-4'>Erro: Nenhum participante selecionado para edição.</div>";
        require_once('rodape.php');
        exit();
    }

    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $acao = $_POST['acao']; 

        if ($acao == 'excluir') {
            try {
                $stmt_delete = $pdo->prepare("DELETE FROM participante WHERE id = ?");
                if ($stmt_delete->execute([$id])) {
                    header("Location: consultar_participante.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao excluir o participante.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
            }
        } 
        elseif ($acao == 'salvar') {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];
            $descricao = $_POST['descricao']; 

            try {
                $stmt_update = $pdo->prepare("UPDATE participante SET nome = ?, email = ?, cpf = ?, descricao = ? WHERE id = ?");
                if ($stmt_update->execute([$nome, $email, $cpf, $descricao, $id])) {
                    echo "<div class='alert alert-success text-center mt-3' role='alert'>Dados do participante atualizados com sucesso!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao atualizar os dados.</div>";
                }
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: Esse E-mail ou CPF já pertencem a outro participante!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
                }
            }
        }
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM participante WHERE id = ?");
        $stmt->execute([$id]);
        $participante = $stmt->fetch();

        if (!$participante) {
            echo "<div class='alert alert-warning text-center mt-4'>Participante não encontrado no banco de dados.</div>";
            require_once('rodape.php');
            exit();
        }
    } catch (Exception $e) {
        die("Erro ao buscar participante: " . $e->getMessage());
    }
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">✏️ Editar ou Excluir Participante</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($participante['nome']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($participante['email']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">CPF</label>
                            <input type="text" name="cpf" class="form-control" value="<?= htmlspecialchars($participante['cpf']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição / Observações</label>
                        <textarea name="descricao" class="form-control" rows="4" placeholder="Ex: Estudante, área de interesse..."><?= htmlspecialchars($participante['descricao'] ?? '') ?></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="consultar_participante.php" class="btn btn-outline-secondary">Voltar</a>
                        
                        <div>
                            <button type="submit" name="acao" value="excluir" class="btn btn-danger px-4 me-2" onclick="return confirm('ATENÇÃO: Tem certeza que deseja remover este participante? Isso também apagará as inscrições dele!');">
                                Excluir Participante
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