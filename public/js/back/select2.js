$(document).ready(function() {
        $('#playlist_musics').select2({
            ajax: {
                url: apiMusicsUrl,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term // recherche du terme
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            minimumInputLength: 1 // Nombre minimum de caractères requis pour déclencher la recherche
        });
});
  