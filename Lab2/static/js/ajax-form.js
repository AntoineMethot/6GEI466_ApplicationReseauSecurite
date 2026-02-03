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
            data: {
                prenom: prenom,
                nom: nom,
                date: date
            },
            success: function (response) {
                // Cache form
                $("#horoscope-form").hide();
                // SShow result
                $("#horoscope-result").html(response).show();
                // Show change identity
                $("#change-identity").show();
            }
        });
    });
});
