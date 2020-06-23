$(document).ready(function () {
    $('#connexion').on('submit', function (e) { 
        e.preventDefault();

        var valeurPseudo = $('#pseudo').val();
        var valeurMdp = $('#mdp').val();

        var params = 'pseudo=' + valeurPseudo + '&mdp=' + valeurMdp;

        $.post('ajax.php', params, function(data) {
            if(data.connexion == 'ok'){
                // on redirige sur la même page
                window.location = window.location.href;
            } else {
                // message d'erreur pour l'utilisateur
                $('#message').html(data.connexion);
            }
            
        })
    });
});