<?php $pageTitle = 'Recettes — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
    <h1>Recettes</h1>
    <section class="filters">
      <label>Année <select id="year"></select></label>
      <label>Type <select id="type"><option value="">Toutes</option></select></label>
    </section>
    <section class="tables">
      <table id="tbl"><thead><tr><th>Catégorie</th><th>Description</th><th>Montant</th><th>Type</th></tr></thead><tbody></tbody></table>
    </section>
    <section class="charts"><canvas id="chart"></canvas></section>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    function fmt(x){ return (x||0).toLocaleString('fr-FR', {minimumFractionDigits:1, maximumFractionDigits:1}); }
    let chart;
    function years(){ return $.getJSON('api/years').fail(jq=>console.error('api/years failed', jq.status, jq.responseText)); }
    function loadTypes(){ const annee=$('#year').val(); return $.getJSON('api/recette-types',{ annee }).then(list=>{ const $t=$('#type'); $t.find('option:not([value=""])').remove(); list.forEach(v=>$t.append(`<option value="${v}">${v}</option>`)); }); }
    function load(){
      const y=$('#year').val(), type=$('#type').val();
      const $tb=$('#tbl tbody'); $tb.empty();
      $.getJSON('api/recettes',{ annee:y, type }).then(rows=>{
        rows.forEach(r=> $tb.append(`<tr><td>${r.nom}</td><td>${r.description||''}</td><td class=\"num\">${fmt(r.montant)}</td><td>${r.type}</td></tr>`));
        const labels = rows.map(r=>r.nom);
        const data = rows.map(r=>Number(r.montant));
        if(chart) chart.destroy();
        chart=new Chart(document.getElementById('chart'),{type:'bar',data:{labels,datasets:[{label:String(y),data,backgroundColor:'#2d6cdf'}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});
      });
    }
    $(function(){
      years().then(list=>{ const $y=$('#year'); list.forEach(r=>{ $y.append(`<option>${r.annee}</option>`); }); if(list.length){ $y.val(list[list.length-1].annee); } return loadTypes(); }).then(load);
      $('#year,#type').on('change',load);
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
