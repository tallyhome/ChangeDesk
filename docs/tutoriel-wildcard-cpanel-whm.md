# Tutoriel --- Configurer un domaine wildcard avec cPanel / WHM / PowerDNS / AutoSSL

## Objectif

Configurer un domaine de type :

``` text
changelog-dev.myvcard.fr
```

avec un **wildcard subdomain** :

``` text
*.changelog-dev.myvcard.fr
```

afin que toutes les adresses suivantes arrivent sur le même projet
Laravel :

``` text
https://teste3.changelog-dev.myvcard.fr
https://client1.changelog-dev.myvcard.fr
https://client2.changelog-dev.myvcard.fr
```

Dans notre exemple, le projet Laravel est situé dans :

``` text
/home/fabien/changelog-dev.myvcard.fr
```

et son Document Root est :

``` text
/home/fabien/changelog-dev.myvcard.fr/public
```

> **Nom correct :** on parle généralement de **wildcard subdomain**
> (sous-domaine wildcard) et de **wildcard DNS**.

------------------------------------------------------------------------

# 1. Architecture finale

Le fonctionnement recherché est :

``` text
teste3.changelog-dev.myvcard.fr
             │
             ▼
       DNS wildcard
             │
             ▼
      54.36.246.158
             │
             ▼
        cPanel / Apache
             │
             ▼
/home/fabien/changelog-dev.myvcard.fr/public
             │
             ▼
           Laravel
```

Le certificat SSL wildcard :

``` text
*.changelog-dev.myvcard.fr
```

permet ensuite d'utiliser HTTPS pour :

``` text
teste3.changelog-dev.myvcard.fr
client1.changelog-dev.myvcard.fr
client2.changelog-dev.myvcard.fr
```

------------------------------------------------------------------------

# 2. Pré-requis

-   VPS avec WHM/cPanel
-   accès root SSH
-   compte cPanel, ici `fabien`
-   PowerDNS utilisé par cPanel
-   domaine principal, ici `myvcard.fr`
-   projet Laravel
-   projet situé dans :

``` text
/home/fabien/changelog-dev.myvcard.fr
```

-   Document Root Laravel :

``` text
/home/fabien/changelog-dev.myvcard.fr/public
```

------------------------------------------------------------------------

# 3. Créer le domaine principal dans cPanel

Dans :

**cPanel → Domains → Create A New Domain**

Créer :

``` text
changelog-dev.myvcard.fr
```

Le Document Root doit pointer vers :

``` text
changelog-dev.myvcard.fr
```

si cPanel affiche `/home/fabien/` comme racine du compte.

Le chemin réel doit être :

``` text
/home/fabien/changelog-dev.myvcard.fr
```

Pour Laravel, le Document Root HTTP doit idéalement être :

``` text
/home/fabien/changelog-dev.myvcard.fr/public
```

------------------------------------------------------------------------

# 4. Vérifier le DNS du domaine principal

Dans :

**WHM → DNS Functions → DNS Zone Manager**

ou :

**cPanel → Zone Editor**

la zone `myvcard.fr` doit contenir au minimum :

``` text
changelog-dev.myvcard.fr.    A    54.36.246.158
```

Adapter évidemment l'adresse IP à celle du VPS.

------------------------------------------------------------------------

# 5. Créer le wildcard DNS

Le wildcard DNS est :

``` text
*.changelog-dev.myvcard.fr
```

Il doit pointer vers la même IP :

``` text
*.changelog-dev.myvcard.fr.    A    54.36.246.158
```

Dans notre installation, le DNS est géré par PowerDNS/cPanel.

On peut vérifier depuis SSH :

``` bash
pdnsutil list-zone myvcard.fr | grep -F '*.changelog-dev.myvcard.fr'
```

Résultat attendu :

``` text
*.changelog-dev.myvcard.fr    14400    IN    A    54.36.246.158
```

------------------------------------------------------------------------

# 6. Attention : erreur "DNS entry already exists"

Lors de la création du wildcard dans cPanel, on peut rencontrer :

``` text
A DNS entry for the domain
"*.changelog-dev.myvcard.fr"
already exists.
```

Cela signifie que l'entrée DNS wildcard existe déjà mais que le
domaine/vhost cPanel n'existe pas encore.

