<?php
session_start();
include('../config/conexao.php');

// Segurança: só admin entra aqui
if ($_SESSION['perfil'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

include('../includes/header.php');

// Buscar usuários para o select
$res_usuarios = $mysqli->query("SELECT id, nome FROM usuarios");
// Buscar categorias para o select
$res_categorias = $mysqli->query("SELECT id, nome FROM categorias");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><i class="bi bi-plus-circle"></i> Nova Tarefa / Atividade</h4>
            </div>
            <div class="card-body">
                <form action="../actions/salvar_tarefa.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título da Atividade</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ex: Aula de Robótica ou Lavar Louça" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quem vai realizar?</label>
                        <select name="usuario_id" class="form-select" required>
                            <?php while($user = $res_usuarios->fetch_assoc()): ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo $user['nome']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoria</label>
                            <select name="categoria_id" class="form-select">
                                <?php while($cat = $res_categorias->fetch_assoc()): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nome']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pontos</label>
                            <input type="number" name="pontos" class="form-control" value="10">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data e Hora</label>
                        <input type="datetime-local" name="data_inicio" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Agendar Atividade</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>