<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Família Tech</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #ddd;
            background: rgba(255, 255, 255, 0.8);
            transition: 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 15px rgba(35, 166, 213, 0.3);
            border-color: #23a6d5;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            color: white;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(118, 75, 162, 0.4);
            color: white;
        }

        .brand-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>




<div class="login-card animate__animated animate__zoomIn">
    <div class="text-center">
        <div class="brand-icon">
            <i class="bi bi-house-lock"></i>
        </div>
        <h3 class="fw-bold">Bem-vindo</h3>
        <p class="text-muted small">Acesse o painel da sua família</p>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'cadastrado'): ?>
        <div class="alert alert-success py-2 small animate__animated animate__headShake">
            <i class="bi bi-check-circle pe-2"></i> Cadastro feito com sucesso!
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['erro']) && $_GET['erro'] == 'login_invalido'): ?>
        <div class="alert alert-danger py-2 small">
            <i class="bi bi-exclamation-triangle pe-2"></i> E-mail ou senha incorretos.
        </div>
    <?php endif; ?>


<div class="dropdown">
    <button class="btn position-relative" type="button" data-bs-toggle="dropdown">
        <!-- <i class="bi bi-bell fs-4 text-white"></i> -->
        <?php if($notificacoes['total'] > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $notificacoes['total']; ?>
            </span>
        <?php endif; ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
        <li><h6 class="dropdown-header">Notificações de Hoje</h6></li>
        <?php if($notificacoes['total'] > 0): ?>
            <li><a class="dropdown-item small" href="#">Você tem <?php echo $notificacoes['total']; ?> tarefas hoje!</a></li>
        <?php else: ?>
            <li><a class="dropdown-item small text-muted" href="#">Tudo em dia por aqui! ✨</a></li>
        <?php endif; ?>
    </ul>
</div>


        

    <form action="../actions/login.php" method="POST">        <div class="mb-3">
            <label class="form-label small fw-bold">Seu E-mail</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input type="email" name="email" class="form-control border-start-0" placeholder="nome@email.com" required style="border-radius: 0 12px 12px 0;">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">Sua Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;">
                    <i class="bi bi-shield-lock text-muted"></i>
                </span>
                <input type="password" name="senha" class="form-control border-start-0" placeholder="••••••••" required style="border-radius: 0 12px 12px 0;">
            </div>
        </div>

        <button type="submit" class="btn btn-login w-100 mb-3">Entrar no Painel</button>
        
        <div class="text-center">
            <span class="text-muted small">Ainda não tem conta?</span><br>
            <a href="../pages/cadastro.php" class="text-primary fw-bold text-decoration-none small">Criar conta familiar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Verifica se existe a mensagem de sucesso na URL
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('msg') === 'sucesso') {
        Swal.fire({
            title: 'Cadastro Concluído!',
            text: 'Sua conta foi criada com sucesso. Agora você já pode entrar!',
            icon: 'success',
            confirmButtonColor: '#764ba2', // Cor roxa que combina com o seu layout
            confirmButtonText: 'Entendido'
        });
    }
</script>
</body>
</html>