Dans notre cas, nous avions effectivement :

``` text
*.changelog-dev.myvcard.fr    A    54.36.246.158
```

dans PowerDNS.

## Vérification

``` bash
pdnsutil list-zone myvcard.fr | grep -F '*.changelog-dev.myvcard.fr'
```

Si l'entrée existe déjà, ne pas créer une deuxième entrée.

La suppression peut être faite depuis :

**WHM → DNS Functions → DNS Zone Manager → myvcard.fr → Manage**

Supprimer uniquement :

``` text
*.changelog-dev.myvcard.fr
```

puis vérifier :

``` bash
pdnsutil list-zone myvcard.fr | grep -F '*.changelog-dev.myvcard.fr'
```

Si rien n'est retourné, l'ancien record wildcard a disparu.

> Ne pas supprimer le record du domaine principal :
>
> ``` text
> changelog-dev.myvcard.fr
> ```
>
> et ne pas toucher aux enregistrements SPF/DKIM sans raison.

------------------------------------------------------------------------

# 7. Créer le wildcard dans cPanel

Dans :

**cPanel → Domains → Create A New Domain**

mettre :

### Domain

``` text
*.changelog-dev.myvcard.fr
```

### Share document root

Laisser **désactivé / décoché**.

### Document Root

Mettre :

``` text
changelog-dev.myvcard.fr/public
```

Avec le compte `/home/fabien`, cela donne :

``` text
/home/fabien/changelog-dev.myvcard.fr/public
```

Puis cliquer sur :

**Soumettre**

------------------------------------------------------------------------

# 8. Vérifier que cPanel a recréé le DNS

Après la création du wildcard, vérifier :

``` bash
pdnsutil list-zone myvcard.fr | grep -F '*.changelog-dev.myvcard.fr'
```

On doit retrouver :

``` text
*.changelog-dev.myvcard.fr    14400    IN    A    54.36.246.158
```

------------------------------------------------------------------------

# 9. Tester le DNS

Depuis le serveur ou un autre ordinateur :

``` bash
nslookup test.changelog-dev.myvcard.fr
```

Résultat attendu :

``` text
Name:    test.changelog-dev.myvcard.fr
Address: 54.36.246.158
```

Tester plusieurs noms :

``` bash
nslookup client1.changelog-dev.myvcard.fr
```

``` bash
nslookup client2.changelog-dev.myvcard.fr
```

Ils doivent tous retourner l'IP du VPS.

------------------------------------------------------------------------

# 10. Tester le wildcard en HTTP

Avant de s'occuper du SSL, tester :

``` text
http://test.changelog-dev.myvcard.fr
```

Si Laravel apparaît, cela signifie que :

``` text
DNS
  ↓
IP VPS
  ↓
cPanel / vhost
  ↓
Document Root
  ↓
Laravel
```

fonctionne correctement.

------------------------------------------------------------------------

# 11. AutoSSL / Let's Encrypt

Le certificat du domaine principal :

``` text
changelog-dev.myvcard.fr
```

peut être valide alors que :

``` text
*.changelog-dev.myvcard.fr
```

possède encore un certificat auto-signé.

Dans ce cas, Chrome peut afficher :

``` text
ERR_CERT_AUTHORITY_INVALID
```

pour :

``` text
https://teste3.changelog-dev.myvcard.fr
```

------------------------------------------------------------------------

# 12. Vérifier AutoSSL

Dans WHM :

**WHM → SSL/TLS → Manage AutoSSL**

Vérifier que :

``` text
fabien
```

a bien :

``` text
Enable AutoSSL
```

Le fournisseur utilisé dans notre cas était :

``` text
Let's Encrypt
```

------------------------------------------------------------------------

# 13. Vérifier si le wildcard est exclu d'AutoSSL

Un problème important rencontré était :

``` text
User-excluded domain: 1 (*.changelog-dev.myvcard.fr)
```

Puis :

``` text
COMPLETELY_EXCLUDED:
All domains are excluded from AutoSSL.
```

Dans ce cas, AutoSSL ne tente même pas de demander le certificat
wildcard.

------------------------------------------------------------------------

# 14. Retirer le wildcard des exclusions AutoSSL

Depuis SSH root :

