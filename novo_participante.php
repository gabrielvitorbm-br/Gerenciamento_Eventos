<?php
    require_once('cabecalho.php');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">👥 Cadastrar Novo Participante</h4>
            </div>
            <div class="card-body p-4">
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" placeholder="Digite o nome completo" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@email.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">CPF</label>
                            <input type="text" name="cpf" class="form-control" placeholder="000.000.000-00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição / Observações</label>
                        <textarea name="descricao" class="form-control" rows="4" placeholder="Ex: Estudante, Palestrante, área de interesse, observações médicas..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="consultar_participante.php" class="btn btn-outline-secondary">Voltar para a Listagem</a>
                        <button type="submit" class="btn btn-success px-4">Salvar Participante</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        require_once('conexao.php');

                        $nome = $_POST['nome'];
                        $email = $_POST['email'];
                        $cpf = $_POST['cpf'];
                        $descricao = $_POST['descricao']; 

                        try {
                            
                            $stmt = $pdo->prepare("INSERT INTO participante (nome, email, cpf, descricao) VALUES (?, ?, ?, ?)");
                            
                            if ($stmt->execute([$nome, $email, $cpf, $descricao])) {
                                echo "<div class='alert alert-success text-center mt-3' role='alert'>Participante cadastrado com sucesso!</div>";
                            } else {
                                echo "<div class='alert alert-danger text-center mt-3' role='alert'>Erro ao salvar o participante. Tente novamente.</div>";
                            }
                        } catch (Exception $e) {
                            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                                echo "<div class='alert alert-danger text-center mt-3' role='alert'>⚠️ Atenção: Este E-mail ou CPF já está cadastrado!</div>";
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