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
      <a class="brand" href="<?= htmlspecialchars($base) ?>">Loi de Finances</a>
      <button class="nav-toggle" aria-label="Toggle navigation" onclick="document.querySelector('.nav-links').classList.toggle('open')">☰</button>
      <div class="nav-links">
        <a href="<?= htmlspecialchars($base) ?>">Accueil</a>
        <a href="<?= htmlspecialchars($base) ?>recettes">Recettes</a>
        <a href="<?= htmlspecialchars($base) ?>depenses">Dépenses</a>
        <a href="<?= htmlspecialchars($base) ?>dispositions">Dispositions</a>
        <a href="<?= htmlspecialchars($base) ?>comparaison">Comparaison</a>
      </div>
    </nav>
  </header>
  <main class="container">
