$(document).ready(function(){
    var primaryheight = window.innerHeight-65;
    var height = $('.contentwrapper').outerHeight();
    if(height > (window.innerHeight*0.15)){
        $("#primary").css("padding",(window.innerHeight*0.05)+"px 0px");
        $('#primary').css("min-height" , "0px");  
    }
    else{
            $('#primary').css("min-height", primaryheight+"px");   
            var dHeight = (primaryheight/2)-(height/2);
            $("#primary").css("padding",dHeight+"px 0px");
            $('#primary').css("min-height" , "0px");
    }
    
    
    
    
    if(primaryheight > (window.innerHeight*0.1)){
        
    }
    else{
                    
    }
    
    console.log(dHeight+"px");
});