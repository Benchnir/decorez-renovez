function valider(id_comment)
{
    var txt = 'Etes-vous sur de vouloir valider ce commentaire ?';

    if (confirm(txt))
    {
        $.ajax({
            type: 'POST',
            url: '/admin/commentaire/valider',
            data: {
                id_comment:id_comment
            },
            success: function(response) {
                if (response == '0') {
                    $('#mini_comment-'+id_comment).fadeOut();
                }
                else {
                    alert(response);
                }
            }
        });	
    }
    else
    {
        return false;
    }
    return true;
}

function refuser(id_comment)
{
    if (id_comment == null)
        return false;
    
    var txt = 'Etes-vous sur de vouloir refuser ce commentaire ?';
    var reason = $("input[type=text][name=reason-"+ id_comment+ "]").val();

    if (confirm(txt))
    {
        $.ajax({
            type: 'POST',
            url: '/admin/commentaire/refuser',
            data: {
                id_comment:id_comment,
                reason:reason
            },
            success: function(response) {
                if (response == '0') {
                    $('#mini_comment-'+id_comment).fadeOut();
                }
                else {
                    alert(response);
                }
            }
        });	
    }
    else
    {
        return false;
    }
    return true;
}