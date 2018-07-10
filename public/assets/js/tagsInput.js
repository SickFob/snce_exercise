$('#product_tags').val();

$('.badge-tag').click(function() { 
  $('#product_tags').tagsinput('add', $(this).text());
});