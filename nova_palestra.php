<?php
    require_once('cabecalho.php');
    require_once('conexao.php');

    
    try {
        $stmt_eventos = $pdo->query("SELECT id, nome, data_evento, descricao FROM evento ORDER BY nome ASC");
        $eventos = $stmt_eventos->fetchAll();
    } catch (Exception $e) {
        $eventos = [];
    }
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">🎤 Cadastrar Nova Palestra</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título da Palestra</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ex: Introdução ao PHP e PDO" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome do Palestrante</label>
                        <input type="text" name="palestrante" class="form-control" placeholder="Ex: Dr. Gabriel Silva" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Vincular ao Evento</label>
                            <select name="id_evento" id="id_evento" class="form-select" onchange="puxarDadosEvento()" required>
                                <option value="">-- Escolha o Evento --</option>
                                <?php foreach ($eventos as $e): ?>
                                    <option value="<?= $e['id'] ?>" 
                                            data-data="<?= $e['data_evento'] ?>" 
                                            data-descricao="<?= htmlspecialchars($e['descricao'] ?? '') ?>">
                                        <?= htmlspecialchars($e['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Data da Palestra</label>
                            <input type="date" name="data_palestra" id="data_palestra" class="form-control" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Horário de Início</label>
                            <input type="time" name="horario" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="container_desc_evento">
                        <label class="form-label fw-bold text-muted">Sobre o Evento Selecionado:</label>
                        <div class="p-3 bg-light rounded border text-secondary" id="texto_desc_evento" style="white-space: pre-wrap;"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="consultar_palestra.php" class="btn btn-outline-secondary">Voltar para a Listagem</a>
                        <button type="submit" class="btn btn-success px-4">Salvar Palestra</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $titulo = $_POST['titulo'];
                        $palestrante = $_POST['palestrante'];
                        $data_palestra = $_POST['data_palestra'];
                        $horario = $_POST['horario'];
                        $id_evento = $_POST['id_evento'];

                        try {
                            $stmt = $pdo->prepare("INSERT INTO palestra (titulo, data_palestra, horario, palestrante, id_evento) VALUES (?, ?, ?, ?, ?)");
                            
                            if ($stmt->execute([$titulo, $data_palestra, $horario, $palestrante, $id_evento])) {
                                echo "<div class='alert alert-success text-center mt-3' role='alert'>Palestra cadastrada com sucesso!</div>";
                            } else {
                                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao salvar a palestra. Tente novamente.</div>";
                            }
                        } catch (Exception $e) {
                            echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro: " . $e->getMessage() . "</div>";
                        }
                    }
                ?>

            </div>
        </div>
    </div>
</div>

<script>
function puxarDadosEvento() {
    var select = document.getElementById('id_evento');
    var inputData = document.getElementById('data_palestra');
    var containerDesc = document.getElementById('container_desc_evento');
    var textoDesc = document.getElementById('texto_desc_evento');
    
    var opcaoSelecionada = select.options[select.selectedIndex];
    
    // Puxa as informações guardadas nos atributos do option
    var dataEvento = opcaoSelecionada.getAttribute('data-data');
    var descEvento = opcaoSelecionada.getAttribute('data-descricao');
    
    if (dataEvento) {
        inputData.value = dataEvento;
        
        
        if (descEvento && descEvento.trim() !== "") {
            textoDesc.textContent = descEvento;
            containerDesc.classList.remove('d-none'); 
        } else {
            textoDesc.innerHTML = "<em>Este evento não possui uma descrição cadastrada.</em>";
            containerDesc.classList.remove('d-none');
        }
    } else {
        inputData.value = '';
        containerDesc.classList.add('d-none'); 
        textoDesc.textContent = '';
    }
}
</script>

<?php
    require_once('rodape.php');
?>