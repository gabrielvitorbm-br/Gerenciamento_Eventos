<?php
    ob_start(); 
    require_once('cabecalho.php');
    require_once('conexao.php');

    if (!isset($_GET['id'])) {
        echo "<div class='alert alert-danger text-center mt-4'>Erro: Nenhuma palestra selecionada para edição.</div>";
        require_once('rodape.php');
        exit();
    }

    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $acao = $_POST['acao']; 

        if ($acao == 'excluir') {
            try {
                $stmt_delete = $pdo->prepare("DELETE FROM palestra WHERE id = ?");
                if ($stmt_delete->execute([$id])) {
                    header("Location: consultar_palestra.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao excluir a palestra.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
            }
        } 
        elseif ($acao == 'salvar') {
            $titulo = $_POST['titulo'];
            $palestrante = $_POST['palestrante'];
            $data_palestra = $_POST['data_palestra'];
            $horario = $_POST['horario'];
            $id_evento = $_POST['id_evento'];

            try {
                $stmt_update = $pdo->prepare("UPDATE palestra SET titulo = ?, data_palestra = ?, horario = ?, palestrante = ?, id_evento = ? WHERE id = ?");
                if ($stmt_update->execute([$titulo, $data_palestra, $horario, $palestrante, $id_evento, $id])) {
                    echo "<div class='alert alert-success text-center mt-3' role='alert'>Palestra updated com sucesso!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao atualizar os dados.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
            }
        }
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM palestra WHERE id = ?");
        $stmt->execute([$id]);
        $palestra = $stmt->fetch();

        if (!$palestra) {
            echo "<div class='alert alert-warning text-center mt-4'>Palestra não encontrada.</div>";
            require_once('rodape.php');
            exit();
        }

        $stmt_eventos = $pdo->query("SELECT id, nome, data_evento, descricao FROM evento ORDER BY nome ASC");
        $eventos = $stmt_eventos->fetchAll();
    } catch (Exception $e) {
        die("Erro ao carregar dados: " . $e->getMessage());
    }
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">✏️ Editar ou Excluir Palestra</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título da Palestra</label>
                        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($palestra['titulo']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome do Palestrante</label>
                        <input type="text" name="palestrante" class="form-control" value="<?= htmlspecialchars($palestra['palestrante']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Vincular ao Evento</label>
                            <select name="id_evento" id="id_evento" class="form-select" onchange="puxarDadosEvento()" required>
                                <?php foreach ($eventos as $e): ?>
                                    <option value="<?= $e['id'] ?>" 
                                            data-data="<?= $e['data_evento'] ?>" 
                                            data-descricao="<?= htmlspecialchars($e['descricao'] ?? '') ?>"
                                            <?= $e['id'] == $palestra['id_evento'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($e['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Data da Palestra</label>
                            <input type="date" name="data_palestra" id="data_palestra" class="form-control" value="<?= $palestra['data_palestra'] ?>" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Horário</label>
                            <input type="time" name="horario" class="form-control" value="<?= $palestra['horario'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição do Evento Vinculado</label>
                        <textarea id="descricao_evento" class="form-control text-muted" rows="3" readonly placeholder="A descrição do evento aparecerá aqui..."></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="consultar_palestra.php" class="btn btn-outline-secondary">Voltar / Cancelar</a>
                        
                        <div>
                            <button type="submit" name="acao" value="excluir" class="btn btn-danger px-4 me-2" onclick="return confirm('Tem certeza que deseja remover esta palestra definitivamente?');">
                                Excluir Palestra
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

<script>
function puxarDadosEvento() {
    var select = document.getElementById('id_evento');
    var inputData = document.getElementById('data_palestra');
    var txtDescricao = document.getElementById('descricao_evento');
    
    var opcaoSelecionada = select.options[select.selectedIndex];
    
    var dataEvento = opcaoSelecionada.getAttribute('data-data');
    var descEvento = opcaoSelecionada.getAttribute('data-descricao');
    
    if (dataEvento) {
        inputData.value = dataEvento;
        txtDescricao.value = descEvento ? descEvento : "Este evento não possui uma descrição cadastrada.";
    } else {
        inputData.value = '';
        txtDescricao.value = '';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    puxarDadosEvento();
});
</script>

<?php
    require_once('rodape.php');
    ob_end_flush();
?>