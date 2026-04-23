<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Gestão Familiar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
        
        .input-group-text {
            background: transparent;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #764ba2;
        }

        .form-control, .form-select { 
            border-radius: 0 12px 12px 0; 
            padding: 12px; 
            background: #fff; 
            border: 1px solid #dee2e6;
            border-left: none;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .btn-register { 
            border-radius: 12px; 
            padding: 14px; 
            font-weight: 600; 
            background: linear-gradient(to right, #667eea, #764ba2); 
            border: none; 
            transition: all 0.3s ease;
        }

        .btn-register:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
            opacity: 0.9;
        }

        .login-link { color: #764ba2; font-weight: 600; text-decoration: none; }
        .login-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<?php if(isset($_GET['erro']) && $_GET['erro'] == 'email_existe'): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'E-mail em uso',
            text: 'Este e-mail já está cadastrado no sistema!',
            confirmButtonColor: '#764ba2'
        });
    </script>
<?php endif; ?>

<div class="container d-flex justify-content-center px-3">
    <div class="glass-card animate__animated animate__fadeInUp">
        <div class="text-center mb-4">
            <div class="mb-2">
                <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
            </div>
            <h2 class="fw-bold">Criar Conta</h2>
            <p class="text-muted">Inicie sua jornada familiar</p>
        </div>

        <form action="../actions/processa_cadastro.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="exemplo@email.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Papel na Família</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                    <select name="perfil" class="form-select">
                        <option value="membro">Filho(a) / Membro</option>
                        <option value="admin">Pai / Mãe (Admin)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-register w-100 text-white shadow-sm mb-3">
                Cadastrar e Entrar
            </button>

            <div class="text-center">
                <span class="text-muted small">Já tem uma conta?</span>
                    <a href="../includes/" class="text-decoration-none small"> Fazer Login</a>

            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




