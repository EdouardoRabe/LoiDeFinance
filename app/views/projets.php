<?php $pageTitle = 'Projets d\'investissement — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
  <h1>Projets d'investissement</h1>
  <p class="section-desc">Explorez les projets d’investissement publics par secteur, source de financement et catégorie budgétaire. Utilisez les filtres pour cibler une thématique (énergie, agriculture, infrastructures, santé, éducation, etc.).</p>
  <section class="filters">
    <label>Année <select id="year"></select></label>
    <label>Secteur <input type="text" id="f_secteur" placeholder="Énergie, Agriculture..." /></label>
    <label>Source
      <select id="f_source">
        <option value="">Toutes</option>
        <option value="interne">Interne</option>
        <option value="externe">Externe</option>
        <option value="mixte">Mixte</option>
      </select>
    </label>
    <label>Catégorie (ID) <input type="number" id="f_cat" min="1" placeholder="ex: 26" /></label>
    <label>Recherche <input type="text" id="q" placeholder="Nom ou description" /></label>
    <button id="btnSearch" class="btn btn-primary">Filtrer</button>
  </section>

  <section class="tables">
    <table id="tblProjets"><thead>
      <tr>
        <th data-sort="string">Nom</th>
        <th data-sort="string">Secteur</th>
        <th data-sort="string">Source</th>
        <th data-sort="number">Montant</th>
        <th data-sort="number">Catégorie</th>
        <th data-sort="string">Description</th>
      </tr>
    </thead><tbody></tbody></table>
  </section>

  <script>
    function ajaxErr(t,jq){ console.error(t, jq.status, jq.responseText); }
    function loadYears(){ return $.getJSON('api/years').then(rows=>rows.map(r=>r.annee)).fail(jq=>ajaxErr('GET years',jq)); }
    function loadProjets(annee, secteur, source, categorie, q, sort){ const p={annee}; if(secteur) p.secteur=secteur; if(source) p.source=source; if(categorie) p.categorie=categorie; if(q) p.q=q; if(sort) p.sort=sort; return $.getJSON('api/projets', p); }
    function fillSelect($sel, arr){ $sel.empty(); arr.forEach(v=> $sel.append(`<option value="${v}">${v}</option>`)); }
    function sortTable($table, idx, type){ const rows=[...$table[0].tBodies[0].rows]; const dir=$table.data('sortDir')==='asc'?'desc':'asc'; rows.sort((a,b)=>{ let x=a.cells[idx].dataset.sort ?? a.cells[idx].innerText, y=b.cells[idx].dataset.sort ?? b.cells[idx].innerText; if(type==='number'){ x=parseFloat(x)||0; y=parseFloat(y)||0; } return dir==='asc'?(x>y?1:(x<y?-1:0)):(x<y?1:(x>y?-1:0)); }); $table.data('sortDir',dir); rows.forEach(r=>$table[0].tBodies[0].appendChild(r)); }
    function render(rows){ const $tb=$('#tblProjets tbody'); $tb.empty(); rows.forEach(r=>{ const montant = (parseFloat(r.montant)||0).toLocaleString('fr-FR', {minimumFractionDigits:1, maximumFractionDigits:1}); $tb.append(`<tr>
          <td>${r.nom}</td>
          <td>${r.secteur||''}</td>
          <td>${r.source_financement}</td>
          <td data-sort="${r.montant||0}">${montant}</td>
          <td data-sort="${r.id_categorie_depense||''}">${r.id_categorie_depense||''}</td>
          <td>${r.description||''}</td>
        </tr>`); }); }

    $(function(){
      $('#tblProjets thead th').on('click', function(){ sortTable($('#tblProjets'), $(this).index(), $(this).data('sort')); });
      loadYears().then(list=>{ fillSelect($('#year'), list); const last=list[list.length-1]; $('#year').val(last); reload(); });
      $('#btnSearch').on('click', reload);
      function reload(){ const y=parseInt($('#year').val()); const s=$('#f_secteur').val().trim(); const so=$('#f_source').val(); const c=parseInt($('#f_cat').val())||null; const q=$('#q').val().trim(); loadProjets(y, s||null, so||null, c, q||null, null).then(render).fail(jq=>ajaxErr('GET projets', jq)); }
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
