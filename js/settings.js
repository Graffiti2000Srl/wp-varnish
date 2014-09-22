(function ($) {
  $(function () {
    var $tables = $('.wpva_table');

    $tables
      .on('click', 'input[name=add]', function (e) {
        var $button = $(e.target);
        var $table = $button.parents('table');
        var $tbody = $table.find('tbody');
        var $first_line = $tbody.find('tr:first-child');

        $first_line
          .clone()
          .appendTo($tbody)
          .find('input:not([type=button]), select').each(function () {
            var $input = $(this);
            var name = $input.attr('name');
            var index = $input.parents('tr').index();

            name = name.replace(/\[\d+]/, '[' + index + ']');

            $input
              .val('')
              .attr('name', name)
              .attr('id', name);
          });
      })
      .on('click', 'input[type=button]:not([name=add])', function (e) {
        var $button = $(e.target);
        var $tr = $button.parents('tr');

        $tr.remove();
      });
  });
}(jQuery));