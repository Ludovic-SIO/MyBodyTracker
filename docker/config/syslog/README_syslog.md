### Fichier `docker-compose.yml`

Le fichier `docker-compose.yml` suivant permet de déployer le serveur Syslog-NG :

```yaml
syslog:
  image: balabit/syslog-ng:latest
  container_name: syslog_gestion_tournament
  ports:
    - "514:514/udp"
    - "514:514/tcp"
  volumes:
    - ./Video_game_tournament_management/docker/config/syslog:/etc/syslog-ng
```

#### Description du Service

**Service Syslog-NG** :

- **Image** : Utilise l’image officielle `balabit/syslog-ng:latest`, fournie par les développeurs de Syslog-NG.
- **Nom du Conteneur** : `syslog_gestion_tournament`.
- **Ports** :
    - UDP 514 et TCP 514 exposés, permettant la réception de messages syslog provenant d’autres conteneurs ou machines.
- **Volumes** :
    - Le répertoire local contenant la configuration (`config/syslog`) est monté dans le conteneur à l’emplacement `/etc/syslog-ng`, où le serveur lit sa configuration.

---

### Fichier de Configuration `syslog-ng.conf`

```conf
@version: 3.22

source s_network {
    udp(ip(0.0.0.0) port(514));
    tcp(ip(0.0.0.0) port(514));
};

destination d_file {
    file("/var/log/syslog.log");
};

log {
    source(s_network);
    destination(d_file);
};
```

#### Description des Instructions

**Version** :

- Spécifie la version de configuration utilisée (ici 3.22, compatible avec l’image).

**Source `s_network`** :

- Écoute les messages syslog sur les ports **UDP et TCP 514** sur toutes les interfaces (`0.0.0.0`).
- Cette configuration permet au serveur de recevoir des logs de n’importe quelle machine ou conteneur qui envoie des logs via le protocole syslog.

**Destination `d_file`** :

- Tous les messages reçus sont enregistrés dans un fichier unique : `/var/log/syslog.log`.

**Bloc `log`** :

- Lie la source réseau `s_network` à la destination fichier `d_file`, mettant en place un pipeline de journalisation simple et fonctionnel.
