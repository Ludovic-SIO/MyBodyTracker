#!/bin/sh

# Vérification des paramètres
if [ $# -ne 2 ]; then
  echo "Usage: $0 <nom_utilisateur> <mot_de_passe>"
  exit 1
fi

USER="$1"
PASS="$2"
DOMAIN="local"        # Ton domaine de labo
PASSWD_FILE="/etc/dovecot/passwd"
VMAILBOX_FILE="/etc/postfix/vmailbox"

# 1. Ajouter dans /etc/dovecot/passwd
echo "${USER}:{PLAIN}${PASS}" >> "$PASSWD_FILE"

# 2. Ajouter dans /etc/postfix/vmailbox
echo "${USER}@${DOMAIN} ${USER}/" >> "$VMAILBOX_FILE"

# 3. Regénérer la base de Postfix
postmap "$VMAILBOX_FILE"

# 4. Recharger Postfix (optionnel, souvent pas nécessaire juste après postmap)
postfix reload

echo "✅ Utilisateur ajouté : ${USER}@${DOMAIN}"
