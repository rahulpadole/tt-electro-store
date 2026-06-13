---
name: Order + Stock Integrity
description: How order creation handles stock checking and decrement atomically
---

OrderModel::create() uses a DB transaction wrapping all operations:

1. For each item: SELECT stock ... FOR UPDATE (row-level lock)
2. If stock < qty → rollBack() + throw RuntimeException with human-readable message
3. UPDATE products SET stock = stock - qty
4. INSERT orders + INSERT order_items
5. COMMIT

api/orders/index.php catches \RuntimeException and calls jsonError($e->getMessage(), 422).

**Why:** Without the transaction + FOR UPDATE, concurrent orders could oversell stock. Without the try/catch in the API, unhandled exceptions would return a 500 with no useful message.

**How to apply:** Any future order-like flow (returns, pre-orders) must also decrement/restore stock inside a transaction.
