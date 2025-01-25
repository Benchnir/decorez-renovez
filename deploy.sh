#!/bin/bash

# Couleurs pour les messages
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}Début du déploiement...${NC}"

# Vérifier que composer est installé
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Composer n'est pas installé. Installation...${NC}"
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

# Installation des dépendances PHP
echo -e "${GREEN}Installation des dépendances PHP...${NC}"
composer install --no-dev --optimize-autoloader

# Vérification de la présence des fichiers de configuration
if [ ! -f "firebase-credentials.json" ]; then
    echo -e "${RED}Erreur: firebase-credentials.json manquant${NC}"
    exit 1
fi

if [ ! -f ".env" ]; then
    echo -e "${RED}Erreur: .env manquant${NC}"
    exit 1
fi

# Création des dossiers nécessaires
echo -e "${GREEN}Création des dossiers...${NC}"
mkdir -p data/cache data/logs public/uploads
chmod -R 777 data public/uploads

# Nettoyage du cache
echo -e "${GREEN}Nettoyage du cache...${NC}"
rm -rf data/cache/*

# Vérification des permissions
echo -e "${GREEN}Mise à jour des permissions...${NC}"
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 777 data/cache data/logs public/uploads

echo -e "${GREEN}Déploiement terminé avec succès !${NC}"
