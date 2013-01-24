function listorgrid(el, event)
{
  event.preventDefault();
  var mode = el.hasClass('lg_grid');
  if (mode)
  {
    el.parent().find('.listorgridswitch .switch_but').removeClass('lg_grid');
    el.removeClass('lg_grid');
    el.find('ul').fadeOut("fast", function() { $(this).fadeIn("fast").removeClass("sw_view"); });
  }
  else
  {
    el.parent().find('.listorgridswitch .switch_but').addClass('lg_grid');
    el.addClass('lg_grid');
    el.find('ul').fadeOut("fast", function() {$(this).fadeIn("fast").addClass("sw_view");});
  }
  jQuery.ajax({
        type: 'POST',
        url: baseDir + 'modules/listorgridswitch/listorgridswitch.php',
        async: false,
        cache: false,
        type : "POST",
        dataType : "json",
        data: { listorgridajax: true, listorgridmode: (mode?0:1) },
        success: function(jsonData)
        {
          return;
        }
  });
  return false;
}

$(document).ready(function(){
  $("a.switch_but")
    .toggle(
      function( event ){ listorgrid($(this).parent().parent().find('.listorgridcanvas'), event); },
      function( event ){ listorgrid($(this).parent().parent().find('.listorgridcanvas'), event); }
    );
  $('.listorgridcanvas.lg_grid').find('ul').addClass('sw_view');
});