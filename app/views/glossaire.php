<?php $pageTitle = 'Glossaire — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
  <h1>Glossaire</h1>
  <p class="section-desc">Recherchez les acronymes et termes utilisés dans la Loi de Finances. Utilisez le filtre de type pour distinguer entre acronymes et définitions générales.</p>
  <section class="filters">
    <label>Recherche <input type="text" id="q" placeholder="terme ou définition" /></label>
    <label>Type
      <select id="type">
        <option value="">Tous</option>
        <option value="acronyme">Acronyme</option>
        <option value="terme">Terme</option>
      </select>
    </label>
    <button id="btnSearch" class="btn btn-primary">Rechercher</button>
  </section>

  <section class="tables">
    <table id="tblGlossaire"><thead>
      <tr>
        <th data-sort="string">Type</th>
        <th data-sort="string">Terme</th>
        <th data-sort="string">Définition</th>
      </tr>
    </thead><tbody></tbody></table>
  </section>

  <script>
    function ajaxErr(t,jq){ console.error(t, jq.status, jq.responseText); }
    function loadGlossaire(q, type){ const p={}; if(q) p.q=q; if(type) p.type=type; return $.getJSON('api/glossaire', p); }
    function sortTable($table, idx, type){ const rows=[...$table[0].tBodies[0].rows]; const dir=$table.data('sortDir')==='asc'?'desc':'asc'; rows.sort((a,b)=>{ let x=a.cells[idx].dataset.sort ?? a.cells[idx].innerText, y=b.cells[idx].dataset.sort ?? b.cells[idx].innerText; if(type==='number'){ x=parseFloat(x)||0; y=parseFloat(y)||0; } return dir==='asc'?(x>y?1:(x<y?-1:0)):(x<y?1:(x>y?-1:0)); }); $table.data('sortDir',dir); rows.forEach(r=>$table[0].tBodies[0].appendChild(r)); }
    function render(rows){ const $tb=$('#tblGlossaire tbody'); $tb.empty(); rows.forEach(r=>{ $tb.append(`<tr>
      <td>${r.type}</td>
      <td>${r.terme}</td>
      <td>${r.definition}</td>
    </tr>`); }); }
    $(function(){
      $('#tblGlossaire thead th').on('click', function(){ sortTable($('#tblGlossaire'), $(this).index(), $(this).data('sort')); });
      function reload(){ const q=$('#q').val().trim()||null; const t=$('#type').val()||null; loadGlossaire(q,t).then(render).fail(jq=>ajaxErr('GET glossaire', jq)); }
      $('#btnSearch').on('click', reload);
      reload();
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
