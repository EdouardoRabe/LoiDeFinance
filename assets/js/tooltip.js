/**
 * Affiche un tooltip personnalisé au survol
 * @param {Event} e - L'événement de la souris
 * @param {string} text - Le texte à afficher dans le tooltip
 */
function showTooltip(e, text) {
  const $tooltip = $('#tooltip');
  if (!text || text === '') {
    $tooltip.removeClass('show');
    return;
  }
  $tooltip.text(text).addClass('show');
  const x = e.pageX + 15;
  const y = e.pageY - 40;
  $tooltip.css({ left: x + 'px', top: y + 'px' });
}

/**
 * Cache le tooltip
 */
function hideTooltip() {
  $('#tooltip').removeClass('show');
}

/**
 * Attache les événements de tooltip à une cellule de catégorie
 * @param {jQuery} $cell - L'élément jQuery de la cellule
 */
function attachTooltipEvents($cell) {
  $cell.on('mouseenter', function(e) {
    showTooltip(e, $(this).data('desc'));
  })
  .on('mousemove', function(e) {
    showTooltip(e, $(this).data('desc'));
  })
  .on('mouseleave', hideTooltip);
}
