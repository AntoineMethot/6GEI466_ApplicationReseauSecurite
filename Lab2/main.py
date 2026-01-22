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
    
    # Get zodiac sign from date
    sign = get_zodiac_sign(date)
    
    # Get horoscope data
    horoscope_data = HOROSCOPES.get(sign, {})
    horoscope_text = horoscope_data.get("text", "Horoscope non disponible")
    image = horoscope_data.get("image", "")
    
    # Generate HTML response
    html_response = f"""
    <h2>Bienvenue {prenom} {nom}!</h2>
    <p>Votre signe: <strong>{sign}</strong></p>
    <img src="./static/images/{image}" alt="{sign}" style="width: 150px; margin: 20px 0;">
    <div style="text-align: left; max-width: 600px; margin: 0 auto;">
        {horoscope_text}
    </div>
    """
    
    return html_response

# Gestion des erreurs 404
@app.errorhandler(404)
def page_not_found(error):
    p = request.path
    if len(p) > 2000:
        p = p[:2000] + "…"
    return render_template("erreur404.html", path=p), 404


if __name__ == "__main__":
    app.run(debug=True, port=8000)
