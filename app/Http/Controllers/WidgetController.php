<?php

namespace App\Http\Controllers;

class WidgetController extends Controller
{
    public function script()
    {
        $news = json_encode(__('app.public.widget_news'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
        $latest = json_encode(__('app.public.widget_latest'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);

        $js = <<<JS
(function () {
  var script = document.currentScript;
  if (!script || !script.src) return;
  var origin;
  try { origin = new URL(script.src).origin; } catch (e) { return; }
  var badge = script.hasAttribute('data-evolora-badge');
  var newsLabel = {$news};
  var latestLabel = {$latest};
  fetch(origin + '/widget/feed.json', { credentials: 'omit', mode: 'cors' })
    .then(function (r) { if (!r.ok) throw new Error('feed'); return r.json(); })
    .then(function (data) { render(script, origin, data, badge, newsLabel, latestLabel); })
    .catch(function () {});

  function safeUrl(value, origin) {
    try {
      var u = new URL(String(value || ''), origin);
      if (u.origin !== origin) return origin + '/changelog';
      if (u.protocol !== 'https:' && u.protocol !== 'http:') return origin + '/changelog';
      return u.href;
    } catch (e) {
      return origin + '/changelog';
    }
  }

  function text(value, max) {
    var s = String(value == null ? '' : value).replace(/\\s+/g, ' ').trim();
    return s.length > max ? s.slice(0, max) : s;
  }

  function render(script, origin, data, badge, newsLabel, latestLabel) {
    var versions = Array.isArray(data && data.versions) ? data.versions.slice(0, 5) : [];
    var list = document.createElement('div');
    list.style.marginTop = '8px';
    versions.forEach(function (v) {
      var a = document.createElement('a');
      a.href = safeUrl(v && v.url, origin);
      a.target = '_blank';
      a.rel = 'noopener noreferrer';
      a.style.cssText = 'display:block;color:#0f766e;text-decoration:none;margin:6px 0';
      var line = 'v' + text(v && v.version, 20);
      if (v && v.date) line += ' — ' + text(v.date, 32);
      a.appendChild(document.createTextNode(line));
      if (v && v.excerpt) {
        var extra = document.createElement('span');
        extra.style.cssText = 'display:block;color:#475569;font-size:12px';
        extra.textContent = text(v.excerpt, 180);
        a.appendChild(extra);
      }
      list.appendChild(a);
    });

    if (badge) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.textContent = newsLabel;
      btn.style.cssText = 'border:0;background:#0f766e;color:#fff;border-radius:999px;padding:6px 12px;cursor:pointer;font:14px system-ui,sans-serif';
      var current = versions[0] ? text(versions[0].version, 20) : '';
      var seen = '';
      try { seen = localStorage.getItem('evolora-seen:' + origin) || ''; } catch (e) {}
      if (current && seen !== current) btn.appendChild(document.createTextNode(' •'));
      list.hidden = true;
      btn.addEventListener('click', function () {
        list.hidden = !list.hidden;
        if (current) { try { localStorage.setItem('evolora-seen:' + origin, current); } catch (e) {} }
      });
      var wrap = document.createElement('div');
      wrap.style.cssText = 'font:14px/1.4 system-ui,sans-serif';
      wrap.appendChild(btn);
      wrap.appendChild(list);
      script.parentNode.insertBefore(wrap, script.nextSibling);
      return;
    }

    var box = document.createElement('div');
    box.style.cssText = 'font:14px/1.4 system-ui,sans-serif;color:#0f172a;background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:12px 14px;max-width:360px';
    var title = document.createElement('strong');
    title.textContent = text(data && data.name, 80) || latestLabel;
    box.appendChild(title);
    box.appendChild(list);
    script.parentNode.insertBefore(box, script.nextSibling);
  }
})();
JS;

        return response($js, 200, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
