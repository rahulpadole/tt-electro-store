---
name: Shipping Constants
description: Where shipping thresholds and charges are defined and how views must use them
---

Config defines (config/config.php):
- FREE_SHIPPING_ABOVE = 499
- SHIPPING_CHARGE = 49

**Why:** Originally the config had 999/99 but views used 499/49 — mismatch. Constants were corrected to 499/49 to match the store's actual policy. All views (cart.php, checkout.php) must reference FREE_SHIPPING_ABOVE and SHIPPING_CHARGE instead of hardcoding numbers.

**How to apply:** If the shipping policy changes, update config/config.php only. Never hardcode 499 or 49 in views or JS (PHP echoes the constant into JS via <?= FREE_SHIPPING_ABOVE ?>).
