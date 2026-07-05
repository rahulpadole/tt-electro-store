---
name: Notification bell dropdown pattern
description: How live bell dropdowns (admin + customer) are implemented for TT Electro Store
---

Two separate notification systems exist and must stay separate:
- Customer-facing: `notifications` table, `NotificationModel.php`, `/api/notifications*` — dropdown lives in `views/layout/navbar.php` (`customerNotifBell()` Alpine component defined in `footer.php`).
- Admin-facing: `admin_notifications` table, `AdminNotificationModel.php`, `/api/admin/notifications*` — dropdown lives in `views/layout/admin-header.php` (`adminNotifBell()` Alpine component defined in `admin-footer.php`).

Both use polling (setInterval, 15-20s) + Alpine `x-data`/`x-init`, not websockets. `apiFetch()`/`showToast()` helpers already exist per-layout — reuse them rather than redefining.

**Why:** Keeping customer and admin notification concerns fully separate (own tables, models, API namespaces, Alpine components) avoids cross-contamination of read/unread state and access control (admin vs logged-in user).

**How to apply:** When adding new notification types or dropdown surfaces, follow this same table/model/API/Alpine-component split. Also: when an API endpoint returns a JSON `data` column and you need a derived value from it (e.g. a `linkFor()` helper reading `data['order_id']`), compute the derived field *before* overwriting `data` with its decoded array form — decoding first then re-decoding in a helper causes an "Array to string conversion" PHP warning.
