FROM alpine:3.19

# 1. Paquets
RUN apk add --no-cache postfix dovecot dovecot-lmtpd supervisor

# 2. Utilisateur/stockage
RUN addgroup -g 5000 virtmail || true \
 && adduser -S -u 5000 -G virtmail -h /var/mail/virtmail virtmail || true \
 && mkdir -p /var/mail/virtmail && chown -R virtmail:virtmail /var/mail/virtmail

# 3. Config
COPY /postfix/main.cf           /etc/postfix/main.cf
COPY /postfix/vmailbox           /etc/postfix/vmailbox
COPY /postfix/master.cf         /etc/postfix/master.cf
COPY /dovecot/dovecot.conf      /etc/dovecot/dovecot.conf
COPY /dovecot/passwd            /etc/dovecot/passwd

COPY add-mailuser.sh /usr/local/bin/add-mailuser.sh
RUN chmod +x /usr/local/bin/add-mailuser.sh

RUN  postmap /etc/postfix/vmailbox

# 4. Supervisor (ou s6, tini…)
COPY supervisord.conf /etc/supervisord.conf

EXPOSE 25 143
CMD ["/usr/bin/supervisord","-c","/etc/supervisord.conf"]
