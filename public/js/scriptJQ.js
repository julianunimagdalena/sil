$(document).ready(function(){
    
    $('.btr-primary-inverted').hover(function(){}, function()
    {
        $(this).css('transition', '0.5s');
    });
    
    //Add Hover effect to menus
    jQuery('ul.nav li.dropdown').hover(function() {
      jQuery(this).find('.dropdown-menu').stop(true, true).delay(50).fadeIn();
    }, function() {
      jQuery(this).find('.dropdown-menu').stop(true, true).delay(50).fadeOut();
    });
    
    $('#fecha_inicio_acta').change(function(){
      var fecha_inicio_acta = moment($(this).val());
      var fecha_fin_acta = fecha_inicio_acta.add('6', 'M');
      $('#fecha_fin_acta').val(fecha_fin_acta.format('YYYY-MM-DD'));
    })
});