@php
    $locales = \App\Support\Locale::all();
    $current = \App\Support\Locale::current();
    $meta = \App\Support\Locale::meta($current);
    $variant = $variant ?? 'auto';
@endphp
<div class="lang-switch lang-{{ $variant }}" data-lang-switch>
  <button type="button" class="lang-btn" aria-haspopup="listbox" aria-expanded="false" aria-label="{{ __('app.nav.language') }}">
    <span class="lang-flag" aria-hidden="true">{{ $meta['flag'] }}</span>
    <span class="lang-code">{{ strtoupper($current) }}</span>
    <span class="lang-caret" aria-hidden="true">▾</span>
  </button>
  <ul class="lang-menu" role="listbox" hidden>
    @foreach($locales as $code => $item)
      <li role="option" aria-selected="{{ $code === $current ? 'true' : 'false' }}">
        <a href="{{ route('locale.switch', $code) }}" class="{{ $code === $current ? 'is-current' : '' }}">
          <span aria-hidden="true">{{ $item['flag'] }}</span>
          <span>{{ $item['native'] }}</span>
        </a>
      </li>
    @endforeach
  </ul>
</div>
<style>
.lang-switch{position:relative;display:inline-flex;z-index:50;font-family:inherit}
.lang-btn{display:inline-flex;align-items:center;gap:.35rem;padding:.38rem .7rem;border-radius:999px;border:1px solid currentColor;background:transparent;color:inherit;cursor:pointer;font:inherit;font-size:.82rem;font-weight:600;line-height:1;opacity:.92}
.lang-btn:hover{opacity:1}
.lang-flag{font-size:1rem;line-height:1}
.lang-caret{font-size:.65rem;opacity:.7}
.lang-menu{position:absolute;top:calc(100% + .4rem);right:0;min-width:11.5rem;margin:0;padding:.35rem;list-style:none;border-radius:12px;background:#fff;color:#111;box-shadow:0 16px 40px rgba(0,0,0,.18);border:1px solid rgba(0,0,0,.08)}
.lang-menu a{display:flex;align-items:center;gap:.55rem;padding:.45rem .6rem;border-radius:8px;text-decoration:none;color:#111;font-size:.88rem}
.lang-menu a:hover,.lang-menu a.is-current{background:#f3f4f6}
.lang-switch.lang-dark .lang-menu{background:#111827;color:#f9fafb;border-color:rgba(255,255,255,.1)}
.lang-switch.lang-dark .lang-menu a{color:#f9fafb}
.lang-switch.lang-dark .lang-menu a:hover,.lang-switch.lang-dark .lang-menu a.is-current{background:rgba(255,255,255,.08)}
.lang-switch.lang-light .lang-btn{border-color:rgba(0,0,0,.16);color:#111}
.lang-switch.lang-on-dark .lang-btn{border-color:rgba(255,255,255,.28);color:#fff}
</style>
<script>
(() => {
  document.querySelectorAll('[data-lang-switch]').forEach((root) => {
    if (root.dataset.bound === '1') return;
    root.dataset.bound = '1';
    const btn = root.querySelector('.lang-btn');
    const menu = root.querySelector('.lang-menu');
    const close = () => { menu.hidden = true; btn.setAttribute('aria-expanded', 'false'); };
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const open = menu.hidden;
      document.querySelectorAll('[data-lang-switch] .lang-menu').forEach((m) => { m.hidden = true; });
      menu.hidden = !open;
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('click', close);
  });
})();
</script>
