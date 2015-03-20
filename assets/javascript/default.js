$(function() {
  $('.query').each(function() {
    $(this).attr('title', $(this).text());
    $(this).html('');
    star = $('<img />').attr('src', 'assets/images/star32.png');
    $(this).prepend(star);
    $(this).show();
  });
  $('.query').tooltipsy({
    offset: [20, 0]
  });
});
