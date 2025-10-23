<?php $pageTitle = 'Secteurs & Croissance — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
  <h1>Secteurs & Croissance</h1>
  <p class="section-desc">Consultez la structure sectorielle de l’économie (primaire, secondaire, tertiaire) et l’évolution des sous-secteurs par année. Les taux présentés reflètent les dynamiques de croissance estimées dans la Loi de Finances.</p>
  <section class="filters">
    <label>Année <select id="year"></select></label>
    <label>Secteur parent
      <select id="parent">
        <option value="">Tous (racine)</option>
      </select>
    </label>
  </section>

  <section class="tables">
    <h3>Liste des secteurs</h3>
    <div class="table-actions">
      <label>Par page
        <select id="pgSizeSecteurs">
          <option>10</option>
          <option selected>20</option>
          <option>50</option>
        </select>
      </label>
      <button id="csvSecteurs" class="btn btn-secondary">Exporter CSV</button>
      <div class="pager">
        <button id="prevSecteurs" class="btn">◀</button>
        <span id="infoSecteurs"></span>
        <button id="nextSecteurs" class="btn">▶</button>
      </div>
    </div>
    <table id="tblSecteurs"><thead>
      <tr>
        <th data-sort="string">Nom</th>
        <th data-sort="string">Type</th>
        <th data-sort="string">Parent</th>
        <th data-sort="string">Description</th>
      </tr>
    </thead><tbody></tbody></table>
  </section>

  <section class="tables">
    <h3>Croissance sectorielle</h3>
    <div class="table-actions">
      <label>Par page
        <select id="pgSizeCroissance">
          <option>10</option>
          <option selected>20</option>
          <option>50</option>
        </select>
      </label>
      <button id="csvCroissance" class="btn btn-secondary">Exporter CSV</button>
      <div class="pager">
        <button id="prevCroissance" class="btn">◀</button>
        <span id="infoCroissance"></span>
        <button id="nextCroissance" class="btn">▶</button>
      </div>
    </div>
    <table id="tblCroissance"><thead>
      <tr>
        <th data-sort="string">Secteur</th>
        <th data-sort="number">Taux (%)</th>
      </tr>
    </thead><tbody></tbody></table>
    <div class="charts">
      <canvas id="chartCroissance" height="120"></canvas>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    function ajaxErr(t,jq){ console.error(t, jq.status, jq.responseText); }
    function loadYears(){ return $.getJSON('api/years').then(rows=>rows.map(r=>r.annee)).fail(jq=>ajaxErr('GET years',jq)); }
    function loadSecteurs(parent){ return $.getJSON('api/secteurs', parent?{parent_id: parent}:{}) }
    function loadCroissance(annee, secteurId){ const p = { annee }; if(secteurId) p.secteur_id = secteurId; return $.getJSON('api/croissance-secteur', p); }

    function fillSelect($sel, arr, opts={}){ $sel.empty(); if(opts.placeholder){ $sel.append(`<option value="">${opts.placeholder}</option>`); } arr.forEach(v=> $sel.append(`<option value="${v}">${v}</option>`)); }
    function sortTable($table, idx, type){ const rows=[...$table[0].tBodies[0].rows]; const dir = $table.data('sortDir')==='asc'?'desc':'asc'; rows.sort((a,b)=>{ let x=a.cells[idx].dataset.sort ?? a.cells[idx].innerText, y=b.cells[idx].dataset.sort ?? b.cells[idx].innerText; if(type==='number'){ x=parseFloat(x)||0; y=parseFloat(y)||0; } return dir==='asc' ? (x>y?1:(x<y?-1:0)) : (x<y?1:(x>y?-1:0)); }); $table.data('sortDir', dir); rows.forEach(r=>$table[0].tBodies[0].appendChild(r)); }
    // Pagination helpers
    const state = {
      secteurs: { page: 1, size: 20, data: [] },
      croissance: { page: 1, size: 20, data: [] }
    };
    function paginate(arr, page, size){ const start=(page-1)*size; return arr.slice(start, start+size); }
    function pageInfo(total, page, size){ const start = total? ( (page-1)*size + 1 ):0; const end = Math.min(page*size, total); return `${start}-${end} / ${total}`; }
    function toCSV(rows, headers){ const esc = v=> '"'+(String(v??'').replaceAll('"','""'))+'"'; return [headers.join(','), ...rows.map(r=> headers.map(h=>esc(r[h])).join(','))].join('\n'); }

    function renderSecteurs(rows, parents){
      state.secteurs.data = rows.map(r=>({
        Nom: r.nom,
        Type: r.type||'',
        Parent: r.parent_id ? (parents.find(p=>p.id==r.parent_id)?.nom||r.parent_id) : '',
        Description: r.description||''
      }));
      state.secteurs.page = 1;
      drawSecteurs();
    }
    function drawSecteurs(){
      const s = state.secteurs; const pageRows = paginate(s.data, s.page, s.size);
      const $tb = $('#tblSecteurs tbody'); $tb.empty();
      pageRows.forEach(r=>{ $tb.append(`<tr><td>${r.Nom}</td><td>${r.Type}</td><td>${r.Parent}</td><td>${r.Description}</td></tr>`); });
      $('#infoSecteurs').text(pageInfo(s.data.length, s.page, s.size));
    }

    let chartCroissance;
    function renderCroissance(rows){
      state.croissance.data = rows.map(r=>({ Secteur: r.secteur, Taux: parseFloat(r.taux)||0 }));
      state.croissance.page = 1;
      drawCroissance();
      // Chart
      const labels = rows.map(r=>r.secteur);
      const data = rows.map(r=> parseFloat(r.taux)||0);
      if(chartCroissance) chartCroissance.destroy();
      chartCroissance = new Chart(document.getElementById('chartCroissance'), {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Taux de croissance (%)', data, backgroundColor: '#4f46e5' }] },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
      });
    }
    function drawCroissance(){
      const s = state.croissance; const pageRows = paginate(s.data, s.page, s.size);
      const $tb=$('#tblCroissance tbody'); $tb.empty();
      pageRows.forEach(r=>{ $tb.append(`<tr><td data-sort="${r.Secteur}">${r.Secteur}</td><td data-sort="${r.Taux}">${r.Taux.toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td></tr>`); });
      $('#infoCroissance').text(pageInfo(s.data.length, s.page, s.size));
    }

    $(function(){
      // headers sorting
      $('#tblSecteurs thead th').on('click', function(){ sortTable($('#tblSecteurs'), $(this).index(), $(this).data('sort')); });
      $('#tblCroissance thead th').on('click', function(){ sortTable($('#tblCroissance'), $(this).index(), $(this).data('sort')); });

      let allRoot=[];
      loadYears().then(list=>{ fillSelect($('#year'), list); const last=list[list.length-1]; $('#year').val(last).trigger('change'); });
      loadSecteurs(null).then(rows=>{ allRoot=rows; rows.forEach(r=> $('#parent').append(`<option value="${r.id}">${r.nom}</option>`)); renderSecteurs(rows, allRoot); });

      $('#parent, #year').on('change', function(){
        const parentId = $('#parent').val();
        const year = parseInt($('#year').val());
        const loadList = parentId ? loadSecteurs(parentId) : loadSecteurs(null);
        loadList.then(rows=>{ renderSecteurs(rows, allRoot); return loadCroissance(year, parentId||null); }).then(renderCroissance).fail(jq=>ajaxErr('load data', jq));
      });

      // initial croissance load after years filled
      $('#year').on('change.init', function(){ const y=parseInt(this.value); loadCroissance(y, null).then(renderCroissance); $(this).off('change.init'); });

      // Pagination controls
      $('#pgSizeSecteurs').on('change', function(){ state.secteurs.size=parseInt(this.value); state.secteurs.page=1; drawSecteurs(); });
      $('#prevSecteurs').on('click', function(){ if(state.secteurs.page>1){ state.secteurs.page--; drawSecteurs(); }});
      $('#nextSecteurs').on('click', function(){ const max=Math.ceil(state.secteurs.data.length/state.secteurs.size)||1; if(state.secteurs.page<max){ state.secteurs.page++; drawSecteurs(); }});
      $('#pgSizeCroissance').on('change', function(){ state.croissance.size=parseInt(this.value); state.croissance.page=1; drawCroissance(); });
      $('#prevCroissance').on('click', function(){ if(state.croissance.page>1){ state.croissance.page--; drawCroissance(); }});
      $('#nextCroissance').on('click', function(){ const max=Math.ceil(state.croissance.data.length/state.croissance.size)||1; if(state.croissance.page<max){ state.croissance.page++; drawCroissance(); }});

      // CSV export
      $('#csvSecteurs').on('click', function(){ const csv = toCSV(state.secteurs.data, ['Nom','Type','Parent','Description']); const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'}); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download='secteurs.csv'; a.click(); });
      $('#csvCroissance').on('click', function(){ const csv = toCSV(state.croissance.data, ['Secteur','Taux']); const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'}); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download='croissance.csv'; a.click(); });
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
