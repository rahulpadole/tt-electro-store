---
name: TT Electro Store design system
description: Color tokens, component classes, Alpine.js patterns, and layout conventions used across every page of the store.
---

# TT Electro Store — Design System

## Theme setup
- `darkMode: 'class'` (Tailwind) — toggled via `appState().toggleTheme()` in footer.php
- Applied to `<html>` via Alpine `x-init` in header.php
- `localStorage('theme')` persists choice

## Color tokens (always use these patterns, never hardcoded dark-only)
| Role | Light | Dark |
|------|-------|------|
| Page bg | `bg-slate-50` | `bg-[hsl(222,47%,8%)]` |
| Card bg | `bg-white` | `bg-[hsl(222,47%,10%)]` |
| Elevated card | `bg-slate-50` | `bg-[hsl(222,47%,13%)]` |
| Border | `border-slate-200` | `border-white/6` |
| Strong text | `text-slate-900` | `text-white` |
| Body text | `text-slate-600` | `text-slate-400` |
| Muted text | `text-slate-400` | `text-slate-500` |

## Reusable CSS classes (in custom.css)
- `.section-label` — small pill label above section titles
- `.section-title` / `.section-subtitle` — section heading hierarchy
- `.chip`, `.chip-blue`, `.chip-green`, `.chip-red`, `.chip-amber` — tag/badge pills
- `.countdown-digit` — flash sale countdown block
- `.trust-strip` — horizontal trust badges bar
- `.hero-gradient` — hero section gradient bg
- `.gradient-text` — blue→cyan gradient text
- `.input-base` — consistent input/select styling (light + dark, focus ring)
- `.logo-img` — logo with gold filter in dark mode

## Alpine components (all defined in footer.php script block)
- `appState()` — theme toggle, cart/wishlist counts
- `navbar()` — mobile menu, mega dropdown state
- `showToast(msg, type)` — global toast notifications
- `apiFetch(url, opts)` — authenticated fetch wrapper
- `addToCart(id)` / `addToWishlist(id)` — global cart/wishlist helpers

## Standalone pages (no shared layout)
- `login.php`, `register.php` — include their own inline `<head>` with theme init script, Inter font, Tailwind, Alpine, FA

## Section pattern
```html
<p class="section-label"><i class="fa-solid fa-X"></i> Label</p>
<h2 class="section-title">Title</h2>
<p class="section-subtitle">Subtitle</p>
```

## Icon convention
- Font Awesome 6 (`fa-solid`, `fa-regular`, `fa-brands`) — no emoji anywhere in production UI

## Why
Established during a full UI overhaul (June 2026) to ensure every page renders correctly in both dark and light mode with consistent spacing, card styles, and interactive states.

## How to apply
When adding any new page or component: use `bg-white dark:bg-[hsl(222,47%,10%)]` for cards, `text-slate-900 dark:text-white` for headings, `input-base` for all form fields, `chip` variants for tags/badges. Never use `text-gray-*` (use `text-slate-*`).
