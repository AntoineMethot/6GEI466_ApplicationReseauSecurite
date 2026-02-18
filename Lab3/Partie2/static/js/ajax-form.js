$(function () {
    $("#horoscope-form").on("submit", function (e) {
        //Intercepter le POST
        e.preventDefault();

        // Get data
        var prenom = $("#prenom").val();
        var nom = $("#nom").val();
        var date = $("#datepicker").val();


        $.ajax({
            type: "POST",
            url: "/horoscope",
            data: { prenom, nom, date },
            success: function (data) {
                $("#horoscope-form").hide();
                $("#change-identity").show();

                const imgUrl = "/static/images/" + data.image;

                $("#horoscope-result").html(`
                <h2>Bienvenue ${data.prenom} ${data.nom}!</h2>
                <p>Votre signe: <strong>${data.sign}</strong></p>
                <p><a href="${imgUrl}" target="_blank">Voir l’image</a></p>
                <img src="${imgUrl}" alt="${data.sign}" style="width:150px; margin:20px 0;">
                <div style="text-align:left; max-width:600px; margin:0 auto;">${data.text}</div>
                `).show();

            },
            error: function (xhr) {
                // Afficher le message exact exigé par le serveur
                $("#horoscope-result").html(`<div class="alert alert-danger">${xhr.responseText}</div>`).show();
            }
        });
    });
});
