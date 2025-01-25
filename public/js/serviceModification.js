function refreshDisplay(id)
{
    $('#heure_txt_' + id).text($('#prix' + id).val() * id * 7);
}

window.onload = function() {
    $('#prix2').keyup(function(){
        refreshDisplay(2);
    });
    $('#prix10').keyup(function(){
        refreshDisplay(10);
    });
    $('#prix30').keyup(function(){
        refreshDisplay(30);
    });
}