<?php $pageTitle = (function_exists('t') ? t('page.postes.title') : 'Postes budgétaires') . ' — ' . (function_exists('t') ? t('app.title') : 'Loi de Finances'); include __DIR__ . '/partials/header.php'; ?>
  <h1><?= htmlspecialchars(function_exists('t') ? t('page.postes.title') : 'Postes budgétaires') ?></h1>
  <section class="filters">
    <label><?= htmlspecialchars(function_exists('t') ? t('labels.year') : 'Année') ?> <select id="year"></select></label>
    <label><?= htmlspecialchars(function_exists('t') ? t('labels.category_id') : 'Catégorie (ID)') ?> <input type="number" id="cat" min="1" placeholder="<?= htmlspecialchars(function_exists('t') ? t('placeholders.category_example') : 'ex: 20') ?>" /></label>
    <button id="btnSearch" class="btn btn-primary"><?= htmlspecialchars(function_exists('t') ? t('buttons.filter') : 'Filtrer') ?></button>
  </section>

  <section class="tables">
    <table id="tblPostes"><thead>
      <tr>
        <th data-sort="string"><?= htmlspecialchars(function_exists('t') ? t('labels.category') : 'Catégorie') ?></th>
        <th data-sort="number"><?= htmlspecialchars(function_exists('t') ? t('labels.count') : 'Nombre') ?></th>
        <th data-sort="string"><?= htmlspecialchars(function_exists('t') ? t('labels.description') : 'Description') ?></th>
      </tr>
    </thead><tbody></tbody></table>
  </section>

  <script>
    function ajaxErr(t,jq){ console.error(t, jq.status, jq.responseText); }
    function loadYears(){ return $.getJSON('api/years').then(rows=>rows.map(r=>r.annee)); }
    function loadPostes(annee, categorie){ const p={ annee }; if(categorie) p.categorie=categorie; return $.getJSON('api/postes', p); }
    function fillSelect($sel, arr){ $sel.empty(); arr.forEach(v=> $sel.append(`<option value="${v}">${v}</option>`)); }
    function sortTable($table, idx, type){ const rows=[...$table[0].tBodies[0].rows]; const dir=$table.data('sortDir')==='asc'?'desc':'asc'; rows.sort((a,b)=>{ let x=a.cells[idx].dataset.sort ?? a.cells[idx].innerText, y=b.cells[idx].dataset.sort ?? b.cells[idx].innerText; if(type==='number'){ x=parseInt(x)||0; y=parseInt(y)||0; } return dir==='asc'?(x>y?1:(x<y?-1:0)):(x<y?1:(x>y?-1:0)); }); $table.data('sortDir',dir); rows.forEach(r=>$table[0].tBodies[0].appendChild(r)); }
    function render(rows){ const $tb=$('#tblPostes tbody'); $tb.empty(); rows.forEach(r=>{ $tb.append(`<tr><td>${r.categorie}</td><td data-sort="${r.nombre}">${r.nombre}</td><td>${r.description||''}</td></tr>`); }); }
    $(function(){
      $('#tblPostes thead th').on('click', function(){ sortTable($('#tblPostes'), $(this).index(), $(this).data('sort')); });
      loadYears().then(list=>{ fillSelect($('#year'), list); const last=list[list.length-1]; $('#year').val(last); reload(); });
      $('#btnSearch').on('click', reload);
      function reload(){ const y=parseInt($('#year').val()); const c=parseInt($('#cat').val())||null; loadPostes(y, c).then(render).fail(jq=>ajaxErr('GET postes', jq)); }
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
