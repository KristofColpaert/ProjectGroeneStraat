$(window).load(function(){
    var primaryheight = window.innerHeight-65;
    var height = $('.contentwrapper').outerHeight();
    var minPadding = 0.05*primaryheight;
    if((height+(2*minPadding)) > primaryheight){
        $('#primary').css("padding",minPadding+"px 0px");
    }
    else{
        var padding = (primaryheight - height)/2;
        $('#primary').css("padding",padding+"px 0px");
    }
});