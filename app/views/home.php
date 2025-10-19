<?php $pageTitle = 'Accueil — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>

  <section class="hero">
    <h1>Loi de Finances</h1>
    <p>Explorez les agrégats, recettes, dépenses et dispositions par année via une interface claire et rapide.</p>
    <div class="cta-row">
      <a class="btn" href="recettes">Explorer les recettes</a>
      <a class="btn" href="depenses">Explorer les dépenses</a>
      <a class="btn secondary" href="dispositions">Voir les dispositions fiscales</a>
    </div>
  </section>

  <section>
    <h2>En un coup d’œil</h2>
    <div class="cards" id="kpiContainer"></div>
  </section>

  <section>
    <p class="muted">Pour les filtres détaillés, tableaux et graphiques, utilisez les pages dédiées depuis la barre de navigation.</p>
  </section>

  <script>
    function fmt(x){ return (x||0).toLocaleString('fr-FR', {minimumFractionDigits:1, maximumFractionDigits:1}); }
    function showAjaxError(title, jqXHR){ console.error(title, jqXHR.status, jqXHR.responseText); }

    function loadYears(){
      return $.getJSON('api/years').then(list => list.map(r=>r.annee)).fail(jq=>showAjaxError('GET api/years failed', jq));
    }
    function loadKpis(annee){
      return $.getJSON('api/kpis', { annee }).then(d => {
        const $kc = $('#kpiContainer');
        $kc.empty();
        Object.entries(d.recettes||{}).forEach(([type, total]) => {
          $kc.append(`<div class="card"><h3>Recettes ${type}</h3><div>${fmt(total)}</div></div>`);
        });
        $kc.append(`<div class="card"><h3>Dépenses totales</h3><div>${fmt(d.depenses)}</div></div>`);
        $kc.append(`<div class="card"><h3>Déficit</h3><div>${d.deficit && d.deficit.montant_total!=null ? fmt(d.deficit.montant_total) : '—'}</div></div>`);
      }).fail(jq=>showAjaxError('GET api/kpis failed', jq));
    }
    $(function(){ loadYears().then(list => { const last = list && list.length ? list[list.length-1] : null; if(last) loadKpis(last); }); });
  </script>

<?php include __DIR__ . '/partials/footer.php'; ?>
