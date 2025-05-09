### Fichier `docker-compose.yml`

Le fichier `docker-compose.yml` ci-dessous définit le service DNS :

```yaml
dns:
  image: ubuntu/bind9
  container_name: dns_gestion_tournament
  ports:
    - "53:53/tcp"
    - "53:53/udp"
  volumes:
    - ./Video_game_tournament_management/docker/config/dns:/etc/bind/
    - ./Video_game_tournament_management/docker/zones:/var/lib/bind/
```

#### Description du Service

**Service DNS (BIND9)** :

- **Image** : Utilise l'image officielle `ubuntu/bind9`, qui contient un serveur DNS complet.
- **Nom du Conteneur** : `dns_gestion_tournament`.
- **Ports** :
	TCP 53 et UDP 53 (port DNS standard) sont exposés sur le port 53 de l’hôte pour éviter les conflits avec des services DNS déjà existants sur la machine hôte.
- **Volumes** :
    Le répertoire local `config/dns` est monté dans `/etc/bind/` dans le conteneur pour fournir les fichiers de configuration de BIND.
    Le répertoire `zones` est monté dans `/var/lib/bind/`, où les fichiers de zone sont stockés.

---

### Fichiers de Configuration BIND9

#### `named.conf`

```bash
include "/etc/bind/named.conf.options";
include "/etc/bind/named.conf.local";
```

**Description** :

- Ce fichier principal inclut les fichiers de configuration pour les options globales (`named.conf.options`) et la définition des zones (`named.conf.local`).

#### `named.conf.options`

```bash
options {
    directory "/var/lib/bind";
    recursion yes;
    allow-recursion { any; };
    forwarders {
        8.8.8.8;
        8.8.4.4;
    };
    dnssec-validation auto;
    auth-nxdomain no;
    listen-on-v6 { any; };
};
```

**Description** :

- **Directory** : Emplacement des fichiers de zone.
- **Recursion** : Activée pour permettre la résolution de noms externes.
- **Forwarders** : Redirige les requêtes DNS non gérées vers les serveurs DNS publics de Google.
- **DNSSEC** : Validation DNS sécurisée activée.
- **IPv6** : Le serveur écoute sur toutes les interfaces IPv6.

#### `named.conf.local`

```bash
zone "gestionTournament.com" {
    type master;
    file "/var/lib/bind/db.gestionTournament.com";
};
```

**Description** :

- Déclare une zone DNS appelée `gestionTournament.com`.
- Définit le fichier de zone correspondant : `db.gestionTournament.com`.

#### `db.gestionTournament.com`

```bash
$TTL    604800
@       IN      SOA     ns1.gestionTournament.com. admin.gestionTournament.com. (
                              3         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
;
@       IN      NS      ns1.gestionTournament.com.

ns1     IN      A       127.0.0.1
web     IN      A       127.0.0.1
mysql   IN      A       127.0.0.1
php     IN      A       127.0.0.1
```

**Description** :

- Définit la zone `gestionTournament.com` avec un TTL (Time To Live) par défaut.
- Fournit un enregistrement SOA (Start of Authority) pour la zone.
- Définit le serveur de noms (`NS`) principal : `ns1.gestionTournament.com`.
- Ajoute des enregistrements `A` pointant vers `127.0.0.1` pour les sous-domaines `ns1`, `web`, `mysql`, et `php`, permettant leur résolution locale.
