<?php $pageTitle = 'Accueil — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>

  <section class="hero">
    <h1>🏛️ Loi de Finances</h1>
    <p>Plateforme officielle de consultation et d'analyse des données budgétaires. Explorez les recettes, dépenses, et dispositions fiscales de manière transparente et interactive.</p>
    <div class="cta-links">
      <a class="btn btn-primary" href="recettes">💰 Explorer les Recettes</a>
      <a class="btn btn-primary" href="depenses">💸 Explorer les Dépenses</a>
      <a class="btn btn-secondary" href="dispositions">📋 Dispositions Fiscales</a>
    </div>
  </section>

  <section class="kpis">
    <h2>📊 En un coup d'œil</h2>
    <p class="section-desc">Aperçu rapide des principaux indicateurs budgétaires de la dernière année disponible.</p>
    <div class="kpis-grid" id="kpiContainer"></div>
  </section>

  <section class="features">
    <h2>🔍 Explorez les Données</h2>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">💰</div>
        <h3>Recettes</h3>
        <p>Consultez les recettes budgétaires par type et catégorie avec des visualisations détaillées.</p>
        <a href="recettes" class="feature-link">Voir les recettes →</a>
      </div>
      <div class="feature-card">
        <div class="feature-icon">💸</div>
        <h3>Dépenses</h3>
        <p>Analysez les dépenses publiques ventilées par secteur avec des graphiques interactifs.</p>
        <a href="depenses" class="feature-link">Voir les dépenses →</a>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🔄</div>
        <h3>Comparaison</h3>
        <p>Comparez les budgets sur plusieurs années pour identifier les tendances et évolutions.</p>
        <a href="comparaison" class="feature-link">Comparer les années →</a>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📋</div>
        <h3>Dispositions Fiscales</h3>
        <p>Accédez aux textes et dispositions fiscales pour chaque loi de finances.</p>
        <a href="dispositions" class="feature-link">Consulter →</a>
      </div>
    </div>
  </section>

  <section class="transparency">
    <div class="transparency-content">
      <h3>⚖️ Transparence & Accessibilité</h3>
      <p>Notre mission est de rendre les données budgétaires publiques accessibles à tous les citoyens. Toutes les informations présentées proviennent de sources officielles et sont mises à jour régulièrement.</p>
    </div>
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
        
        // Calculer le total des recettes
        let totalRecettes = 0;
        Object.entries(d.recettes||{}).forEach(([type, total]) => {
          totalRecettes += parseFloat(total) || 0;
        });
        
        // Total des recettes (première carte principale)
        $kc.append(`
          <div class="kpi-card kpi-main">
            <h3>💰 Total Recettes</h3>
            <div class="kpi-value positive">${fmt(totalRecettes)}</div>
          </div>
        `);
        
        // Dépenses (deuxième carte principale)
        const depenses = parseFloat(d.depenses) || 0;
        $kc.append(`
          <div class="kpi-card kpi-main">
            <h3>💸 Dépenses Totales</h3>
            <div class="kpi-value warning">${fmt(depenses)}</div>
          </div>
        `);
        
        // Déficit (troisième carte principale)
        const deficitVal = d.deficit && d.deficit.montant_total != null ? parseFloat(d.deficit.montant_total) : null;
        if (deficitVal !== null) {
          const deficitClass = deficitVal < 0 ? 'negative' : 'positive';
          $kc.append(`
            <div class="kpi-card kpi-main">
              <h3>📊 Déficit</h3>
              <div class="kpi-value ${deficitClass}">${fmt(Math.abs(deficitVal))}</div>
            </div>
          `);
        } else {
          $kc.append(`
            <div class="kpi-card kpi-main">
              <h3>📊 Déficit</h3>
              <div class="kpi-value">—</div>
            </div>
          `);
        }
      }).fail(jq=>showAjaxError('GET api/kpis failed', jq));
    }
    $(function(){ loadYears().then(list => { const last = list && list.length ? list[list.length-1] : null; if(last) loadKpis(last); }); });
  </script>

<?php include __DIR__ . '/partials/footer.php'; ?>
