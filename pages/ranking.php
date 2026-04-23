<?php
session_start();
include('../config/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../includes/index.php");
    exit;
}

include('../includes/header.php');

$sql = "SELECT nome, pontos, perfil FROM usuarios ORDER BY pontos DESC";
$res = $mysqli->query($sql);

$usuarios = [];
while($row = $res->fetch_assoc()) {
    $usuarios[] = $row;
}
?>

<style>
    /* AJUSTE DO CONTAINER DO PÓDIO */
    .podium-container {
        display: flex;
        justify-content: center;
        align-items: flex-end; 
        gap: 10px;
        margin-bottom: 40px;
        padding-top: 80px; /* Espaço para a coroa não cortar */
    }

    .podium-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 20px; 
        text-align: center;
        padding: 20px 10px;
        width: 32%; /* Ajuste para mobile */
        max-width: 170px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(255,255,255,0.3);
        position: relative;
        transition: transform 0.3s ease;
    }

    /* POSICIONAMENTO DAS MEDALHAS/COROA */
    .podium-badge {
        position: absolute;
        top: -45px;
        left: 50%;
        transform: translateX(-50%);
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
    }

    .first { height: 210px; background: linear-gradient(180deg, #fffcf0 0%, #ffffff 100%); border-top: 6px solid #ffd700; z-index: 2; }
    .second { height: 170px; border-top: 6px solid #c0c0c0; }
    .third { height: 140px; border-top: 6px solid #cd7f32; }
    
    .avatar-circle {
        width: 55px;
        height: 55px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        font-size: 1.2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border: 3px solid white;
    }

    .ranking-table-card { border-radius: 25px; overflow: hidden; }
</style>

<div class="container py-4">
    <div class="text-center mb-5 animate__animated animate__fadeIn">
        <h2 class="fw-bold">Pódio da Semana 🏆</h2>
        <p class="text-muted">A disputa pelo topo nunca esteve tão acirrada!</p>
    </div>

    <div class="podium-container animate__animated animate__fadeInUp">
        
        <?php if(isset($usuarios[1])): ?>
        <div class="podium-card second">
            <div class="podium-badge fs-1">🥈</div>
            <div class="avatar-circle"><?php echo strtoupper(substr($usuarios[1]['nome'], 0, 1)); ?></div>
            <h6 class="fw-bold mb-1 text-truncate"><?php echo explode(' ', $usuarios[1]['nome'])[0]; ?></h6>
            <span class="badge bg-light text-primary border"><?php echo $usuarios[1]['pontos']; ?> pts</span>
        </div>
        <?php endif; ?>

        <?php if(isset($usuarios[0])): ?>
        <div class="podium-card first">
            <div class="podium-badge crown" style="font-size: 3rem; top: -65px;">👑</div>
            <div class="avatar-circle" style="width: 75px; height: 75px; font-size: 1.8rem;">
                <?php echo strtoupper(substr($usuarios[0]['nome'], 0, 1)); ?>
            </div>
            <h5 class="fw-bold mb-1 text-truncate"><?php echo explode(' ', $usuarios[0]['nome'])[0]; ?></h5>
            <div class="fw-bold text-primary mb-2"><?php echo $usuarios[0]['pontos']; ?> pts</div>
            <span class="badge bg-warning text-dark rounded-pill shadow-sm">Líder</span>
        </div>
        <?php endif; ?>

        <?php if(isset($usuarios[2])): ?>
        <div class="podium-card third">
            <div class="podium-badge fs-1">🥉</div>
            <div class="avatar-circle"><?php echo strtoupper(substr($usuarios[2]['nome'], 0, 1)); ?></div>
            <h6 class="fw-bold mb-1 text-truncate"><?php echo explode(' ', $usuarios[2]['nome'])[0]; ?></h6>
            <span class="badge bg-light text-primary border"><?php echo $usuarios[2]['pontos']; ?> pts</span>
        </div>
        <?php endif; ?>
    </div>

    <div class="row justify-content-center animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
        <div class="col-md-9">
            <div class="card ranking-table-card shadow-lg border-0">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Posição</th>
                                <th>Membro da Família</th>
                                <th class="text-center">Pontos</th>
                                <th class="text-end pe-4">Progresso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($usuarios as $index => $user): 
                                $pos = $index + 1;
                                $isMe = ($user['nome'] == $_SESSION['nome']);
                            ?>
                            <tr class="<?php echo $isMe ? 'table-light' : ''; ?>" style="<?php echo $isMe ? 'border-left: 5px solid #764ba2;' : ''; ?>">
                                <td class="ps-4 fw-bold">
                                    <span class="badge rounded-circle <?php echo $pos <= 3 ? 'bg-warning text-dark' : 'bg-secondary'; ?>" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                        <?php echo $pos; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3" style="width: 35px; height: 35px; font-size: 0.8rem; margin: 0; border: none;">
                                            <?php echo strtoupper(substr($user['nome'], 0, 1)); ?>
                                        </div>
                                        <span class="fw-bold <?php echo $isMe ? 'text-primary' : ''; ?>">
                                            <?php echo $user['nome']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-dark">
                                    <?php echo number_format($user['pontos'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php 
                                        $progresso = ($usuarios[0]['pontos'] > 0) ? ($user['pontos'] / $usuarios[0]['pontos']) * 100 : 0;
                                    ?>
                                    <div class="progress" style="height: 6px; width: 80px; display: inline-flex;">
                                        <div class="progress-bar bg-primary" style="width: <?php echo $progresso; ?>%"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="dashboard.php" class="btn btn-primary rounded-pill px-5 shadow">
                    <i class="bi bi-house-door me-2"></i> Voltar ao Início
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    window.onload = () => {
        const meuNome = "<?php echo $_SESSION['nome']; ?>";
        const lider = "<?php echo $usuarios[0]['nome'] ?? ''; ?>";
        if(meuNome === lider) {
            confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#764ba2', '#ffd700'] });
        }
    };
</script>

<?php include('../includes/footer.php'); ?>