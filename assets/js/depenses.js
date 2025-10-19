function fmt(x) {
  return (x || 0).toLocaleString('fr-FR', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
}

let chart;

function years() {
  return $.getJSON('api/years').fail(jq => console.error('api/years failed', jq.status, jq.responseText));
}

function loadTypes() {
  const annee = $('#year').val();
  return $.getJSON('api/depense-types', { annee }).then(list => {
    const $t = $('#type');
    $t.find('option:not([value=""])').remove();
    list.forEach(v => $t.append(`<option value="${v}">${v}</option>`));
  });
}

function load() {
  const y = $('#year').val(), type = $('#type').val();
  $.getJSON('api/depenses', { annee: y, type }).then(rows => {
    const $tb = $('#tbl tbody');
    $tb.empty();
    rows.forEach(r => {
      const $row = $(`<tr><td class="category-cell" data-desc="${r.description || ''}">${r.nom}</td><td class="num">${fmt(r.montant)}</td><td>${r.type}</td></tr>`);
      attachTooltipEvents($row.find('.category-cell'));
      $tb.append($row);
    });
    const grouped = {};
    rows.forEach(r => grouped[r.type] = (grouped[r.type] || 0) + Number(r.montant));
    const labels = Object.keys(grouped), data = Object.values(grouped);
    if (chart) chart.destroy();
    chart = new Chart(document.getElementById('chart'), {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: ['#2d6cdf', '#10b981', '#f59e0b', '#ef4444']
        }]
      },
      options: { responsive: true }
    });
  }).fail(jq => console.error('api/depenses failed', jq.status, jq.responseText));
}

$(function() {
  years().then(list => {
    const $y = $('#year');
    list.forEach(r => $y.append(`<option>${r.annee}</option>`));
    $y.val(list[list.length - 1].annee);
    return loadTypes();
  }).then(load);
  $('#year').on('change', () => loadTypes().then(load));
  $('#type').on('change', load);
});
