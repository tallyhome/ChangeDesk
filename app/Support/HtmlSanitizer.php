<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Nettoie le HTML des éditeurs (changelog, wiki, pages, todos).
 * Conserve la mise en forme TinyMCE et retire scripts, iframes et gestionnaires d'événements.
 */
final class HtmlSanitizer
{
    /** @var list<string> */
    private const ALLOWED = [
        'p', 'br', 'strong', 'em', 'b', 'i', 'u', 's', 'ul', 'ol', 'li',
        'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'code', 'pre',
        'span', 'div', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'img', 'hr',
        'sub', 'sup',
    ];

    /** @var list<string> */
    private const DROP = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'link', 'meta',
        'base', 'svg', 'math', 'textarea', 'input', 'button',
    ];

    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        if (! preg_match('/<[a-z!\/]/i', $html)) {
            return $html;
        }

        $dom = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8"><body>'.$html.'</body>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (! $body instanceof DOMElement) {
            return '';
        }

        self::walk($body);

        $out = '';
        foreach ($body->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    /**
     * Texte public (signalement) : HTML filtré s'il y a des balises, sinon texte échappé avec retours à la ligne.
     */
    public static function format(?string $html): string
    {
        $html ??= '';
        if (preg_match('/<[a-z!\/]/i', $html)) {
            return self::clean($html);
        }

        return nl2br(e($html), false);
    }

    private static function walk(DOMNode $node): void
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);
            if (in_array($tag, self::DROP, true)) {
                $node->removeChild($child);

                continue;
            }

            if (! in_array($tag, self::ALLOWED, true)) {
                self::walk($child);
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);

                continue;
            }

            self::cleanAttributes($child);
            self::walk($child);
        }
    }

    private static function cleanAttributes(DOMElement $el): void
    {
        $tag = strtolower($el->tagName);
        $allowed = match ($tag) {
            'a' => ['href', 'title', 'target'],
            'img' => ['src', 'alt', 'title'],
            'td', 'th' => ['colspan', 'rowspan', 'style'],
            default => ['title', 'class', 'style'],
        };

        $remove = [];
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            if (str_starts_with($name, 'on') || ! in_array($name, $allowed, true)) {
                $remove[] = $attr->name;

                continue;
            }

            if (($name === 'href' || $name === 'src') && ! self::safeUrl($attr->value, $name === 'src')) {
                $remove[] = $attr->name;
            }

            if ($name === 'style') {
                $filtered = self::filterStyle($attr->value);
                if ($filtered === '') {
                    $remove[] = $attr->name;
                } else {
                    $el->setAttribute('style', $filtered);
                }
            }

            if ($name === 'target' && $attr->value !== '_blank') {
                $remove[] = $attr->name;
            }
        }

        foreach ($remove as $name) {
            $el->removeAttribute($name);
        }

        if ($tag === 'a' && $el->getAttribute('target') === '_blank') {
            $el->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function safeUrl(string $url, bool $image): bool
    {
        $url = trim(html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($url === '' || str_contains(strtolower($url), 'javascript:') || str_contains(strtolower($url), 'vbscript:')) {
            return false;
        }

        if (str_starts_with($url, '#') || (str_starts_with($url, '/') && ! str_starts_with($url, '//'))) {
            return true;
        }

        if (str_starts_with(strtolower($url), 'data:')) {
            return false;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if ($scheme === 'mailto') {
            return ! $image;
        }

        return in_array($scheme, ['http', 'https'], true);
    }

    private static function filterStyle(string $style): string
    {
        $kept = [];
        foreach (explode(';', $style) as $declaration) {
            if (! str_contains($declaration, ':')) {
                continue;
            }
            [$prop, $value] = array_map('trim', explode(':', $declaration, 2));
            $prop = strtolower($prop);
            $value = strtolower($value);
            if (str_contains($value, 'url(') || str_contains($value, 'expression') || str_contains($value, 'javascript')) {
                continue;
            }

            $ok = match ($prop) {
                'color', 'background-color' => (bool) preg_match('/^(#[0-9a-f]{3,8}|[a-z]{3,20}|rgba?\([0-9.,\s%]+\))$/', $value),
                'font-size' => (bool) preg_match('/^\d{1,3}(\.\d+)?(px|pt|em|rem|%)$/', $value),
                'font-family' => (bool) preg_match('/^[a-z0-9 ,\-"]+$/', $value),
                'text-align' => in_array($value, ['left', 'right', 'center', 'justify'], true),
                'text-decoration' => in_array($value, ['none', 'underline', 'line-through'], true),
                'font-weight' => in_array($value, ['normal', 'bold', '400', '600', '700'], true),
                default => false,
            };

            if ($ok) {
                $kept[] = $prop.': '.$value;
            }
        }

        return implode('; ', $kept);
    }
}
