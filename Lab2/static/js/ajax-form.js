$(function () {
    // AJAX for horoscope form. Execute on form submit
    $("#horoscope-form").on("submit", function (e) {
        //Stop form from submitting normally
        e.preventDefault();

        // Get form data
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
                // Hide form
                $("#horoscope-form").hide();

                // Show horoscope result
                $("#horoscope-result").html(response).show();

                // Show change identity link
                $("#change-identity").show();
            },
            error: function (xhr) {
                alert("Erreur: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    // Reset when changing identity
    $("#change-identity-link").on("click", function (e) {
        e.preventDefault();

        // Reset form
        $("#horoscope-form")[0].reset();
        $("#datepicker").datepicker("setDate", new Date());

        // Show form
        $("#horoscope-form").show();

        // Hide horoscope result and change identity link
        $("#horoscope-result").hide();
        $("#change-identity").hide();
    });
});
