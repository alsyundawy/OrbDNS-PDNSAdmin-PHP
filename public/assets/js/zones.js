'use strict';
(function ($) {
  function esc(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }
  function checkPdnsStatus() {
    $.getJSON('/api/status').done(function (res) {
      if (res.success) {
        $('#pdns-status').removeClass('text-bg-secondary text-bg-danger').addClass('text-bg-success').text('PDNS OK');
      } else {
        $('#pdns-status').removeClass('text-bg-secondary text-bg-success').addClass('text-bg-danger').text('PDNS Down');
      }
    }).fail(function () {
      $('#pdns-status').removeClass('text-bg-secondary text-bg-success').addClass('text-bg-danger').text('PDNS Down');
    });
  }
  function loadZones() {
    $.getJSON('/api/zones').done(function (res) {
      if (!res.success || !Array.isArray(res.data)) {
        $('#zones-table-body').html('<tr><td colspan="4">Failed to load zones</td></tr>');
        return;
      }
      if (res.data.length === 0) {
        $('#zones-table-body').html('<tr><td colspan="4">No zones found</td></tr>');
        return;
      }
      const rows = res.data.map(function (z) {
        return '<tr><td>' + esc((z.name || '').replace(/\.$/, '')) + '</td><td>' + esc(z.kind || '') + '</td><td>' + esc(String(z.serial || '—')) + '</td><td><span class="badge text-bg-info">cached</span></td></tr>';
      }).join('');
      $('#zones-table-body').html(rows);
    }).fail(function () {
      $('#zones-table-body').html('<tr><td colspan="4">Failed to load zones</td></tr>');
    });
  }
  $(function () { checkPdnsStatus(); loadZones(); });
})(jQuery);
