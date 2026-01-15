from flask import Flask, send_from_directory, render_template, request

app = Flask(__name__)

# Route principale
@app.route("/", methods=["GET", "POST"])
def accueil():
    titre = "Page d’accueil"

    if request.method == "POST":
        nom = request.form.get("nom", "")
        ville = request.form.get("ville", "")

        return render_template(
            "accueil.html",
            titre=titre,
            soumis=True,
            nom=nom,
            ville=ville
        )

    return render_template(
        "accueil.html",
        titre=titre,
        soumis=False
    )

# Retourne la page correspondante dans static
@app.route("/static/<name>", methods=["GET"])
def static_html(name):
    filename = f"{name}.html"
    return send_from_directory("static/pages", filename)

# Gestion des erreurs 404
@app.errorhandler(404)
def page_not_found(error):
    return render_template("erreur404.html"), 404

if __name__ == "__main__":
    app.run(debug=True)