``` bash
uapi --user=fabien SSL remove_autossl_excluded_domains domains='*.changelog-dev.myvcard.fr'
```

Vérifier ensuite :

``` bash
uapi --user=fabien SSL get_autossl_excluded_domains
```

Le wildcard ne doit plus apparaître dans la liste.

> Adapter `fabien` et le domaine pour une autre installation.

------------------------------------------------------------------------

# 15. Lancer AutoSSL manuellement

Depuis SSH root :

``` bash
/usr/local/cpanel/bin/autossl_check --user=fabien
```

Le log doit analyser :

``` text
*.changelog-dev.myvcard.fr
```

et non plus afficher :

``` text
User-excluded domain
```

------------------------------------------------------------------------

# 16. Validation DNS Let's Encrypt

Pour un certificat wildcard, Let's Encrypt utilise une validation DNS.

Le log AutoSSL doit notamment contenir des lignes similaires à :

``` text
No domains need HTTP DCV
```

puis :

``` text
Enqueueing 1 domain (1 zone) for local DNS DCV
```

et :

``` text
Publishing DNS changes for local DNS DCV
```

puis :

``` text
Local DNS DCV OK:
*.changelog-dev.myvcard.fr
```

et surtout :

``` text
Let's Encrypt DNS DCV OK:
*.changelog-dev.myvcard.fr
```

Cela signifie que Let's Encrypt a validé la possession du domaine.

------------------------------------------------------------------------

# 17. Installation du certificat

Le log doit ensuite contenir quelque chose ressemblant à :

``` text
AutoSSL will request a new certificate.
```

puis :

``` text
Installing "*.changelog-dev.myvcard.fr"'s new certificate
```

et :

``` text
Success!
```

À ce stade, le certificat wildcard Let's Encrypt est installé.

------------------------------------------------------------------------

# 18. Vérifier dans cPanel

Dans :

**cPanel → SSL/TLS Status**

rechercher :

``` text
changelog-dev
```

Le wildcard doit maintenant être quelque chose comme :

``` text
*.changelog-dev.myvcard.fr
AutoSSL Domain Validated
```

et non :

``` text
Self-signed
```

------------------------------------------------------------------------

# 19. Test HTTPS

Tester :

``` text
https://teste3.changelog-dev.myvcard.fr
```

Le navigateur doit afficher le cadenas HTTPS.

Tester également :

``` text
https://client1.changelog-dev.myvcard.fr
```

et :

``` text
https://client2.changelog-dev.myvcard.fr
```

Tous doivent arriver sur le même Laravel.

------------------------------------------------------------------------

# 20. Résumé des commandes SSH utiles

## Vérifier le wildcard DNS

``` bash
pdnsutil list-zone myvcard.fr | grep -F '*.changelog-dev.myvcard.fr'
```

## Vérifier les exclusions AutoSSL

``` bash
uapi --user=fabien SSL get_autossl_excluded_domains
```

## Retirer le wildcard des exclusions

``` bash
uapi --user=fabien SSL remove_autossl_excluded_domains domains='*.changelog-dev.myvcard.fr'
```

## Lancer AutoSSL

``` bash
/usr/local/cpanel/bin/autossl_check --user=fabien
```

## Tester le DNS

``` bash
nslookup test.changelog-dev.myvcard.fr
```

------------------------------------------------------------------------

# 21. Pour utiliser un autre domaine plus tard

Le principe est exactement le même.

Supposons que le client possède :

``` text
client-exemple.fr
```

et qu'on souhaite utiliser :

``` text
*.client-exemple.fr
```

La logique devient :

``` text
*.client-exemple.fr
        ↓
      DNS
        ↓
   IP du VPS
        ↓
     cPanel
        ↓
/home/fabien/client-exemple.fr/public
        ↓
      Laravel
```

## DNS

Créer :

``` text
*.client-exemple.fr    A    IP_DU_VPS
```

## cPanel

Créer :

``` text
*.client-exemple.fr
```

avec le Document Root correspondant au projet :

``` text
client-exemple.fr/public
```

## SSL

Retirer éventuellement le wildcard des exclusions AutoSSL :

``` bash
uapi --user=fabien SSL remove_autossl_excluded_domains domains='*.client-exemple.fr'
```

