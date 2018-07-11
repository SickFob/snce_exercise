$('#product_tags').val();

$('.badge-tag').click(function() { 
  $('#product_tags').tagsinput('add', $(this).children('.badge-info').text());
});