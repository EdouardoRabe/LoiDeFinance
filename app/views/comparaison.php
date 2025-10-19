<?php $pageTitle = 'Comparaison — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
    <h1>Comparaison multi-années</h1>
    <section class="filters">
      <label>Jeu de données
        <select id="dataset">
          <option value="recettes">Recettes</option>
          <option value="depenses">Dépenses</option>
        </select>
      </label>
      <label>Type <select id="type"><option value="">Tous</option></select></label>
    </section>
    <section class="filters" id="yearsRow">
      <!-- Year selectors will be injected here -->
      <button id="addYear" class="btn" title="Ajouter une annee pour comparer">+</button>
    </section>
    <section class="tables">
      <table id="tbl"><thead><tr id="theadRow"><th>Catégorie</th><th>Description</th><th>Type</th></tr></thead><tbody></tbody></table>
    </section>
    <section class="charts"><canvas id="chart"></canvas></section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      function fmt(x){ return (x||0).toLocaleString('fr-FR', {minimumFractionDigits:1, maximumFractionDigits:1}); }
      function years(){ return $.getJSON('api/years'); }
      function loadTypes(){
        const dataset = $('#dataset').val();
        const firstYear = $('.year-select').first().val();
        const url = dataset === 'recettes' ? 'api/recette-types' : 'api/depense-types';
        return $.getJSON(url, { annee: firstYear }).then(list => {
          const $t = $('#type'); $t.find('option:not([value=""])').remove();
          list.forEach(v => $t.append(`<option value="${v}">${v}</option>`));
        });
      }
      function selectedYears(){ return $('.year-select').map(function(){ return $(this).val(); }).get(); }
      function ensureHeader(){
        const years = selectedYears();
        const $tr = $('#theadRow');
        let html = '<th>Catégorie</th><th>Description</th><th>Type</th>';
        years.forEach(y => { html += `<th>${y}</th>`; });
        $tr.html(html);
      }
      let chart;
      function load(){
        const dataset = $('#dataset').val();
        const type = $('#type').val();
        const annees = selectedYears();
        if(annees.length === 0){ return; }
        ensureHeader();
        const url = dataset === 'recettes' ? 'api/recettes-compare-multi' : 'api/depenses-compare-multi';
        const params = $.param({ annee: annees, type });
        $.getJSON(url + '?' + params).then(rows => {
          const $tb = $('#tbl tbody'); $tb.empty();
          // Group rows by categorie (id)
          const byCat = {};
          rows.forEach(r => {
            const key = r.id + '|' + (r.type||'');
            if(!byCat[key]){ byCat[key] = { nom:r.nom, description:r.description, type:r.type||'', values:{} }; }
            byCat[key].values[r.annee] = Number(r.montant);
          });
          // Render table rows
          Object.values(byCat).forEach(obj => {
            let tds = `<td>${obj.nom}</td><td>${obj.description||''}</td><td>${obj.type}</td>`;
            selectedYears().forEach(y => { tds += `<td class="num">${fmt(obj.values[y]||0)}</td>`; });
            $tb.append('<tr>' + tds + '</tr>');
          });

          // Build chart: stacked bar per year, totals per type
          const totalsByTypeYear = {};
          Object.values(byCat).forEach(obj => {
            annees.forEach(y => {
              const k = obj.type || 'Autre';
              totalsByTypeYear[k] = totalsByTypeYear[k] || {};
              totalsByTypeYear[k][y] = (totalsByTypeYear[k][y] || 0) + (obj.values[y]||0);
            });
          });
          const labels = annees;
          const palette = ['#2d6cdf','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4'];
          const datasets = Object.keys(totalsByTypeYear).map((t,i)=>({
            label: t,
            data: labels.map(y => totalsByTypeYear[t][y]||0),
            backgroundColor: palette[i % palette.length]
          }));
          if(chart) chart.destroy();
          chart = new Chart(document.getElementById('chart'), {
            type: 'bar',
            data: { labels, datasets },
            options: { responsive:true, plugins:{ legend:{ position:'bottom' } }, scales:{ x:{ stacked:true }, y:{ stacked:true, beginAtZero:true } } }
          });
        }).fail(jq => console.error('compare-multi failed', jq.status, jq.responseText));
      }

      function addYearSelector(yearsList, value){
        const id = 'y' + Math.random().toString(36).slice(2,7);
        const $wrap = $('<div class="year-wrap" style="display:flex;gap:6px;align-items:end"></div>');
        const $label = $(`<label for="${id}">Année <select id="${id}" class="year-select"></select></label>`);
        const $remove = $('<button class="btn btn-danger" title="Retirer">-</button>').on('click', function(){ $wrap.remove(); load(); });
        yearsList.forEach(r => { $label.find('select').append(`<option>${r.annee}</option>`); });
        if(value){ $label.find('select').val(value); }
        $label.find('select').on('change', load);
        $wrap.append($label).append($remove);
        $('#yearsRow #addYear').before($wrap);
      }

      $(function(){
        let cachedYears = [];
        years().then(list => {
          cachedYears = list;
          // Preselect last and previous
          const last = list[list.length-1]?.annee;
          const prev = list[Math.max(0, list.length-2)]?.annee;
          addYearSelector(list, prev || last);
          addYearSelector(list, last);
          return loadTypes();
        }).then(load);

        $('#addYear').on('click', function(){ addYearSelector(cachedYears); load(); });
        $('#dataset').on('change', ()=> loadTypes().then(load));
        $('#type').on('change', load);
      });
    </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
