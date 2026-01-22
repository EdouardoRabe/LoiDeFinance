<?php $pageTitle = (function_exists('t') ? t('nav.home') : 'Accueil') . ' — ' . (function_exists('t') ? t('app.title') : 'Loi de Finances'); include __DIR__ . '/partials/header.php'; ?>

  <section class="hero">
    <h1><?= htmlspecialchars(function_exists('t') ? t('home.title') : 'Loi de Finances') ?></h1>
    <p><?= htmlspecialchars(function_exists('t') ? t('home.subtitle') : "Plateforme officielle de consultation et d'analyse des données budgétaires. Explorez les recettes, dépenses, et dispositions fiscales de manière transparente et interactive.") ?></p>
    <div class="cta-links">
      <a class="btn btn-primary" href="recettes"><?= htmlspecialchars(function_exists('t') ? t('home.cta.recettes') : 'Explorer les Recettes') ?></a>
      <a class="btn btn-primary" href="depenses"><?= htmlspecialchars(function_exists('t') ? t('home.cta.depenses') : 'Explorer les Dépenses') ?></a>
      <a class="btn btn-secondary" href="dispositions"><?= htmlspecialchars(function_exists('t') ? t('home.cta.dispositions') : 'Dispositions Fiscales') ?></a>
    </div>
  </section>

  <section class="kpis">
    <h2><?= htmlspecialchars(function_exists('t') ? t('home.kpis.title') : "En un coup d'œil") ?></h2>
    <p class="section-desc"><?= htmlspecialchars(function_exists('t') ? t('home.kpis.desc') : 'Aperçu rapide des principaux indicateurs budgétaires de la dernière année disponible.') ?></p>
    <div class="kpis-grid" id="kpiContainer"></div>
  </section>

  <section class="features">
    <h2><?= htmlspecialchars(function_exists('t') ? t('home.features.title') : 'Explorez les Données') ?></h2>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon"></div>
        <h3><?= htmlspecialchars(function_exists('t') ? t('home.features.recettes') : 'Recettes') ?></h3>
        <p><?= htmlspecialchars(function_exists('t') ? t('home.features.recettes.desc') : 'Consultez les recettes budgétaires par type et catégorie avec des visualisations détaillées.') ?></p>
        <a href="recettes" class="feature-link"><?= htmlspecialchars(function_exists('t') ? t('home.features.recettes.link') : 'Voir les recettes →') ?></a>
      </div>
      <div class="feature-card">
        <div class="feature-icon"></div>
        <h3><?= htmlspecialchars(function_exists('t') ? t('home.features.depenses') : 'Dépenses') ?></h3>
        <p><?= htmlspecialchars(function_exists('t') ? t('home.features.depenses.desc') : 'Analysez les dépenses publiques ventilées par secteur avec des graphiques interactifs.') ?></p>
        <a href="depenses" class="feature-link"><?= htmlspecialchars(function_exists('t') ? t('home.features.depenses.link') : 'Voir les dépenses →') ?></a>
      </div>
      <div class="feature-card">
        <div class="feature-icon"></div>
        <h3><?= htmlspecialchars(function_exists('t') ? t('home.features.comparaison') : 'Comparaison') ?></h3>
        <p><?= htmlspecialchars(function_exists('t') ? t('home.features.comparaison.desc') : 'Comparez les budgets sur plusieurs années pour identifier les tendances et évolutions.') ?></p>
        <a href="comparaison" class="feature-link"><?= htmlspecialchars(function_exists('t') ? t('home.features.comparaison.link') : 'Comparer les années →') ?></a>
      </div>
      <div class="feature-card">
        <div class="feature-icon"></div>
        <h3><?= htmlspecialchars(function_exists('t') ? t('home.features.dispositions') : 'Dispositions Fiscales') ?></h3>
        <p><?= htmlspecialchars(function_exists('t') ? t('home.features.dispositions.desc') : 'Accédez aux textes et dispositions fiscales pour chaque loi de finances.') ?></p>
        <a href="dispositions" class="feature-link"><?= htmlspecialchars(function_exists('t') ? t('home.features.dispositions.link') : 'Consulter →') ?></a>
      </div>
    </div>
  </section>

  <section class="transparency">
    <div class="transparency-content">
      <h3><?= htmlspecialchars(function_exists('t') ? t('home.transparency.title') : 'Transparence & Accessibilité') ?></h3>
      <p><?= htmlspecialchars(function_exists('t') ? t('home.transparency.desc') : 'Notre mission est de rendre les données budgétaires publiques accessibles à tous les citoyens. Toutes les informations présentées proviennent de sources officielles et sont mises à jour régulièrement.') ?></p>
    </div>
  </section>

  <script>
    const KPI_LABELS = {
      totalRecettes: <?= json_encode(function_exists('t') ? t('kpi.total_recettes') : 'Total Recettes') ?>,
      depensesTotales: <?= json_encode(function_exists('t') ? t('kpi.depenses_totales') : 'Dépenses Totales') ?>,
      deficit: <?= json_encode(function_exists('t') ? t('kpi.deficit') : 'Déficit') ?>
    };
    function fmt(x){ return (x||0).toLocaleString('fr-FR', {minimumFractionDigits:1, maximumFractionDigits:1}); }
    function showAjaxError(title, jqXHR){ console.error(title, jqXHR.status, jqXHR.responseText); }

    function loadYears(){
      return $.getJSON('api/years').then(list => list.map(r=>r.annee)).fail(jq=>showAjaxError('GET api/years failed', jq));
    }
    function hasKpiData(d){
      const hasRecettes = d && d.recettes && Object.keys(d.recettes).length > 0;
      const hasDepenses = d && Number(d.depenses||0) > 0;
      const hasDeficit = d && d.deficit && (d.deficit.montant_total !== null && d.deficit.montant_total !== undefined);
      return hasRecettes || hasDepenses || hasDeficit;
    }
    function pickLatestYearWithData(years){
      const tryYear = (idx)=>{
        if(idx < 0) return Promise.resolve(null);
        const y = years[idx];
        return $.getJSON('api/kpis', { annee: y }).then(d => hasKpiData(d) ? y : tryYear(idx-1)).catch(()=> tryYear(idx-1));
      };
      return tryYear(years.length-1);
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
            <h3>${KPI_LABELS.totalRecettes}</h3>
            <div class="kpi-value positive">${fmt(totalRecettes)}</div>
          </div>
        `);
        
        // Dépenses (deuxième carte principale)
        const depenses = parseFloat(d.depenses) || 0;
        $kc.append(`
          <div class="kpi-card kpi-main">
            <h3>${KPI_LABELS.depensesTotales}</h3>
            <div class="kpi-value warning">${fmt(depenses)}</div>
          </div>
        `);
        
        // Déficit (troisième carte principale)
        const deficitVal = d.deficit && d.deficit.montant_total != null ? parseFloat(d.deficit.montant_total) : null;
        if (deficitVal !== null) {
          const deficitClass = deficitVal < 0 ? 'negative' : 'positive';
          $kc.append(`
            <div class="kpi-card kpi-main">
              <h3>${KPI_LABELS.deficit}</h3>
              <div class="kpi-value ${deficitClass}">${fmt(Math.abs(deficitVal))}</div>
            </div>
          `);
        } else {
          $kc.append(`
            <div class="kpi-card kpi-main">
              <h3>${KPI_LABELS.deficit}</h3>
              <div class="kpi-value">—</div>
            </div>
          `);
        }
      }).fail(jq=>showAjaxError('GET api/kpis failed', jq));
    }
    $(function(){
      loadYears().then(list => pickLatestYearWithData(list)).then(y => { if(y) loadKpis(y); });
    });
  </script>

<?php include __DIR__ . '/partials/footer.php'; ?>
