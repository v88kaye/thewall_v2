function validateEmail($obj) {
  var regEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return regEx.test($obj.val());
}

function swapClass($obj, beforeClass, afterClass) {
  $obj.removeClass(beforeClass);
  $obj.addClass(afterClass);
}

function passwordsMatch() {
  return $('#password_confirmation').val() == $('#password').val();
}

function hasPresence($obj) {
  return $obj.val() !== "" && $obj.val() !== null;
}

function meetsLengthRequirements($obj, minLength) {
  return $obj.val().length > minLength;
}

function fail($obj) {
  alert($obj.attr("name") + " error in validation");
  
}

function success($obj) {
  swapClass($obj, 'fail', 'success');
}

function isnumber($obj) {
  return isNaN($obj.val());
}

function isDateValid($obj){
  return isNaN($obj.val());
}

function isGreaterDate($obj1,$obj2){
  if($obj1.val() >$obj2.val()) {
    return true;
  }
  else{
    return false;
  }
}

$(document).ready(function(){

  $("form").submit(function(){
    if(!isDateValid($("#dateFrom"))){
      fail($("#dateFrom"));
      return false;
    }
    if(!isDateValid($("#dateTo"))){
      fail($("#dateTo"));
      return false;
    }
    if(isGreaterDate($("#dateFrom"),$("#dateTo"))){
      fail($("#dateTo"));
      return false;
    }
  });

  $("input[type ='text']").keyup(function(){
    hasPresence($(this)) ? success($(this)) : fail($(this));
  });

  $('.spinner').keyup(function(){
    !isnumber($(this)) ? success($(this)) : fail($(this));
  });

  $('#email').keyup(function(){
    validateEmail($(this)) ? success($(this)) : fail($(this));
  });

  $('#password').keyup(function(){
    meetsLengthRequirements($(this), 7) ? success($(this)) : fail($(this));
  });

  $('#password_confirmation').keyup(function(){
    passwordsMatch() ? success($(this)) : fail($(this));
  });
});
