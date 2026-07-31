<?php
$pag = 'mapa_reservas';

// permissão
if (@$mapa_reservas == 'ocultar') {
  echo "<script>window.location='../index.php'</script>";
  exit();
}

// datas padrão (hoje → +30 dias)
$iniDefault = date('Y-m-d');
$fimDefault = date('Y-m-d', strtotime('+30 days'));
?>
<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locales-all.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.9/index.global.min.js"></script>

<div style="display:flex; gap:.5rem; align-items:center; margin:10px 0;">
  <label for="ini">Início:</label>
  <input type="date" id="ini" value="<?=$iniDefault?>">
  <label for="fim">Fim:</label>
  <input type="date" id="fim" value="<?=$fimDefault?>">
  <button id="btn-aplicar" class="btn btn-primary btn-sm">Aplicar</button>
</div>

<div id="calendar" style="height:70vh"></div>

<script>
function el(id){ return document.getElementById(id); }

const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
  locale: 'pt-br',
  initialView: 'resourceTimelineWeek',
  slotDuration: { days: 1 },
  resourceGroupField: 'groupName',          // agrupa por categoria
  resourceAreaHeaderContent: 'Quartos',

  headerToolbar: {
    left: 'today prev,next',
    center: 'title',
    right: 'resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
  },

  // Traduções manuais dos botões
  buttonText: {
    today: 'Hoje',
    day:   'Dia',
    week:  'Semana',
    month: 'Mês'
  },

  resources(fetchInfo, success, failure) {
    const url = `paginas/${pag}/mapa.php?ini=${el('ini').value}&fim=${el('fim').value}`;
    fetch(url).then(r => r.json()).then(j => success(j.resources)).catch(failure);
  },
  events(fetchInfo, success, failure) {
    const url = `paginas/${pag}/mapa.php?ini=${el('ini').value}&fim=${el('fim').value}`;
    fetch(url).then(r => r.json()).then(j => success(j.events)).catch(failure);
  },

  eventDidMount(info){
    info.el.title = info.event.title || '';
  }
});

document.getElementById('btn-aplicar').addEventListener('click', () => {
  cal.refetchResources();
  cal.refetchEvents();
});

cal.render();
</script>

<style>
/* cores / legenda */
.fc .evt.status-reservado  { background:#8bc34a; border-color:#7cb342; }
.fc .evt.status-hospedado  { background:#42a5f5; border-color:#1e88e5; }
.fc .evt.bloqueio          { background:#9e9e9e; opacity:.35; }
</style>
