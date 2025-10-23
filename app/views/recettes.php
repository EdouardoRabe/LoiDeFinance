<?php $pageTitle = 'Recettes — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
    <link rel="stylesheet" href="assets/css/tooltip.css">
    <h1>Recettes</h1>
    <p class="section-desc">Parcourez les recettes publiques par type (fiscales, douanières, non fiscales, dons) et par catégorie. Les montants sont issus des estimations et prévisions de la Loi de Finances.</p>
    <section class="filters">
      <label>Année <select id="year"></select></label>
      <label>Type <select id="type"><option value="">Toutes</option></select></label>
    </section>
    <section class="tables">
      <table id="tbl"><thead><tr><th>Catégorie</th><th>Montant</th><th>Type</th></tr></thead><tbody></tbody></table>
    </section>
    <section class="charts"><canvas id="chart"></canvas></section>
    <div class="description-tooltip" id="tooltip"></div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="assets/js/tooltip.js"></script>
  <script src="assets/js/recettes.js"></script>
<?php include __DIR__ . '/partials/footer.php'; ?>
