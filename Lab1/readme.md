# Laboratoire 1 6GEI466

Version Python: 3.12.6

## Description générale

Le premier laboratoire du cours 6GEI466 consiste à créer un serveur web Flask permettant la récupération de pages HTML, ainsi que la soumission d’un formulaire et l’envoi de données à l’aide de la méthode POST.

## Démarrage du serveur
Le serveur peut être démarré localement en se plaçant dans le répertoire contenant le fichier `main.py` et en exécutant la commande : `python .\main.py` 
Il est également possible d’utiliser l’extension Python de Visual Studio Code.  
Dans tous les cas, un environnement virtuel doit être configuré et les dépendances doivent être installées à partir du fichier `requirements.txt` à l’aide de la commande : `pip install -r requirements.txt`

## Accès au serveur
Le serveur a été précisé à fonctionner sur le port 8000. Pour y acceder, dans le navigateur il faut inscrire https://localhost:8000 ou https://127.0.0.1:8000 puisque c'est https. Puisque l'application même a crée le certificat, le navigateur avertira que le certificat peut être dangereux.


## Routes
### /static/*
La route `/static/*` permet de retourner un fichier HTML statique dont le nom correspond à `*`.  
Une route explicite a été créée afin de respecter le comportement demandé dans l’énoncé.  
La fonction `send_from_directory()` de Flask est utilisée pour retourner les fichiers HTML statiques, ce qui constitue une solution appropriée pour ce type de contenu.  
Cette route accepte uniquement la méthode GET.

Actuellement, deux fichiers statiques sont disponibles :
- `helloworld1.html`
- `helloworld2.html`

Ils peuvent être récupérés à l’aide des routes :  /static/helloworldx

### /acceuil

La route `/accueil` accepte les méthodes GET et POST.

- Lors d’une requête GET, la page est chargée avec le formulaire.
- Lors d’une requête POST, les données du formulaire sont traitées et la page est rechargée avec un contenu différent affichant les informations soumises.

Le moteur de gabarits Jinja2 permet d’utiliser des structures conditionnelles (`if / else`) directement dans le HTML afin d’afficher un contenu différent selon l’état de la requête.


### Gestion des erreurs404
Flask fournit des mécanismes intégrés pour la gestion des erreurs HTTP.  
La fonction `app.errorhandler(404)` est utilisée afin d’intercepter les erreurs 404 et de retourner le gabarit `erreur404.html`.

Puisque cette page n’est pas entièrement statique, la fonction `render_template()` est utilisée.  
Le chemin de la route demandée est capté et transmis au gabarit afin d’être affiché à l’utilisateur.


## Sécurité

Avec la lib `pyopenssl`, il est possible de générer des certificats ssl et utiliser le protocole https. Puisque l'application même a crée le certificat, le navigateur avertira que le certificat peut être dangereux.

Par défaut, Jinja2 applique un échappement HTML automatique lors de l’affichage de variables à l’aide de la syntaxe `{{ }}`, ce qui constitue un premier niveau de protection contre les injections de code. L'ajout de `| e` dans `{{ }}` est redondant, mais démontre explicitement l'échappement HTML.

En complément, une fonction `validate_content()` a été implémentée afin de valider les données saisies dans le formulaire. Cette fonction vérifie notamment :
- la longueur des champs
- l’absence de caractères spéciaux non désirés

Un mécanisme similaire est utilisé pour l’affichage de la route invalide dans le gabarit de l’erreur 404.  
Cependant, cette validation est adaptée afin de permettre la présence de chiffres dans l’URL.  
Une vérification de la longueur est également effectuée afin d’éviter qu’une requête contenant un nombre excessif de caractères ne puisse nuire au serveur.
