<?php
    require_once('cabecalho.php');
?>

<div class="row mb-4">
    <div class="col">
        <h2 class="display-6">Seja bem-vindo, <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></strong>!</h2>
        <p class="text-muted">Painel de controle do Sistema de Gerenciamento de Eventos.</p>
        <hr>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-md-4">
        <div class="card h-100 border-start border-primary border-4 shadow-sm py-2">
            <div class="card-body d-flex flex-column justify-content-between text-center">
                <div>
                    <div class="display-4 text-primary mb-3">📅</div>
                    <h5 class="card-title text-primary fw-bold mb-2">Eventos</h5>
                    <p class="card-text text-muted small px-2">Gerencie os eventos principais do sistema, crie novos ou edite os existentes.</p>
                </div>
                <div class="px-3 mt-3">
                    <a href="consultar_evento.php" class="btn btn-outline-primary btn-sm w-100 py-2">Acessar Eventos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-start border-success border-4 shadow-sm py-2">
            <div class="card-body d-flex flex-column justify-content-between text-center">
                <div>
                    <div class="display-4 text-success mb-3">👥</div>
                    <h5 class="card-title text-success fw-bold mb-2">Participantes</h5>
                    <p class="card-text text-muted small px-2">Cadastre novos participantes e visualize a lista de pessoas da comunidade.</p>
                </div>
                <div class="px-3 mt-3">
                    <a href="consultar_participante.php" class="btn btn-outline-success btn-sm w-100 py-2">Acessar Participantes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-start border-warning border-4 shadow-sm py-2">
            <div class="card-body d-flex flex-column justify-content-between text-center">
                <div>
                    <div class="display-4 text-warning mb-3">🎤</div>
                    <h5 class="card-title text-warning fw-bold mb-2">Palestras</h5>
                    <p class="card-text text-muted small px-2">Vincule palestras e cronogramas diretamente aos eventos criados.</p>
                </div>
                <div class="px-3 mt-3">
                    <a href="consultar_palestra.php" class="btn btn-outline-warning btn-sm w-100 py-2">Acessar Palestras</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-4">
        <div class="card border-start border-info border-4 shadow-sm py-3">
            <div class="card-body d-flex flex-column justify-content-between text-center">
                <div>
                    <div class="display-4 text-info mb-3">📝</div>
                    <h5 class="card-title text-info fw-bold mb-2">Inscrições em Palestras</h5>
                    <p class="card-text text-muted small px-4">Conecte os participantes cadastrados às palestras disponíveis do cronograma.</p>
                </div>
                <div class="px-5 mt-3">
                    <a href="consultar_inscricao.php" class="btn btn-info text-white btn-sm w-100 py-2 fw-bold">Realizar Nova Inscrição</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once('rodape.php');
?>