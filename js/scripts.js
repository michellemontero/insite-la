
$('html').mousemove(function(e){

  var wx = $(window).width();
  var wy = $(window).height();

  var x = e.pageX - this.offsetLeft;
  var y = e.pageY - 0;

  var newx = x - wx/2;
  var newy = y - 0;

/*   $('spanasas').text(newx + ", " + newy); */

  $('.wrapper_parallax div').each(function(){
    var speed = $(this).attr('data-speed');
    if($(this).attr('data-revert')) speed *= -1;
    TweenMax.to($(this), 1, {x: (1 - newx*speed)});

  });

});

  $('#nav-icon3').click(function(){
    $(this).toggleClass('open');
    $('#logo-negativo').toggleClass('fade-in');
  });

  $('.sidebar-wrapper').on('shown.bs.collapse', function (e) {
console.log("shown");
});

var form = document.getElementById('contactForm');

function validateRequired() {
  var result = true;

  $('.input-required').each(function () {
    var $self = $(this);
    if (!$self.val()) {
      $self.siblings('.help-block').text($self.attr('data-error'));
      $self.parent().addClass('with-errors');
      result = false;
    } else {
      $self.siblings('.help-block').text('');
      $self.parent().removeClass('with-errors');
    }
  });

  return result;
}

function validateEmail() {
  var emailPattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  var $inputEmail = $('.input-email');

  if (!emailPattern.test($inputEmail.val())) {
    $inputEmail.siblings('.help-block').text($inputEmail.attr('data-error'));
    $inputEmail.parent().addClass('with-errors');
    return false;
  }
  return true;
}

function validateRecaptcha () {
  var result = true;

  var response = grecaptcha.getResponse();
  var $inputCaptcha = $('.input-captcha');
  
  if(response.length == 0) 
  { 
    $inputCaptcha.siblings('.help-block').text($inputCaptcha.attr('data-error'));
    $inputCaptcha.parent().addClass('with-errors');
    return false;
  }

    return result;
}


function recaptchaCallback(){
  var $inputCaptcha = $('.input-captcha');

  $inputCaptcha.siblings('.help-block').text('');
  $inputCaptcha.parent().removeClass('with-errors');

}

var submitHandler = function (event) {

  event.preventDefault();

  var $form = $(this);

  validateRecaptcha();

  //var $form = $('#contactForm');

  $('.input-required').change(validateRequired);
  $('.input-email').change(validateEmail);

  if (validateRequired() && validateEmail() && validateRecaptcha()) {
      var data = $form.serialize();
      $.post('contacto.php', data)
        .done(
          function(data) {
            $('#form-message').html(data);
            $('#form-message').removeClass('d-none');
            $form.trigger("reset");
          }
        );
  }
}

form.addEventListener("submit", submitHandler, true);


