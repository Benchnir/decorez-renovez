# DecorezRenovez

Un site web de mise en relation entre particuliers et artisans pour des projets de décoration et rénovation. Initialement développé avec Zend Framework en 2012, maintenant modernisé avec Firebase.

## Structure du Projet

- `application/` : Code source de l'application Zend Framework
- `public/` : Fichiers publics (CSS, JS, images)
- `library/` : Librairies tierces
- `scripts/` : Scripts de migration et utilitaires
- `data/` : Fichiers de données et cache

## Prérequis

- PHP 7.4 ou supérieur
- Composer
- Node.js et npm (pour les outils de build)
- Compte Firebase avec Firestore activé

## Installation

1. Cloner le repository :
   ```bash
   git clone https://github.com/votre-username/decorezrenovez.git
   cd decorezrenovez
   ```

2. Configurer Firebase :
   - Créer un projet sur [Firebase Console](https://console.firebase.google.com)
   - Activer Firestore Database
   - Télécharger le fichier de credentials (`firebase-credentials.json`) et le placer à la racine du projet

3. Créer le fichier `.env` :
   ```
   FIREBASE_PROJECT_ID=decorez-renovez
   ```

4. Lancer le script de déploiement :
   ```bash
   ./deploy.sh
   ```

## Base de données

La base de données est maintenant sur Firebase Firestore avec les collections suivantes :
- `members` : Utilisateurs (particuliers et artisans)
- `announcements` : Annonces de projets
- `departments` : Départements français avec leurs régions

## Développement

Pour lancer l'environnement de développement :

1. Installer les dépendances :
   ```bash
   composer install
   ```

2. Configurer le serveur web (Apache/Nginx) pour pointer vers le dossier `public/`

3. Vérifier les permissions :
   ```bash
   chmod -R 777 data/cache data/logs public/uploads
   ```

## Déploiement

Le déploiement est automatisé via le script `deploy.sh` qui :
- Installe les dépendances PHP
- Configure les permissions
- Nettoie le cache
- Prépare l'environnement de production

## Sécurité

- Ne jamais commiter les fichiers sensibles :
  - `firebase-credentials.json`
  - `.env`
  - Fichiers dans `data/`

## Support

Pour toute question ou problème, merci d'ouvrir une issue sur GitHub.
