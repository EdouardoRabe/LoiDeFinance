<?php $pageTitle = (function_exists('t') ? t('page.indicateurs.title') : 'Indicateurs macro') . ' — ' . (function_exists('t') ? t('app.title') : 'Loi de Finances'); include __DIR__ . '/partials/header.php'; ?>
  <h1><?= htmlspecialchars(function_exists('t') ? t('page.indicateurs.title') : 'Indicateurs macro') ?></h1>
  <p class="section-desc">Consultez les principaux indicateurs macroéconomiques par année et visualisez leurs séries temporelles.</p>

  <section class="filters">
    <label><?= htmlspecialchars(function_exists('t') ? t('labels.year') : 'Année') ?> <select id="year"></select></label>
    <label><?= htmlspecialchars(function_exists('t') ? t('labels.search') : 'Recherche') ?> <input type="text" id="q" placeholder="nom contient…" /></label>
    <button id="btnLoad" class="btn btn-primary"><?= htmlspecialchars(function_exists('t') ? t('buttons.load') : 'Charger') ?></button>
  </section>

  <section class="tables">
    <h3><?= htmlspecialchars(function_exists('t') ? t('labels.indicators') : 'Indicateurs') ?></h3>
    <div class="table-actions">
      <label><?= htmlspecialchars(function_exists('t') ? t('labels.per_page') : 'Par page') ?>
        <select id="pgSizeIndics">
          <option>10</option>
          <option selected>20</option>
          <option>50</option>
        </select>
      </label>
      <button id="csvIndics" class="btn btn-secondary"><?= htmlspecialchars(function_exists('t') ? t('labels.export_csv') : 'Exporter CSV') ?></button>
      <div class="pager">
        <button id="prevIndics" class="btn">◀</button>
        <span id="infoIndics"></span>
        <button id="nextIndics" class="btn">▶</button>
      </div>
    </div>
    <table id="tblIndics"><thead>
      <tr>
        <th data-sort="string"><?= htmlspecialchars(function_exists('t') ? t('labels.name') : 'Nom') ?></th>
        <th data-sort="number"><?= htmlspecialchars(function_exists('t') ? t('labels.value') : 'Valeur') ?></th>
        <th data-sort="string"><?= htmlspecialchars(function_exists('t') ? t('labels.unit') : 'Unité') ?></th>
      </tr>
    </thead><tbody></tbody></table>
  </section>

  <section class="filters">
    <h3>Séries multi-années</h3>
    <label><?= htmlspecialchars(function_exists('t') ? t('labels.years') : 'Années') ?> <select id="years" multiple size="4"></select></label>
    <label><?= htmlspecialchars(function_exists('t') ? t('labels.indicators') : 'Indicateurs') ?> <input type="text" id="names" placeholder="<?= htmlspecialchars(function_exists('t') ? t('placeholders.indicator_names') : 'ex: Solde global (base caisse); Taux de change Dollars/Ariary') ?>" /></label>
    <button id="btnSeries" class="btn btn-secondary"><?= htmlspecialchars(function_exists('t') ? t('labels.show_series') : 'Afficher séries') ?></button>
  </section>

  <section class="tables">
    <table id="tblSeries"><thead>
      <tr>
        <th data-sort="string"><?= htmlspecialchars(function_exists('t') ? t('labels.name') : 'Nom') ?></th>
        <th data-sort="number"><?= htmlspecialchars(function_exists('t') ? t('labels.year') : 'Année') ?></th>
        <th data-sort="number"><?= htmlspecialchars(function_exists('t') ? t('labels.value') : 'Valeur') ?></th>
        <th data-sort="string"><?= htmlspecialchars(function_exists('t') ? t('labels.unit') : 'Unité') ?></th>
      </tr>
    </thead><tbody></tbody></table>
    <div class="table-actions">
      <label><?= htmlspecialchars(function_exists('t') ? t('labels.per_page') : 'Par page') ?>
        <select id="pgSizeSeries">
          <option>10</option>
          <option selected>20</option>
          <option>50</option>
        </select>
      </label>
      <button id="csvSeries" class="btn btn-secondary"><?= htmlspecialchars(function_exists('t') ? t('labels.export_csv') : 'Exporter CSV') ?></button>
      <div class="pager">
        <button id="prevSeries" class="btn">◀</button>
        <span id="infoSeries"></span>
        <button id="nextSeries" class="btn">▶</button>
      </div>
    </div>
    <div class="charts">
      <canvas id="chartSeries" height="140"></canvas>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    function ajaxErr(t,jq){ console.error(t, jq.status, jq.responseText); }
    function loadYears(){ return $.getJSON('api/years').then(rows=>rows.map(r=>r.annee)); }
    function loadIndics(annee, q){ const p={ annee }; if(q) p.q=q; return $.getJSON('api/indicateurs', p); }
    function loadSeries(annees, noms){ const p=new URLSearchParams(); (annees||[]).forEach(a=>p.append('annee', a)); (noms||[]).forEach(n=>p.append('nom', n)); return $.getJSON('api/indicateurs-serie?'+p.toString()); }
    function fillSelect($sel, arr, multi=false){ $sel.empty(); arr.forEach(v=> $sel.append(`<option value="${v}">${v}</option>`)); if(!multi && arr.length){ $sel.val(arr[arr.length-1]); } }
    function sortTable($table, idx, type){ const rows=[...$table[0].tBodies[0].rows]; const dir=$table.data('sortDir')==='asc'?'desc':'asc'; rows.sort((a,b)=>{ let x=a.cells[idx].dataset.sort ?? a.cells[idx].innerText, y=b.cells[idx].dataset.sort ?? b.cells[idx].innerText; if(type==='number'){ x=parseFloat(x)||0; y=parseFloat(y)||0; } return dir==='asc'?(x>y?1:(x<y?-1:0)):(x<y?1:(x>y?-1:0)); }); $table.data('sortDir',dir); rows.forEach(r=>$table[0].tBodies[0].appendChild(r)); }
    // Pagination + CSV state
    const state = {
      indics: { page: 1, size: 20, data: [] },
      series: { page: 1, size: 20, data: [] }
    };
    function paginate(arr, page, size){ const start=(page-1)*size; return arr.slice(start, start+size); }
    function pageInfo(total, page, size){ const start = total? ( (page-1)*size + 1 ):0; const end = Math.min(page*size, total); return `${start}-${end} / ${total}`; }
    function toCSV(rows, headers){ const esc = v=> '"'+(String(v??'').replaceAll('"','""'))+'"'; return [headers.join(','), ...rows.map(r=> headers.map(h=>esc(r[h])).join(','))].join('\n'); }

    function renderIndics(rows){
      state.indics.data = rows.map(r=>({ Nom: r.nom, Valeur: parseFloat(r.valeur)||0, Unite: r.unite||'' }));
      state.indics.page = 1; drawIndics();
    }
    function drawIndics(){ const s=state.indics; const pageRows=paginate(s.data, s.page, s.size); const $tb=$('#tblIndics tbody'); $tb.empty(); pageRows.forEach(r=>{ $tb.append(`<tr>
        <td>${r.Nom}</td>
        <td data-sort="${r.Valeur}">${r.Valeur.toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
        <td>${r.Unite}</td>
      </tr>`); }); $('#infoIndics').text(pageInfo(s.data.length, s.page, s.size)); }

    function renderSeries(rows){
      state.series.data = rows.map(r=>({ Nom: r.nom, Annee: parseInt(r.annee), Valeur: parseFloat(r.valeur)||0, Unite: r.unite||'' }));
      state.series.page = 1; drawSeries(); drawSeriesChart();
    }
    function drawSeries(){ const s=state.series; const pageRows=paginate(s.data, s.page, s.size); const $tb=$('#tblSeries tbody'); $tb.empty(); pageRows.forEach(r=>{ $tb.append(`<tr>
        <td>${r.Nom}</td>
        <td data-sort="${r.Annee}">${r.Annee}</td>
        <td data-sort="${r.Valeur}">${r.Valeur.toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
        <td>${r.Unite}</td>
      </tr>`); }); $('#infoSeries').text(pageInfo(s.data.length, s.page, s.size)); }

    let chartSeries;
    function drawSeriesChart(){
      const byName = {};
      state.series.data.forEach(r=>{ if(!byName[r.Nom]) byName[r.Nom]=[]; byName[r.Nom].push(r); });
      const names = Object.keys(byName);
      const allYears = Array.from(new Set(state.series.data.map(r=>r.Annee))).sort((a,b)=>a-b);
      const datasets = names.map((name,i)=>{
        const color = `hsl(${(i*67)%360} 70% 45%)`;
        const map = new Map(byName[name].map(x=>[x.Annee, x.Valeur]));
        return { label: name, borderColor: color, backgroundColor: color, data: allYears.map(y=> map.get(y) ?? null), tension: 0.25 };
      });
      if(chartSeries) chartSeries.destroy();
      chartSeries = new Chart(document.getElementById('chartSeries'), {
        type: 'line',
        data: { labels: allYears, datasets },
        options: { responsive: true, interaction: { mode: 'index', intersect: false }, stacked: false, plugins: { legend: { position: 'bottom' } } }
      });
    }

    $(function(){
      $('#tblIndics thead th').on('click', function(){ sortTable($('#tblIndics'), $(this).index(), $(this).data('sort')); });
      $('#tblSeries thead th').on('click', function(){ sortTable($('#tblSeries'), $(this).index(), $(this).data('sort')); });

      loadYears().then(list=>{ fillSelect($('#year'), list); fillSelect($('#years'), list, true); reload(); });
      $('#btnLoad').on('click', reload);
      $('#btnSeries').on('click', function(){ const years=$('#years').val()||[]; const names=$('#names').val().split(';').map(s=>s.trim()).filter(Boolean); loadSeries(years, names).then(renderSeries).fail(jq=>ajaxErr('GET indicateurs-serie', jq)); });

      // Pagination controls
      $('#pgSizeIndics').on('change', function(){ state.indics.size=parseInt(this.value); state.indics.page=1; drawIndics(); });
      $('#prevIndics').on('click', function(){ if(state.indics.page>1){ state.indics.page--; drawIndics(); }});
      $('#nextIndics').on('click', function(){ const max=Math.ceil(state.indics.data.length/state.indics.size)||1; if(state.indics.page<max){ state.indics.page++; drawIndics(); }});
      $('#pgSizeSeries').on('change', function(){ state.series.size=parseInt(this.value); state.series.page=1; drawSeries(); });
      $('#prevSeries').on('click', function(){ if(state.series.page>1){ state.series.page--; drawSeries(); }});
      $('#nextSeries').on('click', function(){ const max=Math.ceil(state.series.data.length/state.series.size)||1; if(state.series.page<max){ state.series.page++; drawSeries(); }});

      // CSV export
      $('#csvIndics').on('click', function(){ const csv = toCSV(state.indics.data, ['Nom','Valeur','Unite']); const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'}); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download='indicateurs.csv'; a.click(); });
      $('#csvSeries').on('click', function(){ const csv = toCSV(state.series.data, ['Nom','Annee','Valeur','Unite']); const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'}); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download='indicateurs_series.csv'; a.click(); });

      function reload(){ const y=parseInt($('#year').val()); const q=$('#q').val().trim()||null; loadIndics(y, q).then(renderIndics).fail(jq=>ajaxErr('GET indicateurs', jq)); }
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
