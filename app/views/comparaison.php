<?php $pageTitle = 'Comparaison — Loi de Finances'; include __DIR__ . '/partials/header.php'; ?>
    <link rel="stylesheet" href="assets/css/tooltip.css">
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
      <table id="tbl"><thead><tr id="theadRow"><th>Catégorie</th><th>Type</th></tr></thead><tbody></tbody></table>
    </section>
    <section class="charts"><canvas id="chart"></canvas></section>
    <div class="description-tooltip" id="tooltip"></div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/tooltip.js"></script>
    <script src="assets/js/comparaison.js"></script>
<?php include __DIR__ . '/partials/footer.php'; ?>
