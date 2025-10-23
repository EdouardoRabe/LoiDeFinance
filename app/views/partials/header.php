<?php
  $title = isset($pageTitle) ? $pageTitle : 'Loi de Finances';
  // Compute base path robustly for subfolder deployments (e.g., /LoiDeFinance/)
  $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
  $base = ($scriptDir === '' || $scriptDir === '.') ? '/' : ($scriptDir . '/');
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="assets/css/main.css">
  <script src="assets/js/jquery.min.js"></script>
</head>
<body>
  <header class="site-header">
    <nav class="navbar">
      <a class="brand" href="<?= htmlspecialchars($base) ?>">
        <img src="<?= htmlspecialchars($base) ?>assets/images.png" alt="Loi de Finances" class="brand-logo">
      </a>
      <button class="nav-toggle" aria-label="Toggle navigation" onclick="document.querySelector('.nav-links').classList.toggle('open')">☰</button>
      <div class="nav-links">
        <a href="<?= htmlspecialchars($base) ?>">Accueil</a>
        <a href="<?= htmlspecialchars($base) ?>recettes">Recettes</a>
        <a href="<?= htmlspecialchars($base) ?>depenses">Dépenses</a>
        <a href="<?= htmlspecialchars($base) ?>dispositions">Dispositions</a>
        <a href="<?= htmlspecialchars($base) ?>comparaison">Comparaison</a>
        <a href="<?= htmlspecialchars($base) ?>secteurs">Secteurs</a>
        <a href="<?= htmlspecialchars($base) ?>projets">Projets</a>
        <a href="<?= htmlspecialchars($base) ?>dette">Dette</a>
        <a href="<?= htmlspecialchars($base) ?>postes">Postes</a>
        <a href="<?= htmlspecialchars($base) ?>indicateurs">Indicateurs</a>
        <a href="<?= htmlspecialchars($base) ?>glossaire">Glossaire</a>
        <a class="pdf-link" href="<?= htmlspecialchars($base) ?>assets/BDC-LF-2025-VF.pdf" target="_blank" rel="noopener" title="Ouvrir le PDF Loi de Finances 2025" aria-label="Ouvrir le PDF Loi de Finances 2025">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M6 2h7l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <path d="M13 2v5h5" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <path d="M8 15h6M8 12h8M8 18h8" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </a>
      </div>
    </nav>
  </header>
  <main class="container">
