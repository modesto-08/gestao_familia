<?php
session_start();
include('../config/conexao.php');

// Verificação de segurança
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../includes/index.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$perfil = $_SESSION['perfil'];
$hoje = date('Y-m-d');

// 1. Dados do Usuário e Notificações
$query_user = $mysqli->query("SELECT pontos FROM usuarios WHERE id = '$id_usuario'");
$dados_user = $query_user->fetch_assoc();
$meus_pontos = $dados_user['pontos'] ?? 0;

$sql_notif = "SELECT id FROM tarefas WHERE usuario_id = '$id_usuario' AND (data_vencimento = '$hoje' OR data_vencimento IS NULL) AND status = 'pendente'";
$res_notif = $mysqli->query($sql_notif);
$total_hoje = ($res_notif) ? $res_notif->num_rows : 0;

// 2. Query de Tarefas
if ($perfil == 'admin') {
    $sql_tarefas = "SELECT t.*, u.nome as quem_faz FROM tarefas t 
                    JOIN usuarios u ON t.usuario_id = u.id 
                    WHERE t.status != 'validada' ORDER BY t.data_inicio ASC";
} else {
    $sql_tarefas = "SELECT t.*, 'Você' as quem_faz FROM tarefas t 
                    WHERE t.usuario_id = '$id_usuario' AND t.status != 'validada' 
                    ORDER BY t.data_inicio ASC";
}
$res_tarefas = $mysqli->query($sql_tarefas);

include('../includes/header.php'); 
?>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        transition: transform 0.3s ease;
    }
    .glass-card:hover { transform: translateY(-5px); }
    .btn-rounded { border-radius: 50px; }
    .search-bar {
        border-radius: 50px;
        padding-left: 45px;
        background: rgba(255, 255, 255, 0.9);
    }
    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
</style>

<div class="container py-4">
    <div class="row align-items-center mb-5 animate__animated animate__fadeIn">
        <div class="col-md-7">
            <h1 class="fw-bold">Olá, <span class="text-primary"><?php echo $_SESSION['nome']; ?></span>! 👋</h1>
            <p class="text-muted">Você tem <span class="badge bg-soft-primary text-primary"><?php echo $total_hoje; ?> tarefas</span> agendadas para hoje.</p>
        </div>
        <div class="col-md-5">
            <div class="position-relative">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="inputBusca" class="form-control form-control-lg shadow-sm search-bar" placeholder="Buscar tarefa pelo título..." onkeyup="filtrarTarefas()">
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card glass-card shadow-sm h-100 p-3 border-start border-success border-5">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-success small">Meus Pontos</h6>
                    <h2 class="fw-bold mb-0" id="contador-pontos">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card glass-card shadow-sm h-100 p-3 border-start border-primary border-5">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-primary small">Nível Família</h6>
                    <h2 class="fw-bold mb-0"><?php echo ($meus_pontos > 100) ? '🏆 Pro' : '🌱 Iniciante'; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card glass-card shadow-sm h-100 bg-gradient-primary text-dark">
                <div class="card-body d-flex align-items-center">
                    <div class="display-6 me-3 text-warning"><i class="bi bi-lightning-charge-fill"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Dica do Dia:</h6>
                        <small>Complete tarefas antes do meio-dia para manter a casa organizada!</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($perfil == 'admin'): ?>
        <div class="alert glass-card d-flex align-items-center justify-content-between shadow-sm p-3 mb-5 border-0">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white rounded-circle p-2 me-3"><i class="bi bi-shield-check"></i></div>
                <strong>Painel de Controle do Admin</strong>
            </div>
            <a href="cadastrar_tarefa.php" class="btn btn-primary btn-rounded px-4 shadow">Criar Tarefa</a>
        </div>
    <?php endif; ?>

    <h4 class="fw-bold mb-4"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Sua Jornada</h4>
    <div class="row" id="listaTarefas">
        <?php if ($res_tarefas->num_rows > 0): ?>
            <?php while($tarefa = $res_tarefas->fetch_assoc()): ?>
                <div class="col-md-4 mb-4 card-container">
                    <div class="card glass-card h-100 border-0 shadow-sm card-tarefa">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-warning text-dark rounded-pill px-3">🪙 <?php echo $tarefa['pontos']; ?> pts</span>
                                <small class="text-muted"><?php echo date('d M', strtotime($tarefa['data_inicio'])); ?></small>
                            </div>
                            <h5 class="card-title fw-bold mb-3"><?php echo $tarefa['titulo']; ?></h5>
                            <div class="d-flex align-items-center text-muted small mb-4">
                                <i class="bi bi-person-circle me-2"></i> <?php echo $tarefa['quem_faz']; ?>
                            </div>
                            
                            <?php if ($tarefa['status'] == 'pendente'): ?>
                                <button onclick="concluirTarefa(<?php echo $tarefa['id']; ?>)" class="btn btn-success w-100 btn-rounded fw-bold py-2 shadow-sm">
                                    <i class="bi bi-check-all me-1"></i> Concluir
                                </button>
                            <?php elseif ($perfil == 'admin' && $tarefa['status'] == 'concluida'): ?>
                                <a href="../actions/validar_tarefa.php?id=<?php echo $tarefa['id']; ?>&user=<?php echo $tarefa['usuario_id']; ?>&pts=<?php echo $tarefa['pontos']; ?>" class="btn btn-primary w-100 btn-rounded fw-bold shadow-sm">
                                    <i class="bi bi-star-fill me-1"></i> Validar Pontos
                                </a>
                            <?php else: ?>
                                <button class="btn btn-light w-100 btn-rounded disabled text-uppercase small fw-bold">Aguardando...</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-smile text-muted display-1"></i>
                <p class="text-muted fs-5">Tudo limpo! Nenhuma tarefa por enquanto.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

