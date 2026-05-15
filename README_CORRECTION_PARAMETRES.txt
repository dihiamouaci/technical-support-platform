Correction Paramètres
=====================

La page admin_settings.php a été corrigée.

Problème corrigé :
- Les boutons/onglets Général, Mot de passe, Email / SMTP et Modules dépendaient de Bootstrap JS via CDN.
- Si le JavaScript Bootstrap ne se chargeait pas, cliquer sur les onglets ne faisait rien.

Correction :
- Ajout d'un système d'onglets en JavaScript simple, directement dans admin_settings.php.
- Ajout d'un champ action dans chaque formulaire pour savoir quelle section sauvegarder.
- Correction du cas Modules : même si aucune case n'est cochée, l'enregistrement fonctionne.

Après remplacement du dossier dans htdocs :
1. Connecte-toi en admin.
2. Va dans Paramètres.
3. Clique sur Mot de passe, Email / SMTP ou Modules.
4. Les sections doivent maintenant s'afficher.
