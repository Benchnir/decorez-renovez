window.onload = function() {
    preload_image('/public/images/map/map_1.png');
    preload_image('/public/images/map/map_2.png'); 
    preload_image('/public/images/map/map_3.png'); 
    preload_image('/public/images/map/map_4.png'); 
    preload_image('/public/images/map/map_5.png'); 
    preload_image('/public/images/map/map_6.png'); 
    preload_image('/public/images/map/map_7.png'); 
    preload_image('/public/images/map/map_8.png'); 
    preload_image('/public/images/map/map_9.png'); 
    preload_image('/public/images/map/map_10.png'); 
    preload_image('/public/images/map/map_11.png'); 
    preload_image('/public/images/map/map_12.png'); 
    preload_image('/public/images/map/map_13.png'); 
    preload_image('/public/images/map/map_14.png'); 
    preload_image('/public/images/map/map_15.png'); 
    preload_image('/public/images/map/map_16.png'); 
    preload_image('/public/images/map/map_17.png'); 
    preload_image('/public/images/map/map_18.png'); 
    preload_image('/public/images/map/map_19.png'); 
    preload_image('/public/images/map/map_20.png'); 
    preload_image('/public/images/map/map_21.png'); 
    preload_image('/public/images/map/map_22.png'); 
    preload_image('/public/images/map/map_23.png'); 
    preload_image('/public/images/map/map_24.png'); 
    preload_image('/public/images/map/map_25.png'); 
    preload_image('/public/images/map/map_26.png'); 
}

function preload_image(a){
    var b=new Image;
    b.src=a;
}
    
function change_image(d,c){
    var a=document.getElementById("area_image");
    var b=document.getElementById("regionName_"+c);
    a.style.backgroundImage="url('/public/images/map/map_"+c+".png')";
    b.style.textDecoration="underline";
    return true;
}
    
function hide_image(d,c){
    var a=document.getElementById("area_image");
    var b=document.getElementById("regionName_"+c);
    a.style.backgroundImage="url('/public/images/map/none.gif')";
    b.style.textDecoration="none";
    return true;
}
  
 
