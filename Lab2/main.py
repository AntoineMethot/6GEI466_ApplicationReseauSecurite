from flask import Flask
from flask import render_template, request, abort, jsonify
from utils import *

app = Flask(__name__)

# Route principale avec GET
@app.route("/", methods=["GET"])
def accueil():
    return render_template("accueil.html")


# Horoscope route - handles AJAX POST request
@app.route("/horoscope", methods=["POST"])
def horoscope():
    prenom = validate_content(request.form.get("prenom", ""))
    nom = validate_content(request.form.get("nom", ""))
    date = validate_date(request.form.get("date", ""))
    
    # Generate horoscope response
    horoscope_text = f"<p>Bonjour {prenom} {nom}, voici votre horoscope pour le {date}!</p>"
    
    return horoscope_text

# Gestion des erreurs 404
@app.errorhandler(404)
def page_not_found(error):
    p = request.path
    if len(p) > 2000:
        p = p[:2000] + "…"
    return render_template("erreur404.html", path=p), 404


if __name__ == "__main__":
    app.run(debug=True, port=8000)
