---
name: Route Auth Guards
description: Which routes require login and OTP validation rules
---

Routes in index.php $routes array with auth=true: /checkout, /wishlist, /orders, /dashboard, /notifications.

OTP validation: must use `strlen($otp) !== 6 || !ctype_digit($otp)` — NOT `strlen < 4`. OTPs are always exactly 6 digits.

Auth APIs with method checks: login, register, logout all require POST. otp-send, otp-verify, reset-password, forgot-password all require POST.

wishlist/item.php requires requireLogin() — unauthenticated DELETE requests must be rejected.

**Why:** Checkout was previously auth=false, allowing PHP null-array access warnings when $user=null pre-fills form fields. /wishlist also required login since the API requires it.
