<?php
session_start();
include('../config/conexao.php');

if (!isset($_SESSION['usuario_id'])) { header("Location: ../index.php"); exit; }

include('../includes/header.php');

$id_user = $_SESSION['usuario_id'];
$user_data = $mysqli->query("SELECT pontos FROM usuarios WHERE id = '$id_user'")->fetch_assoc();
$meus_pontos = $user_data['pontos'];

// Buscar recompensas disponíveis
$recompensas = $mysqli->query("SELECT * FROM recompensas WHERE estoque > 0 ORDER BY custo_pontos ASC");

// Função simples para dar ícones aleatórios se não houver no banco
function getIcon($nome) {
    $nome = strtolower($nome);
    if (strpos($nome, 'jantar') !== false || strpos($nome, 'comer') !== false) return '🍔';
    if (strpos($nome, 'cinema') !== false || strpos($nome, 'filme') !== false) return '🍿';
    if (strpos($nome, 'dormir') !== false || strpos($nome, 'hora') !== false) return '⏰';
    if (strpos($nome, 'pix') !== false || strpos($nome, 'dinheiro') !== false) return '💰';
    if (strpos($nome, 'folga') !== false || strpos($nome, 'tarefa') !== false) return '🏖️';
    return '🎁';
}
?>

<style>
    .reward-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .reward-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .reward-icon {
        font-size: 4rem;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 0 0 50% 50%;
        margin-bottom: 15px;
        display: inline-block;
        width: 100%;
        transition: background 0.3s;
    }
    .reward-card:hover .reward-icon {
        background: #e9ecef;
    }
    .points-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .balance-card {
        background: white;
        border-radius: 15px;
        padding: 15px 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
</style>

<div class="container py-5">
    <div class="row align-items-center mb-5 animate__animated animate__fadeIn">
        <div class="col-md-7">
            <h2 class="fw-bold mb-0">🎁 Loja de Recompensas</h2>
            <p class="text-muted">Troque seu esforço por prêmios incríveis!</p>
        </div>
        <div class="col-md-5 text-md-end">
            <div class="balance-card d-inline-block text-center">
                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Seu Saldo Atual</small>
                <span class="fs-3 fw-bold text-primary"><?php echo number_format($meus_pontos, 0, ',', '.'); ?></span>
                <span class="text-primary fw-bold">pts</span>
            </div>
        </div>
    </div>

    <div class="row">
        <?php while($item = $recompensas->fetch_assoc()): 
            $pode_resgatar = ($meus_pontos >= $item['custo_pontos']);
        ?>
            <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__fadeInUp">
                <div class="card reward-card h-100 shadow-sm border-0">
                    <div class="text-center">
                        <div class="reward-icon">
                            <?php echo getIcon($item['nome']); ?>
                        </div>
                    </div>
                    
                    <div class="card-body text-center pt-0">
                        <h5 class="card-title fw-bold mb-2"><?php echo $item['nome']; ?></h5>
                        <p class="card-text text-muted mb-4" style="font-size: 0.9rem; min-height: 40px;">
                            <?php echo $item['descricao']; ?>
                        </p>
                        
                        <div class="mb-4">
                            <span class="points-badge">
                                <?php echo $item['custo_pontos']; ?> pts
                            </span>
                        </div>

                        <div class="d-grid">
                            <?php if($pode_resgatar): ?>
                                <button onclick="confirmarResgate(<?php echo $item['id']; ?>, '<?php echo $item['nome']; ?>', <?php echo $item['custo_pontos']; ?>)" 
                                        class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                    Resgatar Prêmio
                                </button>
                            <?php else: ?>
                                <button class="btn btn-light btn-lg rounded-pill text-muted border" disabled>
                                    Faltam <?php echo ($item['custo_pontos'] - $meus_pontos); ?> pts
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <small class="text-muted">
                            <i class="bi bi-box-seam me-1"></i> <?php echo $item['estoque']; ?> disponíveis
                        </small>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="row mt-5 animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Meus Prêmios Resgatados</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Prêmio</th>
                                <th class="text-center">Custo</th>
                                <th class="text-center">Data</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $historico = $mysqli->query("SELECT * FROM historico_resgates WHERE usuario_id = '$id_user' ORDER BY data_resgate DESC");
                            if($historico->num_rows > 0):
                                while($h = $historico->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold"><?php echo $h['recompensa_nome']; ?></span>
                                    </td>
                                    <td class="text-center text-primary fw-bold">
                                        <?php echo $h['custo_pontos']; ?> pts
                                    </td>
                                    <td class="text-center text-muted small">
                                        <?php echo date('d/m/Y H:i', strtotime($h['data_resgate'])); ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if($h['status'] == 'pendente'): ?>
                                            <span class="badge bg-warning text-dark">Aguardando Entrega</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Entregue!</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted italic">
                                        Você ainda não resgatou nenhum prêmio. Continue pontuando! 🚀
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmarResgate(id, nome, custo) {
        Swal.fire({
            title: 'Confirmar Resgate?',
            html: `Você quer trocar <b>${custo} pontos</b> por:<br><h4>${nome}</h4>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#764ba2',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, resgatar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../actions/resgatar_premio.php?id=${id}&custo=${custo}`;
            }
        });
    }

    // Alertas de feedback
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'sucesso') {
        Swal.fire({
            title: 'Aêeee! 🎉',
            text: 'Prêmio resgatado! Fale com o Admin para receber.',
            icon: 'success',
            timer: 4000
        });
    } else if (urlParams.get('status') === 'erro') {
        Swal.fire({
            title: 'Erro no resgate',
            text: 'Não foi possível processar seu pedido.',
            icon: 'error'
        });
    }
</script>

<?php include('../includes/footer.php'); ?>