$(document).foundation();

$(document).ready(function(){

    $('.horsforfaitreset').click(function(){
       $(":input[type='text']", "form#saisiehorsforfait").val('');
    });

    $('button.changeMonth').click(function(){
        var month = $("select[name='month']").val();
        var url = window.location.href;
        url = url.split('/');
        month = '/'+month.split('/').reverse().join('');
        console.log(url);
        if(url.length > 5){
            delete url[url.length--];
            return window.location.href = url.join('/')+month;
        }
        return window.location.href = url.join('/')+month;
    });

    $('.deletehorsforfait').click(function(){
        return confirm("Etes vous sûr de vouloir supprimer ce frais hors forfait ?");
    })

});
