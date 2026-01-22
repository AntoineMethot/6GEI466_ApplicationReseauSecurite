from flask import Flask
from flask import render_template, request, abort
from utils import *

app = Flask(__name__)

# Route principale avec GET and POST
@app.route("/", methods=["GET", "POST"])
def accueil():
    titre = "Page d’accueil"

    # Return loaded form
    if request.method == "POST":
        prenom = validate_content(request.form.get("prenom", ""))
        nom = validate_content(request.form.get("nom", ""))
        date = validate_date(request.form.get("date", ""))
        return render_template("accueil.html", titre=titre, soumis=True, prenom=prenom, nom=nom, date=date)

    #Return empty form
    return render_template("accueil.html", titre=titre, soumis=False)

# Gestion des erreurs 404
@app.errorhandler(404)
def page_not_found(error):
    p = request.path
    if len(p) > 2000:
        p = p[:2000] + "…"
    return render_template("erreur404.html", path=p), 404


if __name__ == "__main__":
    app.run(debug=True, port=8000)
