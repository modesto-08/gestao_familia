<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Família Tech - Gestão Inteligente</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { 
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.75);
            --text-main: #2d3436;
            --accent-color: #ffd700;
        }

        body { 
            background: #f8f9fa; 
            /* Background animado sutil */
            background-image: 
                radial-gradient(at 0% 0%, rgba(102, 126, 234, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(118, 75, 162, 0.05) 0px, transparent 50%);
            font-family: 'Poppins', sans-serif; 
            color: var(--text-main);
            min-height: 100vh;
        }

        /* Navbar com efeito Glassmorphism Real */
        .navbar { 
            background: var(--primary-gradient) !important; 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand { 
            font-weight: 800; 
            letter-spacing: -0.5px;
            font-size: 1.5rem;
        }

        /* Link Ativo e Hover */
        .nav-link {
            font-weight: 500;
            margin: 0 5px;
            transition: all 0.3s ease;
            opacity: 0.8;
        }
        .nav-link:hover {
            opacity: 1;
            transform: translateY(-2px);
        }
        .nav-link i { margin-right: 5px; }

        /* Badge de Pontos Premium */
        .badge-points {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            color: white;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Loader de página simples */
        #loader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 3px;
            background: var(--accent-color);
            z-index: 9999;
            transition: width 0.4s ease;
        }

        /* Melhoria nos Cards para Dashboard */
        .card { 
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px; 
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>
<body>

<div id="loader" style="width: 0;"></div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-5">
    <div class="container">
        <a class="navbar-brand animate__animated animate__fadeInDown" href="dashboard.php">
            <i class="bi bi-cpu-fill text-warning"></i> FAMÍLIA<span class="fw-light">TECH</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3" href="dashboard.php"><i class="bi bi-house-door"></i> Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="ranking.php"><i class="bi bi-bar-chart-line"></i> Ranking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="recompensas.php"><i class="bi bi-shop"></i> Loja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="calendario.php"><i class="bi bi-calendar3"></i> Agenda</a>
                </li>
                
                <li class="nav-item ms-lg-4">
                    <div class="badge-points">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span><?php echo explode(' ', $_SESSION['nome'])[0]; ?></span>
                    </div>
                </li>

                <li class="nav-item ms-lg-3">
                    <button onclick="confirmarSair()" class="btn btn-sm btn-light text-primary rounded-pill px-4 fw-bold shadow-sm">
                        Sair <i class="bi bi-box-arrow-right ms-1"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container animate__animated animate__fadeInUp">

<script>
    // Simulação de barra de carregamento ao mudar de página
    window.onload = () => {
        document.getElementById('loader').style.width = '100%';
        setTimeout(() => { document.getElementById('loader').style.opacity = '0'; }, 400);
    };

    // Função Interativa para Logout com SweetAlert2
    function confirmarSair() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Já vai?',
                text: "Deseja realmente encerrar sua sessão na Família Tech?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#764ba2',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, sair!',
                cancelButtonText: 'Ficar',
                reverseButtons: true,
                borderRadius: '20px'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../actions/logout.php";
                }
            })
        } else {
            if(confirm("Deseja realmente sair?")) {
                window.location.href = "../actions/logout.php";
            }
        }
    }
</script>