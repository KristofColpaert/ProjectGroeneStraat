$(document).ready(function(){
    var primaryheight = window.innerHeight-65;
    $('#primary').css("min-height", primaryheight+"px");
    var height = $('.contentwrapper').outerHeight();
    primaryheight = $('#primary').outerHeight();
    console.log(primaryheight);
    var dHeight = (primaryheight/2)-(height/2);
    $("#primary").css("padding",dHeight+"px 0px");
    $('#primary').css("min-height" , "0px");
    console.log(dHeight+"px");
});