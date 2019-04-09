# Synology-routeur-sfr-fibre

Insérer une carte SD dans le routeur et la formatter (en ext4) avec ce dernier, cela permettra de stocker les fichiers et paquets nécessaires à l'opération

Se connecter sur la WebUI du routeur et installer manuellement le package Easy Bootstrap Installer depuis cette URL :
https://www.cphub.net/?p=ebi

Durant l'installation, sélectionner "Entware-ng oPKG", "Bind mount", "Optware before Synology NAS" et sélectionner le support de stockage correspondant (Carte SD)

Récupérer les infos sur la Neufbox à partir des url suivantes :
http://ip-neufbox/api/1.0/?method=system.getInfo
http://ip-neufbox/api/1.0/?method=lan.getHostsList
http://ip-neufbox/api/1.0/?method=wan.getInfo
http://ip-neufbox/api/1.0/?method=ftth.getInfo
http://ip-neufbox/api/1.0/?method=tv.getInfo
http://ip-neufbox/api/1.0/?method=usb.getInfo

Sauvegarder les résultats dans des fichiers selon cette correspondance :
system.getInfo		>	system.xml
lan.getHostsList	>	lan.xml
wan.getInfo		>	wan.xml
ftth.getInfo		>	ftth.xml
tv.getInfo		>	tv.xml
usb.getInfo		>	usb.xml

Activer le service SSH depuis l'interface du routeur :
Panneau de configuration > Services > Activer le service SSH > Valider avec le bouton "Appliquer"

Lancer PuTTY et se connecter avec le compte root sur le routeur et installer via opkg les paquets suivants :
- lighttpd
- lighttpd-mod-cgi
- lighttpd-mod-redirect
- lighttpd-mod-rewrite
- php7
- php7-cgi
- python

opkg install lighttpd lighttpd-mod-cgi lighttpd-mod-redirect lighttpd-mod-rewrite php7 php7-cgi python

Editer les fichiers suivants :
vi /etc.defaults/httpd/sites-enabled/redirect.conf
	- A la première ligne, remplacer "Listen 80" par "Listen 81"
	- A la deuxièmen ligne, remplacer "<Virtualhost *:80>" par "<Virtualhost *:81>"

vi /etc.defaults/httpd/sites-enabled-user/redirect.conf
	- A la quatrième ligne, remplacer "RewriteCond %{SERVER_PORT} =80" par "RewriteCond %{SERVER_PORT} =81"

Lancer WinSCP et copier les fichiers XML reçu depuis la Neufbox ainsi que le fichier index.php attaché dans /opt/share/www/api/1.0 (créer l'arborescence)

Toujours avec WinSCP, aller dans le répertoire /opt/etc/lighttpd/ et renommer le fichier lighttpd.conf en lighttpd.conf.old
Dans ce répertoire, copier le fichier lighttpd.conf attaché

Aller dans le répertoire /opt/etc/lighttpd/conf.d et copier le fichier 30-cgi.conf

Redémarrer le routeur

Se connecter en SSH sur le routeur et valider que le port 80 est bien utilisé par lighttpd via la commande suivante :
netstat -tulpn | grep :80

Il devrait y avoir cette ligne :
tcp        0      0 0.0.0.0:80              0.0.0.0:*               LISTEN      18672/lighttpd

Vérifier que la redirection et la réécriture d'URL fonctionne avec la commande suivante :
curl http://ip-routeur/api/1.0/?method=system.getInfo

Si le contenu du fichier system.xml est retourné, c'est que tout est en ordre

Redémarrer le décodeur TV