<script>
    // 1. Animação de Contagem de Pontos
    function animateValue(id, start, end, duration) {
        let obj = document.getElementById(id);
        let range = end - start;
        let minTimer = 50;
        let stepTime = Math.abs(Math.floor(duration / range));
        stepTime = Math.max(stepTime, minTimer);
        let startTime = new Date().getTime();
        let endTime = startTime + duration;
        let timer;
        function run() {
            let now = new Date().getTime();
            let remaining = Math.max((endTime - now) / duration, 0);
            let value = Math.round(end - (remaining * range));
            obj.innerHTML = value + " <small style='font-size: 15px'>pts</small>";
            if (value == end) clearInterval(timer);
        }
        timer = setInterval(run, stepTime);
        run();
    }
    animateValue("contador-pontos", 0, <?php echo $meus_pontos; ?>, 1500);

    // 2. Filtro de Busca Dinâmico
    function filtrarTarefas() {
        let input = document.getElementById('inputBusca').value.toLowerCase();
        let cards = document.querySelectorAll('.card-container');
        cards.forEach(card => {
            let titulo = card.querySelector('.card-title').innerText.toLowerCase();
            card.style.display = titulo.includes(input) ? "" : "none";
        });
    }

    // 3. Efeito de Confete e Redirecionamento
    function concluirTarefa(id) {
        confetti({
            particleCount: 150,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#2ecc71', '#3498db', '#9b59b6']
        });
        
        setTimeout(() => {
            window.location.href = `../actions/concluir_tarefa.php?id=${id}`;
        }, 1000);
    }

    // 4. Toast de Notificação de boas-vindas
    const total = <?php echo $total_hoje; ?>;
    if(total > 0) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: `Você tem ${total} tarefas para hoje!`,
            showConfirmButton: false,
            timer: 3000
        });
    }


</script>

<div class="card border-0 shadow-sm rounded-4 mt-4 animate__animated animate__fadeInUp">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-bell-fill text-warning me-2"></i> Atividades Recentes
        </h5>
        <span class="badge rounded-pill bg-light text-dark border">Últimas 5</span>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            <?php
            // Aqui buscamos as últimas 5 notificações ou resgates
            // Vou unir o histórico de resgates e tarefas concluídas para gerar o feed
            $query_feed = "
                (SELECT 'resgate' as tipo, recompensa_nome as titulo, data_resgate as data, u.nome as user 
                 FROM historico_resgates hr 
                 JOIN usuarios u ON hr.usuario_id = u.id)
                UNION
                (SELECT 'tarefa' as tipo, titulo as titulo, data_vencimento as data, u.nome as user 
                 FROM tarefas t 
                 JOIN usuarios u ON t.usuario_id = u.id WHERE t.status = 'concluida')
                ORDER BY data DESC LIMIT 5";
            
            $res_feed = $mysqli->query($query_feed);

            if($res_feed && $res_feed->num_rows > 0):
                while($item = $res_feed->fetch_assoc()):
                    $icon = ($item['tipo'] == 'resgate') ? 'bi-gift-fill text-danger' : 'bi-check-circle-fill text-success';
                    $bg_color = ($item['tipo'] == 'resgate') ? 'rgba(220, 53, 69, 0.05)' : 'rgba(40, 167, 69, 0.05)';
            ?>
                <div class="list-group-item border-0 p-3 mb-1" style="background: <?php echo $bg_color; ?>; border-radius: 15px; margin: 10px;">
                    <div class="d-flex align-items-center">
                        <div class="icon-box me-3 fs-4">
                            <i class="bi <?php echo $icon; ?>"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">
                                <?php echo $item['user']; ?> 
                                <span class="fw-normal text-muted">
                                    <?php echo ($item['tipo'] == 'resgate') ? 'resgatou' : 'concluiu'; ?>
                                </span> 
                                <?php echo $item['titulo']; ?>
                            </h6>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-clock me-1"></i> <?php echo date('d/m/Y H:i', strtotime($item['data'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
                
                


                
            <?php 
                endwhile;
            else:
            ?>
                <div class="p-4 text-center">
                    <img src="../assets/img/empty.svg" alt="Sem atividades" style="width: 50px; opacity: 0.5;">
                    <p class="text-muted small mt-2">Nenhuma atividade recente por aqui.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-footer bg-white border-0 text-center pb-3">
        <a href="#" class="text-decoration-none small fw-bold text-primary">Ver histórico completo</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>