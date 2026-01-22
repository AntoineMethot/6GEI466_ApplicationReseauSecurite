from flask import Flask
from flask import send_from_directory, render_template, request, abort
import re

app = Flask(__name__)

# Route principale avec GET and POST
@app.route("/", methods=["GET", "POST"])
def accueil():
    titre = "Page d’accueil"

    if request.method == "POST":
        nom = validate_content(request.form.get("nom", ""))
        ville = validate_content(request.form.get("ville", ""))
        return render_template("accueil.html", titre=titre, soumis=True, nom=nom, ville=ville)

    return render_template("accueil.html", titre=titre, soumis=False)

# Gestion des erreurs 404
@app.errorhandler(404)
def page_not_found(error):
    p = request.path
    if len(p) > 2000:
        p = p[:2000] + "…"
    return render_template("erreur404.html", path=p), 404


def validate_content(s: str) -> str:
    # Remove whitespace
    s = (s or "").strip()
    # Max 50 char limit
    if not (1 <= len(s) <= 50):
        abort(400, "Invalid length")
    # Accept only letters and what would be in a name
    if not re.fullmatch(r"[A-Za-zÀ-ÖØ-öø-ÿ '\-]+", s):
        abort(400, "Invalid characters")
    return s

if __name__ == "__main__":
    app.run(debug=True, port=8000, ssl_context='adhoc')
