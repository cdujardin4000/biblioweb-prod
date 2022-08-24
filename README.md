# biblioweb-cedric

AJOUT D'UNE TABLE RATINGS => biblioweb.sql
Qd un membre loue un livre un ligne se crée automatiquement dans la table rankings, qd la date retour d'une location est dépassée le membre peut noter le livre et changer sa note.(pas de doublons)

novice: tester la création de compte
membre avec location: user:lara pw:1234
membre sans location: user:bob pw:1234
admin: user:kaneda pw:1234

1. La session demarre en $_SESSION['statut'] = 'unknown'
2. apres sign-in l'utilisateur est en $_SESSION['statut'] = 'novice'. cela permet d'afficher un message d'accueil et une photo de bienvenue et éventuellement vérifier que c'est pas un bot.
3. L'enregistrement db se mets en $_SESSION['statut'] = 'membre'
4. Après déco reco il aura donc le statut de $_SESSION['statut'] = 'membre'


un user NOVICE:
-peut voir la liste des livres 
-voir le détail d'un livre.
-rechercher pas auteur

un user MEMBRE: 
-peut voir la liste des livres, 
-voir le détail d'un livre, 
-rechercher pas auteur
-louer un livre, 
-noter un livre s'il la date return est dépassée, 
-changer sa note s'il l'a déja noté

un user ADMIN:
-peut voir la liste des livres, 
-voir le détail d'un livre, 
-rechercher pas auteur
-modifier un livre,
-ajouter des auteurs, 
-promote ou retrograde un user, 
-gerer les locations(à implémenter), 
-ajouter livre, 
-modifier livre
-effacer livre.
-changer le logo 
-ajouter une cover à un livre sans cover (bouton page admin)
-attribuer a un user au hazard un livre dont l'auteur n'à aucun livre en location

Les membres ne voient que les livres disponibles... S'ils ont deja loué le livre, ils peuvent le relouer s'il est disponible et le noter, ils peuveut modifier leur note s'il ont déja noté.

TODO: 
-verifier injections sql et xss

