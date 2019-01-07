Le blog des pingouins
=====================

**What does the penguin say ? Can penguins fly ? Come and find out !**

 [Lien vers l'app Heroku](https://chaillot-kriszt-blog.herokuapp.com/)

**Auteurs :** Anthony Chaillot, Theo Kriszt

#Recap'
- Doctrine Fixtures pour peupler la base [dev]
- Doctrine FOSUserBundle pour les utilisateurs
- FontAwesome pour les icones

# Utilisateurs
- Anonyme : Peut consulter le blog
- Enregistré : Peut poster et commenter
- Admin (compte unique) : peut modifier/supprimer tous les articles

## Inscription / connexion
Le lien de connexion se trouve dans la barre de navigation

Si vous n'avez pas de compte, la page de login mène propose de s'inscrire

Le compte administrateur utilise les identifiants / mdp ``admin / admin``

# TODO
---------------------------
- [x] Ajouter les encadrants en collaborateurs du projet
- [x] ajouter la BdD postgre
- [x] CRUD sur les users
- [x] CRUD sur les posts
- [x] Habiller la page de login ;)
- [x] Les commentaires
- [x] Check la recherche d'article
- [ ] Bosser les articles de facon sémantique (article, content, etc.)


##Bonus :
- [ ] Faire une auth via Google/facebook
- [ ] Une page 404
- [ ] Traductions
- [ ] Virer catégories et remplacer par derniers messages
- [ ] Ajouter des tags aux articles 
- [ ] Ajouter une vraie recherche par tags 
- [ ] Essayer de trouver un Bundle pour mieux gérer les forms


## CSS
- [x] eviter de compresser/déformer les photos quand ecran trop petit
- [ ] Pousser le CSS de base
- [ ] Inscription Users
- [ ] ~~-Interface Admin~~
