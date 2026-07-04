---
name: Validator::in() strict type comparison bug
description: Why a Validator ->in() check silently rejects valid JSON-submitted values
---

`Validator::in($field, $allowed)` uses `in_array($value, $allowed, true)` (strict mode). If `$allowed` is an array of strings (e.g. `['1','2','3']`) but the value comes from `getJsonBody()` (JSON numbers decode to PHP int), the strict check always fails with "Invalid value" even though the value is valid.

**Why:** This caused the customer review submission to always fail with a generic validation error — the `rating` field decoded as an int but was checked against a string array.

**How to apply:** When validating a field with `->in()` after JSON body decoding, make sure the `$allowed` array's element types match the decoded JSON type (e.g. use `[1,2,3,4,5]` not `['1','2','3','4','5']` for a numeric field). Audit any other `->in()` calls the same way if new ones are added.
