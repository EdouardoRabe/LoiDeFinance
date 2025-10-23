function fmt(x) {
  return (x || 0).toLocaleString('fr-FR', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
}

let chart;

function years() {
  return $.getJSON('api/years').fail(jq => console.error('api/years failed', jq.status, jq.responseText));
}

function loadTypes() {
  const annee = $('#year').val();
  return $.getJSON('api/recette-types', { annee }).then(list => {
    const $t = $('#type');
    $t.find('option:not([value=""])').remove();
    list.forEach(v => $t.append(`<option value="${v}">${v}</option>`));
  });
}

function load() {
  const y = $('#year').val(), type = $('#type').val();
  const $tb = $('#tbl tbody');
  $tb.empty();
  $.getJSON('api/recettes', { annee: y, type }).then(rows => {
    rows.forEach(r => {
      const $row = $(`<tr><td class="category-cell" data-desc="${r.description || ''}">${r.nom}</td><td class="num">${fmt(r.montant)}</td><td>${r.type}</td></tr>`);
      attachTooltipEvents($row.find('.category-cell'));
      $tb.append($row);
    });
    // Grouper par type pour le camembert
    const grouped = {};
    rows.forEach(r => grouped[r.type] = (grouped[r.type] || 0) + Number(r.montant));
    const labels = Object.keys(grouped);
    const data = Object.values(grouped);
    if (chart) chart.destroy();
    chart = new Chart(document.getElementById('chart'), {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: ['#2d6cdf', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
          borderWidth: 0,
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: { padding: 10 },
        plugins: {
          legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 10 } },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const total = ctx.dataset.data.reduce((a,b)=>a+Number(b||0),0);
                const val = Number(ctx.parsed) || 0;
                const pct = total ? (val*100/total) : 0;
                return `${ctx.label}: ${fmt(val)} (${pct.toFixed(1)}%)`;
              }
            }
          }
        },
        animation: { duration: 500, easing: 'easeOutQuart' }
      }
    });
  });
}

$(function() {
  years().then(list => {
    const $y = $('#year');
    list.forEach(r => {
      $y.append(`<option>${r.annee}</option>`);
    });
    if (list.length) {
      $y.val(list[list.length - 1].annee);
    }
    return loadTypes();
  }).then(load);
  $('#year,#type').on('change', load);
});
