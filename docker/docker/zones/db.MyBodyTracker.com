$TTL    604800
@       IN      SOA     ns1.MyBodyTracker.com. admin.MyBodyTracker.com. (
                              3         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
;
@       IN      NS      ns1.MyBodyTracker.com.

ns1     IN      A       127.0.0.1
web     IN      A       127.0.0.1
mysql   IN      A       127.0.0.1
php     IN      A       127.0.0.1