Puis :

``` bash
/usr/local/cpanel/bin/autossl_check --user=fabien
```

Let's Encrypt effectuera la validation DNS du wildcard.

------------------------------------------------------------------------

# 22. Cas d'un domaine client réellement externe

Il est important de distinguer deux cas.

## Cas A --- le domaine est géré par ton PowerDNS

Exemple :

``` text
client-exemple.fr
```

est hébergé sur les DNS de ton serveur.

Tu peux gérer directement :

``` text
*.client-exemple.fr
```

et AutoSSL peut effectuer la validation DNS localement.

## Cas B --- le client garde ses DNS chez son registrar

Exemple :

``` text
client-exemple.fr
```

utilise les DNS d'OVH, Cloudflare, Gandi, etc.

Il faudra alors créer chez le fournisseur DNS du client :

``` text
*.client-exemple.fr    A    IP_DU_VPS
```

et, pour le certificat wildcard, permettre à cPanel/Let's Encrypt
d'effectuer la validation DNS.

Selon le fournisseur DNS, la gestion automatique de la validation DNS
peut nécessiter une configuration spécifique ou une API DNS.

------------------------------------------------------------------------

# 23. Important pour une application SaaS multi-tenant

Le wildcard ne crée pas un site cPanel différent pour chaque client.

Par exemple :

``` text
client1.changelog-dev.myvcard.fr
client2.changelog-dev.myvcard.fr
client3.changelog-dev.myvcard.fr
```

peuvent tous arriver sur :

``` text
/home/fabien/changelog-dev.myvcard.fr/public
```

Laravel reçoit simplement des Host différents.

Dans Laravel :

``` php
$request->getHost()
```

peut retourner :

``` text
client1.changelog-dev.myvcard.fr
```

ou :

``` text
client2.changelog-dev.myvcard.fr
```

L'application peut ensuite déterminer le tenant à partir du hostname.

------------------------------------------------------------------------

# 24. Points à retenir

### DNS

``` text
*.domaine.fr → IP du VPS
```

### cPanel

``` text
*.domaine.fr
        ↓
/home/USER/projet/public
```

### SSL

``` text
*.domaine.fr
        ↓
Let's Encrypt / AutoSSL
        ↓
DNS-01 / DNS DCV
```

### Application

Tous les sous-domaines arrivent sur le même Laravel.

------------------------------------------------------------------------

# 25. Checklist rapide

Pour refaire l'installation sur un nouveau domaine :

-   [ ] Domaine principal créé dans cPanel
-   [ ] Document Root correct
-   [ ] Projet Laravel dans `/home/USER/PROJET`
-   [ ] Document Root Laravel = `/home/USER/PROJET/public`
-   [ ] Record `A` du domaine principal
-   [ ] Record `A` wildcard `*.domaine.fr`
-   [ ] Wildcard créé dans cPanel
-   [ ] `pdnsutil list-zone` confirme le wildcard
-   [ ] `nslookup test.domaine.fr` retourne l'IP du VPS
-   [ ] HTTP arrive bien sur Laravel
-   [ ] AutoSSL activé pour le compte
-   [ ] Wildcard non exclu d'AutoSSL
-   [ ] `autossl_check` lancé
-   [ ] `Let's Encrypt DNS DCV OK`
-   [ ] certificat wildcard installé
-   [ ] HTTPS testé
-   [ ] plusieurs sous-domaines testés

------------------------------------------------------------------------

# Exemple complet utilisé pendant le test

Domaine :

``` text
changelog-dev.myvcard.fr
```

Wildcard :

``` text
*.changelog-dev.myvcard.fr
```

IP :

``` text
54.36.246.158
```

Utilisateur cPanel :

``` text
fabien
```

Projet :

``` text
/home/fabien/changelog-dev.myvcard.fr
```

Document Root :

``` text
/home/fabien/changelog-dev.myvcard.fr/public
```

Test :

``` text
https://teste3.changelog-dev.myvcard.fr
```

Certificat :

``` text
*.changelog-dev.myvcard.fr
```

Résultat :

``` text
DNS          ✅
Wildcard    ✅
cPanel       ✅
Laravel      ✅
DNS DCV      ✅
Let's Encrypt ✅
HTTPS        ✅
```
