jQuery(function($){
   $("#date").mask("99/99/9999");
   $("#date1").mask("99/99/9999");
   $('#spinner2').ace_spinner({value:0,min:0,max:999,step:1, touch_spinner: true, icon_up:'icon-caret-up', icon_down:'icon-caret-down'});
});