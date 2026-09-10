$('.btn-twitter').click(function(){
  //Twitter login form
  $('.twitterForm').submit();
});

//Character counter for twitter text area
$('#twitterShareField').keyup(function () {
  var max = 120;
  var len = $(this).val().length;
  if (len >= max) {
    $('#charNum').text(' you have reached the limit');
  } else {
    var char = max - len;
    $('#charNum').text(char + ' characters left');
  }
});