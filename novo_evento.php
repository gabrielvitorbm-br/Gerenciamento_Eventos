<?php
    require_once('cabecalho.php');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">📅 Cadastrar Novo Evento</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome do Evento</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: Feira de Tecnologia 2026" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data do Evento</label>
                            <input type="date" name="data_evento" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Local / Plataforma</label>
                            <input type="text" name="local_evento" class="form-control" placeholder="Ex: Auditório Principal" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição do Evento</label>
                        <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva os detalhes do evento..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="consultar_evento.php" class="btn btn-outline-secondary">Voltar para a Listagem</a>
                        <button type="submit" class="btn btn-success px-4">Salvar Evento</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        require_once('conexao.php');

                        $nome = $_POST['nome'];
                        $data_evento = $_POST['data_evento'];
                        $local_evento = $_POST['local_evento'];
                        $descricao = $_POST['descricao'];

                        try {
                            
                            $stmt = $pdo->prepare("INSERT INTO evento (nome, data_evento, local_evento, descricao) VALUES (?, ?, ?, ?)");
                            
                            if ($stmt->execute([$nome, $data_evento, $local_evento, $descricao])) {
                                echo "<div class='alert alert-success text-center mt-3' role='alert'>Evento cadastrado com sucesso!</div>";
                            } else {
                                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao salvar o evento. Tente novamente.</div>";
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

<?php
    require_once('rodape.php');
?>