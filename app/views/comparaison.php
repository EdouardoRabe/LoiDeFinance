<?php $pageTitle = (function_exists('t') ? t('page.comparaison.title') : 'Comparaison multi-années') . ' — ' . (function_exists('t') ? t('app.title') : 'Loi de Finances'); include __DIR__ . '/partials/header.php'; ?>
    <link rel="stylesheet" href="assets/css/tooltip.css">
    <h1><?= htmlspecialchars(function_exists('t') ? t('page.comparaison.title') : 'Comparaison multi-années') ?></h1>
    <section class="filters">
      <label><?= htmlspecialchars(function_exists('t') ? t('labels.dataset') : 'Jeu de données') ?>
        <select id="dataset">
          <option value="recettes"><?= htmlspecialchars(function_exists('t') ? t('page.recettes.title') : 'Recettes') ?></option>
          <option value="depenses"><?= htmlspecialchars(function_exists('t') ? t('page.depenses.title') : 'Dépenses') ?></option>
        </select>
      </label>
      <label><?= htmlspecialchars(function_exists('t') ? t('labels.type') : 'Type') ?> <select id="type"><option value=""><?= htmlspecialchars(function_exists('t') ? t('labels.all_m') : 'Tous') ?></option></select></label>
    </section>
    <section class="filters" id="yearsRow">
      <!-- Year selectors will be injected here -->
      <button id="addYear" class="btn" title="<?= htmlspecialchars(function_exists('t') ? t('labels.add_year') : 'Ajouter une année') ?>">+</button>
    </section>
    <section class="tables">
      <table id="tbl"><thead><tr id="theadRow"><th><?= htmlspecialchars(function_exists('t') ? t('labels.category') : 'Catégorie') ?></th><th><?= htmlspecialchars(function_exists('t') ? t('labels.type') : 'Type') ?></th></tr></thead><tbody></tbody></table>
    </section>
    <section class="charts"><canvas id="chart"></canvas></section>
    <div class="description-tooltip" id="tooltip"></div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/tooltip.js"></script>
    <script src="assets/js/comparaison.js"></script>
<?php include __DIR__ . '/partials/footer.php'; ?>
