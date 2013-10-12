- [x] Ajouter un champ de recherche (filtered text avec jQuery Mobile), qui filtre
      sur les titres et descriptions.
- [x] Compter la taille des MP3 dans la taille totale de chaque livret
- [x] Ajouter une pop-up pour chaque élément où on peut lire le titre et la
      description de l’élément
- [x] bouton qui contient la taille de la ressource (combine taille + case à
      cocher)

Mardi 18/06, 16h.

- [x] fermer la pop-up au clic
- [x] sélectionner une ressource au clic sur tout l’élément, sauf l’image.
- [x] Afficher le nombre de MP3 dans la popup
- [x] mettre une image par défaut pour les contenus sans image
- [x] Ne pas montrer la date de dernière modification si la ressource n’a pas
      été modifiée depuis sa création
- [x] Mettre le nombre/taille des contenus sélectionnés à côté du nom du pays, pour
      voir rapidement où sont les éléments sélectionnés quand tout est replié.
- [x] Ajouter un textfield pour le nom d’utilisateur

Mercredi 26/06, 10h.

- [x] Ajouter les logos des organisations sur la page principale de l’appli
- [x] Mettre le champ de pseudo sur une seule ligne
- [x] Remplir la page « À Propos » avec les noms des gens (cf mail JBY)
- [x] Remplir la page « Aide »
- [ ] Filtrer (aussi) sur les tags avec le champ de filtre
- [ ] Quand on sélectionne des livrets, mettre la taille totale des fichiers
  sélectionnés en haut


- [x] Corriger la taille affichée pour chaque ressources, par ex le premier
  livret du Bénin fait 311ko, la taille affichée est de 311o.
- [x] Lors du clic sur le bouton de validation, envoyer la liste des ressources
  sélectionnées ainsi que le pseudo au serveur (JS)
- [x] Côté serveur, générer / mettre à jour le fichier `/<pseudo>.xml`
- [~] Toujours côté serveur, retourner une confirmation (ou pas) et côté JS
  afficher l’URL à copier
- [x] Lors de la confirmation du serveur, afficher aussi un lien pour voir les
  ressources, qui mène vers une page qui met en cache les ressources (AppCache)
- [~] Si ça n’est pas le cas, pré-enregistrer le pseudo auprès du serveur et
  afficher une confirmation visuelle.
- [x] Au chargement de la page, vérifiez en PHP si un pseudo est dans le cookie,
  et si oui, l’afficher dans le champ
- [x] Au chargement de la page, si le pseudo est dans le cookie, récupérer la
  liste des ids des ressources sélectionnées, et les pré-sélectionner via PHP
  (ou appel d’API).

Mardi 17/09:

- [ ] Quand l’utilisateur entre un pseudo en haut, charger les contenus qu’il a
  déjà sélectionné (afficher X/5, etc)
- [ ] Deux pages, une de sélection, une pour voir les contenus en cache, + une
  première page pour choisir entre les deux en fonction du mode online/offline.
- [ ] Sur la page des docs en cache, ajouter un indicateur de progression clair.
- [ ] Après validation des contenus, page intermédiaire pour choisir entre
  l’utilisation de AppCache et de l’appli Android.
- [ ] Sélection du pseudo dans la première page.

Octobre:

- [x] Quand on change de pseudo, charger les ressources concernées, ce n’est pas un
  changement de pseudo d’un utilisateur mais un changement d’utilisateur
- [x] Préselectionner les ressources sur la page de sélection en JS, parce que
  le changement de pseudo a pu être réalisé après le chargement de la page.
- [ ] Quand on clique sur un bouton de la landing page juste après avoir changé
  de pseudo mais en étant toujours focusé sur le champ, attendre le retour du
  call AJAX avant de naviguer sur une autre page.
