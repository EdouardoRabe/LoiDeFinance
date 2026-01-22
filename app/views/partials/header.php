<?php
  $title = isset($pageTitle) ? $pageTitle : 'Loi de Finances';
  // Compute base path robustly for subfolder deployments (e.g., /LoiDeFinance/)
  $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
  $base = ($scriptDir === '' || $scriptDir === '.') ? '/' : ($scriptDir . '/');
?>
<!doctype html>
<html lang="<?= htmlspecialchars(function_exists('current_lang') ? current_lang() : 'fr') ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="assets/css/main.css">
  <script src="assets/js/jquery.min.js"></script>
  <script>
    window.I18N_LABELS = {
      year: <?= json_encode(function_exists('t') ? t('labels.year') : 'Année') ?>,
      category: <?= json_encode(function_exists('t') ? t('labels.category') : 'Catégorie') ?>,
      type: <?= json_encode(function_exists('t') ? t('labels.type') : 'Type') ?>,
      remove: <?= json_encode('Retirer') ?>
    };
  </script>
</head>
<body>
  <header class="site-header">
    <nav class="navbar">
      <a class="brand" href="<?= htmlspecialchars($base) ?>">
        <img src="<?= htmlspecialchars($base) ?>assets/images.png" alt="<?= htmlspecialchars(function_exists('t') ? t('app.title') : 'Loi de Finances') ?>" class="brand-logo">
      </a>
      <button class="nav-toggle" aria-label="Toggle navigation" onclick="document.querySelector('.nav-links').classList.toggle('open')">☰</button>
      <div class="nav-links">
        <a href="<?= htmlspecialchars($base) ?>"><?= htmlspecialchars(function_exists('t') ? t('nav.home') : 'Accueil') ?></a>
        <a href="<?= htmlspecialchars($base) ?>recettes"><?= htmlspecialchars(function_exists('t') ? t('nav.recettes') : 'Recettes') ?></a>
        <a href="<?= htmlspecialchars($base) ?>depenses"><?= htmlspecialchars(function_exists('t') ? t('nav.depenses') : 'Dépenses') ?></a>
        <a href="<?= htmlspecialchars($base) ?>dispositions"><?= htmlspecialchars(function_exists('t') ? t('nav.dispositions') : 'Dispositions') ?></a>
        <a href="<?= htmlspecialchars($base) ?>comparaison"><?= htmlspecialchars(function_exists('t') ? t('nav.comparaison') : 'Comparaison') ?></a>
        <a href="<?= htmlspecialchars($base) ?>secteurs"><?= htmlspecialchars(function_exists('t') ? t('nav.secteurs') : 'Secteurs') ?></a>
        <a href="<?= htmlspecialchars($base) ?>projets"><?= htmlspecialchars(function_exists('t') ? t('nav.projets') : 'Projets') ?></a>
        <a href="<?= htmlspecialchars($base) ?>dette"><?= htmlspecialchars(function_exists('t') ? t('nav.dette') : 'Dette') ?></a>
        <a href="<?= htmlspecialchars($base) ?>postes"><?= htmlspecialchars(function_exists('t') ? t('nav.postes') : 'Postes') ?></a>
        <a href="<?= htmlspecialchars($base) ?>indicateurs"><?= htmlspecialchars(function_exists('t') ? t('nav.indicateurs') : 'Indicateurs') ?></a>
        <a href="<?= htmlspecialchars($base) ?>glossaire"><?= htmlspecialchars(function_exists('t') ? t('nav.glossaire') : 'Glossaire') ?></a>
        <a class="pdf-link" href="<?= htmlspecialchars($base) ?>assets/BDC-LF-2025-VF.pdf" target="_blank" rel="noopener" title="<?= htmlspecialchars(function_exists('t') ? t('nav.pdf_title') : 'Ouvrir le PDF Loi de Finances 2025') ?>" aria-label="<?= htmlspecialchars(function_exists('t') ? t('nav.pdf_title') : 'Ouvrir le PDF Loi de Finances 2025') ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M6 2h7l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <path d="M13 2v5h5" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <path d="M8 15h6M8 12h8M8 18h8" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </a>
        <form action="<?= htmlspecialchars($base) ?>lang" method="get" style="margin-left:8px;">
          <select name="lang" onchange="this.form.submit()" aria-label="Language selector">
            <option value="fr" <?= (function_exists('current_lang') && current_lang()==='fr')?'selected':''; ?>>🇫🇷 FR</option>
            <option value="mg" <?= (function_exists('current_lang') && current_lang()==='mg')?'selected':''; ?>>🇲🇬 MG</option>
          </select>
        </form>
      </div>
    </nav>
  </header>
  <main class="container">
