<?php $pageTitle = 'Dépenses — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
    <h1>Dépenses</h1>
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
    function loadTypes(){ const annee=$('#year').val(); const $t=$('#type');
      return $.getJSON('api/depense-types', { annee }).then(list=>{
        $t.find('option:not([value=""])').remove();
        list.forEach(v=> $t.append(`<option value="${v}">${v}</option>`));
      }).fail(jq=>console.error('api/depense-types failed', jq.status, jq.responseText));
    }
    function load(){
      const y=$('#year').val(), type=$('#type').val();
      $.getJSON('api/depenses',{ annee:y, type }).then(rows=>{
        const $tb=$('#tbl tbody');$tb.empty();
        rows.forEach(r=> $tb.append(`<tr><td>${r.nom}</td><td>${r.description||''}</td><td class=\"num\">${fmt(r.montant)}</td><td>${r.type}</td></tr>`));
        const grouped={}; rows.forEach(r=> grouped[r.type]=(grouped[r.type]||0)+Number(r.montant));
        const labels=Object.keys(grouped), data=Object.values(grouped);
        if(chart) chart.destroy();
        chart=new Chart(document.getElementById('chart'),{type:'doughnut',data:{labels,datasets:[{data,backgroundColor:['#2d6cdf','#10b981','#f59e0b','#ef4444']} ]},options:{responsive:true}});
      }).fail(jq=>console.error('api/depenses failed', jq.status, jq.responseText));
    }
    $(function(){ years().then(list=>{const $y=$('#year');list.forEach(r=>$y.append(`<option>${r.annee}</option>`));$y.val(list[list.length-1].annee);
        loadTypes().then(load);
      });
      $('#year').on('change', ()=> loadTypes().then(load));
      $('#type').on('change', load);
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
