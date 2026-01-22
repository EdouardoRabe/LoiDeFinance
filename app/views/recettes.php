<?php $pageTitle = (function_exists('t') ? t('page.recettes.title') : 'Recettes') . ' — ' . (function_exists('t') ? t('app.title') : 'Loi de Finances'); include __DIR__ . '/partials/header.php'; ?>
    <link rel="stylesheet" href="assets/css/tooltip.css">
    <h1><?= htmlspecialchars(function_exists('t') ? t('page.recettes.title') : 'Recettes') ?></h1>
    <p class="section-desc"><?= htmlspecialchars(function_exists('t') ? t('desc.recettes') : 'Parcourez les recettes publiques par type et par catégorie.') ?></p>
    <section class="filters">
      <label><?= htmlspecialchars(function_exists('t') ? t('labels.year') : 'Année') ?> <select id="year"></select></label>
      <label><?= htmlspecialchars(function_exists('t') ? t('labels.type') : 'Type') ?> <select id="type"><option value=""><?= htmlspecialchars(function_exists('t') ? t('labels.all_f') : 'Toutes') ?></option></select></label>
    </section>
    <section class="tables">
      <table id="tbl"><thead><tr><th><?= htmlspecialchars(function_exists('t') ? t('labels.category') : 'Catégorie') ?></th><th><?= htmlspecialchars(function_exists('t') ? t('labels.amount') : 'Montant') ?></th><th><?= htmlspecialchars(function_exists('t') ? t('labels.type') : 'Type') ?></th></tr></thead><tbody></tbody></table>
    </section>
    <section class="charts"><canvas id="chart"></canvas></section>
    <div class="description-tooltip" id="tooltip"></div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="assets/js/tooltip.js"></script>
  <script src="assets/js/recettes.js"></script>
<?php include __DIR__ . '/partials/footer.php'; ?>
