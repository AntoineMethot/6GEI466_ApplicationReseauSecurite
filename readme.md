# Laboratoire 1 6GEI466

## Description générale

Le premier laboratoire du cours 6GEI466 comprend créer un server web Flask qui permet la récupération de pages HTML, ainsi que remplir un formulaire et envoyer des données avec la méthode POST.

## Routes
### /static/*
Pour la route /static/*. une route a été crée pour retourner un fichier html avec le nom correspondant à *. La bonne pratique en python et en Flash pour retourner une page static html est d'utiliser la fonction send_from_directory(). La route /static/* permet uniquement des GET.

Pour l'instant il n'y a que 2 fichiers static: helloworld1.html et helloworld2.html qui peuvent être récupérée avec /static/helloworldx

### Gestion des erreurs404
Flask contient des fonctions intégrées qui permettent de faciliter la gestion des erreurs 404. la fonction app.errorhandler(404) permet justement de capter quand les erreurs 404 ont lieux et on peux préciser de renvoyer la page error404.html que nous avons crée. Maintenant, puisque cette page n'est pas 100% statique, on utilise la fonction de flask render_template(). Je capte la route qui a été tenté et je l'inclus dans la page.

### /acceuil

Enfin, pour la page d'acceuil, cette route permet les méthodes GET et POST. quand on fait un GET, on charge la page avec le formulaire. Une fois que le formulaire rempli et qu'on l'envoie avec un POST, on charge la page avec un contenu différent qui contient les informations du formulaire. Jinja2 permet des conditions tel que if et else dans le html qui permet d'afficher un contenu différent dépendament de certaines conditions.