# Administration

L'objectif est de permettre aux administrateurs qui se connecte de Apouvoir ajouter des articles qui seront publiés sur le blog.

Les éléments suivants vont être nécessaires :

## Session

- Une classe SessionManager qui se chargera de démarrer les sessions et les détruire
- Générer un identifiant unique pour l'utilisateur
- Un identifiant de session unique qui sera enregistré lorsque l'administrateur sera connecté qui servira notamment pendant la création de ressource

- La superglobale ne doit être utilisée que dans index.php

- Une session doit être démarrer et détruite dans index.php
## Login
- Une vérification sur l'identité de l'utilisateur connecté



# Scénario MAJ 13 juillet 2023 à  16h28 (à revoir)
Lorsqu'un utilisateur va se connecter on va d'abord vérifier qu'une session n'a pas déjà été créée  :

Cas n°1 : La session n'existe pas alors on la créer et on va insérer un identifiant unique dans la base de données avec d'autres informations non sensibles comme le nom de l'utilisateur , son type etc..