<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}
include('../includes/header.php');
?>

<style>
    /* 1. CORREÇÃO DE ESTRUTURA: Forçar o calendário a ocupar 100% */
    .fc {
        width: 100% !important;
        max-width: 100% !important;
    }

    .calendar-container {
        background: #fff;
        border-radius: 25px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-top: 20px;
        width: 100%; /* Garante que o card não comprima */
    }

    /* 2. VISUAL DAS PÍLULAS: Estilo moderno e preenchido */
    .fc-daygrid-event {
        border: none !important;
        border-radius: 6px !important;
        padding: 3px 6px !important;
        background-color: #764ba2 !important; /* Roxo do seu tema */
        color: white !important;
        font-size: 0.8rem !important;
        margin-top: 2px !important;
    }

    /* Ajuste para remover o ponto e mostrar apenas o bloco colorido */
    .fc-daygrid-event-dot {
        display: none !important;
    }

    .fc-event-title {
        font-weight: 600 !important;
    }

    /* 3. CABEÇALHO E DIAS */
    .fc-col-header-cell-cushion {
        color: #764ba2 !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none !important;
    }

    .fc-daygrid-day-number {
        color: #6c757d !important;
        text-decoration: none !important;
        padding: 8px !important;
        font-size: 0.9rem;
    }

    /* Dia de hoje com destaque suave */
    .fc-day-today {
        background-color: rgba(118, 75, 162, 0.05) !important;
    }

    /* Botões do FullCalendar personalizados */
    .fc .fc-button-primary {
        background-color: #764ba2 !important;
        border-color: #764ba2 !important;
        border-radius: 10px !important;
        text-transform: capitalize;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold"><i class="bi bi-calendar3 text-primary"></i> Agenda Familiar</h2>
            <p class="text-muted">Veja o que a galera está aprontando hoje!</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary rounded-pill px-4" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Atualizar
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="calendar-container">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'pt-br',
      expandRows: true, // Faz as linhas crescerem para ocupar o espaço
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek'
      },
      buttonText: {
        today: 'Hoje',
        month: 'Mês',
        week: 'Semana'
      },
      events: '../actions/buscar_eventos.php',
      
      eventClick: function(info) {
        Swal.fire({
          title: info.event.title,
          text: info.event.extendedProps.description || 'Tarefa agendada para hoje.',
          icon: 'info',
          confirmButtonColor: '#764ba2'
        });
      }
    });
    calendar.render();
  });
</script>

<?php include('../includes/footer.php'); ?>