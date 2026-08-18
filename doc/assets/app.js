(function () {
  const pages = [
    { group: "Démarrer", items: [
      { href: "index.html", id: "index", label: "Vue d’ensemble" },
      { href: "prerequis.html", id: "prerequis", label: "Prérequis" },
      { href: "wizard.html", id: "wizard", label: "Assistant /install" },
    ]},
    { group: "Hébergeurs", items: [
      { href: "cpanel.html", id: "cpanel", label: "cPanel / WHM" },
      { href: "webuzo.html", id: "webuzo", label: "Webuzo" },
      { href: "serveur.html", id: "serveur", label: "Apache, Nginx, Plesk" },
    ]},
    { group: "Configurer", items: [
      { href: "base-de-donnees.html", id: "bdd", label: "Base de données" },
      { href: "configuration.html", id: "config", label: "Fichier .env" },
      { href: "sous-domaines.html", id: "wildcard", label: "Sous-domaines" },
      { href: "depannage.html", id: "help", label: "Dépannage" },
    ]},
  ];

  const root = document.getElementById("app");
  if (!root) return;
  const current = root.dataset.page || "index";
  const mainHtml = root.innerHTML;

  const nav = pages.map((group) => {
    const links = group.items.map((item) => {
      const active = item.id === current ? " active" : "";
      return `<a class="${active.trim()}" href="${item.href}"><span class="dot"></span>${item.label}</a>`;
    }).join("");
    return `<div class="nav-label">${group.group}</div>${links}`;
  }).join("");

  root.className = "app";
  root.innerHTML = `
    <aside class="sidebar">
      <a class="brand" href="index.html">
        <span class="brand-mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6 4c0 5 0 7 4 10 3 2 6 2.5 6 6" stroke="#14B8A6" stroke-width="2.2" stroke-linecap="round"/>
            <circle cx="6" cy="7" r="1.4" fill="#5EEAD4"/>
            <circle cx="9" cy="12" r="1.4" fill="#14B8A6"/>
            <circle cx="16" cy="18" r="1.4" fill="#E8B86D"/>
            <path d="M9 7h9M11 12h8M18 18h3" stroke="#14B8A6" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
        </span>
        <span><strong>Evolora</strong><span>Guide d’installation</span></span>
      </a>
      <nav class="nav">${nav}</nav>
    </aside>
    <div>
      <div class="topbar">
        <strong>Evolora Docs</strong>
        <button class="menu-btn" type="button" data-toggle>Menu</button>
      </div>
      ${mainHtml}
    </div>
  `;

  const overlay = document.createElement("div");
  overlay.className = "overlay";
  document.body.appendChild(overlay);

  const close = () => document.body.classList.remove("nav-open");
  document.querySelector("[data-toggle]")?.addEventListener("click", () => {
    document.body.classList.toggle("nav-open");
  });
  overlay.addEventListener("click", close);
  document.querySelectorAll(".nav a").forEach((a) => a.addEventListener("click", close));
})();
