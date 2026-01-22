<?php $pageTitle = (function_exists('t') ? t('page.dispositions.title') : 'Dispositions fiscales') . ' — ' . (function_exists('t') ? t('app.title') : 'Loi de Finances'); include __DIR__ . '/partials/header.php'; ?>
    <h1><?= htmlspecialchars(function_exists('t') ? t('page.dispositions.title') : 'Dispositions fiscales') ?></h1>
    <section class="filters">
      <label><?= htmlspecialchars(function_exists('t') ? t('labels.year') : 'Année') ?> <select id="year"></select></label>
    </section>
    <section class="tables">
      <table id="tbl"><thead><tr><th><?= htmlspecialchars(function_exists('t') ? t('labels.type') : 'Type') ?></th><th><?= htmlspecialchars(function_exists('t') ? t('labels.description') : 'Description') ?></th></tr></thead><tbody></tbody></table>
    </section>
  <script>
    function load(){ const annee=$('#year').val();
      $.getJSON('api/dispositions',{annee}).then(rows=>{
        const $tb=$('#tbl tbody'); $tb.empty();
        rows.forEach(r=> $tb.append(`<tr><td>${r.type}</td><td>${r.description}</td></tr>`));
      }).fail(jq=>console.error('api/dispositions failed', jq.status, jq.responseText));
    }
    function years(){ return $.getJSON('api/years').fail(jq=>console.error('api/years failed', jq.status, jq.responseText)); }
    $(function(){ years().then(list=>{const $y=$('#year');list.forEach(r=>$y.append(`<option>${r.annee}</option>`));$y.val(list[list.length-1].annee); load();});
      $('#year').on('change',load);
    });
  </script>
<?php include __DIR__ . '/partials/footer.php'; ?>
