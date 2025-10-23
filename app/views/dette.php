<?php $pageTitle = 'Dette — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
  <h1>Dette</h1>
  <p class="section-desc">Consultez la dette publique par type (intérieure, extérieure) et comparez deux années pour suivre l’évolution des intérêts et du principal conformément aux informations de la Loi de Finances.</p>
  <section class="filters">
    <label>Année <select id="year"></select></label>
    <label>Type
      <select id="type">
        <option value="">Tous</option>
        <option value="interieure">Intérieure</option>
        <option value="exterieure">Extérieure</option>
      </select>
    </label>
  </section>

  <section class="tables">
    <h3>Dette par type</h3>
    <table id="tblDette"><thead>
      <tr>
        <th data-sort="string">Type</th>
        <th data-sort="number">Intérêts</th>
        <th data-sort="number">Principal</th>
        <th data-sort="number">Taux moyen</th>
      </tr>
    </thead><tbody></tbody></table>
  </section>

  <section class="filters">
    <h3>Comparaison</h3>
    <label>Année 1 <select id="year1"></select></label>
    <label>Année 2 <select id="year2"></select></label>
  </section>
  <section class="tables">
    <table id="tblCompare"><thead>
      <tr>
        <th data-sort="string">Type</th>
        <th data-sort="number">Intérêts A1</th>
        <th data-sort="number">Intérêts A2</th>
        <th data-sort="number">Principal A1</th>
        <th data-sort="number">Principal A2</th>
      </tr>
    </thead><tbody></tbody></table>
  </section>

  <script>
    function ajaxErr(t,jq){ console.error(t, jq.status, jq.responseText); }
    function loadYears(){ return $.getJSON('api/years').then(rows=>rows.map(r=>r.annee)); }
    function loadDette(annee, type){ const p={annee}; if(type) p.type=type; return $.getJSON('api/dette', p); }
    function loadCompare(annee1, annee2){ return $.getJSON('api/compare-dette', { annee1, annee2 }); }
    function fillSelect($sel, arr){ $sel.empty(); arr.forEach(v=> $sel.append(`<option value="${v}">${v}</option>`)); }
    function sortTable($table, idx, type){ const rows=[...$table[0].tBodies[0].rows]; const dir=$table.data('sortDir')==='asc'?'desc':'asc'; rows.sort((a,b)=>{ let x=a.cells[idx].dataset.sort ?? a.cells[idx].innerText, y=b.cells[idx].dataset.sort ?? b.cells[idx].innerText; if(type==='number'){ x=parseFloat(x)||0; y=parseFloat(y)||0; } return dir==='asc'?(x>y?1:(x<y?-1:0)):(x<y?1:(x>y?-1:0)); }); $table.data('sortDir',dir); rows.forEach(r=>$table[0].tBodies[0].appendChild(r)); }

    function renderDette(rows){ const $tb=$('#tblDette tbody'); $tb.empty(); rows.forEach(r=>{ $tb.append(`<tr>
      <td>${r.type}</td>
      <td data-sort="${r.interets||0}">${(parseFloat(r.interets)||0).toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
      <td data-sort="${r.principal||0}">${(parseFloat(r.principal)||0).toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
      <td data-sort="${r.taux_moyen||0}">${r.taux_moyen!=null?(parseFloat(r.taux_moyen)||0).toFixed(2)+'%':''}</td>
    </tr>`); }); }

    function renderCompare(rows){ const $tb=$('#tblCompare tbody'); $tb.empty(); rows.forEach(r=>{ $tb.append(`<tr>
      <td>${r.type}</td>
      <td data-sort="${r.interets1||0}">${(parseFloat(r.interets1)||0).toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
      <td data-sort="${r.interets2||0}">${(parseFloat(r.interets2)||0).toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
      <td data-sort="${r.principal1||0}">${(parseFloat(r.principal1)||0).toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
      <td data-sort="${r.principal2||0}">${(parseFloat(r.principal2)||0).toLocaleString('fr-FR',{minimumFractionDigits:1, maximumFractionDigits:1})}</td>
    </tr>`); }); }

    $(function(){
      $('#tblDette thead th').on('click', function(){ sortTable($('#tblDette'), $(this).index(), $(this).data('sort')); });
      $('#tblCompare thead th').on('click', function(){ sortTable($('#tblCompare'), $(this).index(), $(this).data('sort')); });

      loadYears().then(list=>{ fillSelect($('#year'), list); fillSelect($('#year1'), list); fillSelect($('#year2'), list); const last=list[list.length-1]; $('#year').val(last); $('#year1').val(list[Math.max(0,list.length-2)]); $('#year2').val(last); reload(); reloadCompare(); });
      $('#year, #type').on('change', reload);
      $('#year1, #year2').on('change', reloadCompare);

      function reload(){ const y=parseInt($('#year').val()); const t=$('#type').val()||null; loadDette(y,t).then(renderDette).fail(jq=>ajaxErr('GET dette', jq)); }
      function reloadCompare(){ const y1=parseInt($('#year1').val()); const y2=parseInt($('#year2').val()); loadCompare(y1,y2).then(renderCompare).fail(jq=>ajaxErr('GET compare-dette', jq)); }
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
