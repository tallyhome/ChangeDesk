# SSL multi-tenant (Caddy + Let’s Encrypt)

## Sous-domaines `*.changelog.fr`

1. Créez un enregistrement DNS wildcard : `*.changelog.fr` → IP / CNAME de votre serveur.
2. Obtenez un certificat wildcard Let’s Encrypt (DNS-01) ou laissez Caddy le gérer si votre DNS le permet.

Exemple Caddy (wildcard via DNS provider) :

```caddy
*.changelog.fr, changelog.fr {
    tls {
        dns cloudflare {env.CLOUDFLARE_API_TOKEN}
    }
    reverse_proxy app:80
}
```

## Domaines custom (on-demand TLS)

Laravel expose une allowlist :

`GET /api/ssl/ask?domain=changelog.monsite.fr`

- **200** : domaine central, sous-domaine tenant actif, ou `custom_domain` **vérifié**
- **404** : refusé (pas de certificat)

Exemple Caddy :

```caddy
{
    on_demand_tls {
        ask http://app:80/api/ssl/ask
    }
}

:443 {
    tls {
        on_demand
    }
    reverse_proxy app:80
}
```

Variables `.env` utiles :

```
CENTRAL_DOMAIN=changelog.fr
CENTRAL_DOMAINS=changelog.fr,www.changelog.fr
TENANCY_CNAME_TARGET=cname.changelog.fr
APP_URL=https://changelog.fr
```

Côté client DNS :

```
changelog.monsite.fr.  CNAME  cname.changelog.fr.
```

Puis « Vérifier le DNS » dans Admin → Domaine.
