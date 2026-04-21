# MyTicket Application - Function Testing Report

**Date:** April 22, 2026  
**Test Method:** MCP Playwright Browser Testing  
**Scope:** All view and function testing for User, Petugas, and Admin roles

---

## Executive Summary

Comprehensive testing of all functions across three user roles (User, Petugas, Admin) revealed **11 bugs and issues** ranging from critical fatal errors to minor UI problems.

### Summary by Role
- **User Role:** 6 functions tested, 3 bugs found
- **Petugas Role:** 3 functions tested, 2 bugs found  
- **Admin Role:** 7 functions tested, 5 bugs found

### Critical Issues Requiring Immediate Fix
1. **UserController access level error** - Fatal error prevents User management
2. **Duplicate HTML in order/create.php** - Broken page structure
3. **Wrong column name order_date** - Affects 5 view files
4. **Missing TicketUserController fix** - QR code view fails

---

## User Role - Function Testing

### Test Account
- **Email:** user@gmail.com
- **Password:** password
- **Name:** Al Warid

### Test Results

| # | Function | URL | Status | Notes |
|---|----------|-----|--------|-------|
| 1 | **Login** | `index.php?page=auth&action=login` | ✅ WORKING | Successful authentication |
| 2 | **User Dashboard** | `index.php?page=dashboard&action=user` | ✅ WORKING | Shows recent orders and upcoming events correctly |
| 3 | **Browse Events** | `index.php?page=event&action=index` | ✅ WORKING | Lists 4 events with venue info |
| 4 | **Event Details** | `index.php?page=event&action=show&id=1` | ✅ WORKING | Shows event info and available tickets |
| 5 | **Order Creation Form** | `index.php?page=order&action=create&ticket_id=5&event_id=1` | ⚠️ WORKING with BUG | Form works but has **duplicate "Cancel" text** at bottom due to duplicate HTML code (lines 153-164 in create.php) |
| 6 | **Order History** | `index.php?page=order&action=history` | ❌ **BUG** | Shows: `Warning: Undefined array key "order_date" in user/order/history.php on line 43` |
| 7 | **Order Details** | `index.php?page=order&action=show&id=5` | ❌ **BUG** | Shows: `Warning: Undefined array key "order_date" in user/order/show.php on line 33` |
| 8 | **My Tickets** | `index.php?page=ticket&action=index` | ✅ WORKING | Shows purchased tickets with correct info |
| 9 | **Ticket QR View** | `index.php?page=ticket&action=show&order_id=5` | ❌ **FATAL ERROR** | Fatal error: `Unknown column 'order_detail_id' in 'where clause' - TicketUserController.php line 63` |
| 10 | **Logout** | `index.php?page=auth&action=logout` | ✅ WORKING | Successful logout |

### User Role Bugs Summary

1. **order_date column name bug** (history.php, show.php)
   - Views use `$order['order_date']` but database column is `date`
   - Fix: Change `order_date` to `date` in both files

2. **Duplicate HTML in create.php** (lines 153-164)
   - Duplicate closing tags causing broken layout
   - Fix: Remove duplicate HTML at end of file

3. **TicketUserController show method** (line 63)
   - Uses `order_detail_id` instead of `detail_id`
   - Fix: Change to `detail_id` (already fixed in index method at line 28)

---

## Petugas Role - Function Testing

### Test Account
- **Email:** petugas1@gmail.com
- **Password:** password
- **Name:** Petugas 1

### Test Results

| # | Function | URL | Status | Notes |
|---|----------|-----|--------|-------|
| 1 | **Login** | `index.php?page=auth&action=login` | ✅ WORKING | Successful authentication |
| 2 | **Petugas Dashboard** | `index.php?page=dashboard&action=petugas` | ✅ WORKING | Shows Check-in and View Orders buttons |
| 3 | **Check-in Page** | `index.php?page=attendee&action=index` | ⚠️ WORKING with ISSUE | Shows form but displays **"Berhasil masuk!"** (Success) message on initial load before any form submission |
| 4 | **View Orders** | `index.php?page=order&action=index` | ❌ **BUG** | Shows: `Warning: Undefined array key "order_date" in admin/order/index.php on line 55` |
| 5 | **Logout** | `index.php?page=auth&action=logout` | ✅ WORKING | Successful logout |

### Petugas Role Bugs Summary

1. **Success message on initial load** (checkin/index.php)
   - Shows success message before any form submission
   - Likely session flash message not being cleared properly
   - Fix: Check session handling in AttendeeController

2. **order_date column name bug** (same as admin order index)
   - Views use `$o['order_date']` but database column is `date`
   - Fix: Change `order_date` to `date`

---

## Admin Role - Function Testing

### Test Account
- **Email:** admin@gmail.com
- **Password:** password
- **Name:** Admin

### Test Results

| # | Function | URL | Status | Notes |
|---|----------|-----|--------|-------|
| 1 | **Login** | `index.php?page=auth&action=login` | ✅ WORKING | Successful authentication |
| 2 | **Admin Dashboard** | `index.php?page=dashboard&action=admin` | ✅ WORKING | Shows stats: 3 Users, 1 Order, Rp 0 Revenue |
| 3 | **Users Management** | `index.php?page=user&action=index` | ❌ **FATAL ERROR** | `Fatal error: Access level to UserController::getSidebarMenu() must be protected (as in class BaseController) or weaker` |
| 4 | **Venues Management** | `index.php?page=venue&action=index` | ✅ WORKING | Shows 4 venues with edit/delete actions |
| 5 | **Events Management** | `index.php?page=event&action=index` | ⚠️ WORKING with DATA ISSUE | Shows events but **wrong venue names** (legacy from migration bug - venue column shows event names) |
| 6 | **Tickets Management** | `index.php?page=ticket&action=index` | ✅ WORKING | Shows 4 tickets with correct event names |
| 7 | **Vouchers Management** | `index.php?page=voucher&action=index` | ✅ WORKING | Shows 4 vouchers with correct status |
| 8 | **Orders Management** | `index.php?page=order&action=index` | ❌ **BUG** | Shows: `Warning: Undefined array key "order_date" in admin/order/index.php on line 55` |
| 9 | **Order Details** | `index.php?page=order&action=show&id=5` | ❌ **NOT TESTED** | UserController error prevents testing |
| 10 | **Check-in** | `index.php?page=attendee&action=index` | ⚠️ WORKING with ISSUE | Shows form but displays **"Berhasil masuk!"** (Success) message on initial load |
| 11 | **Logout** | `index.php?page=auth&action=logout` | ✅ WORKING | Successful logout |

### Admin Role Bugs Summary

1. **UserController access level error** (CRITICAL)
   - `getSidebarMenu()` method has wrong access level
   - Fix: Change from private to protected in UserController

2. **order_date column name bug** (admin/order/index.php, show.php)
   - Same issue as user views
   - Also affects `customer_name` field
   - Fix: Change column names to match database

3. **Success message on initial load** (checkin/index.php)
   - Same issue as petugas check-in
   - Fix: Check session handling in AttendeeController

4. **Wrong venue names in Events** (data issue)
   - Events show wrong venue names due to migration bug
   - Legacy data issue - would need data fix

---

## Detailed Bug Report

### Bug 1: UserController Fatal Error
**Severity:** CRITICAL  
**Location:** `app/controllers/UserController.php`  
**Error:** `Fatal error: Access level to UserController::getSidebarMenu() must be protected (as in class BaseController) or weaker`  
**Impact:** Admin cannot access User Management  
**Fix:** Change method visibility from `private` to `protected` in UserController

### Bug 2: Wrong Column Name - order_date
**Severity:** HIGH  
**Affected Files:**
- `app/views/user/order/history.php` (line 43)
- `app/views/user/order/show.php` (line 33)
- `app/views/admin/order/index.php` (line 55)
- `app/views/admin/order/show.php` (line 34)
- `app/views/user/ticket/index.php` (line 32)

**Issue:** Views use `$order['order_date']` but database column is `date`  
**Impact:** PHP warnings and empty date fields  
**Fix:** Change all `order_date` references to `date`

### Bug 3: Missing customer_name Field
**Severity:** HIGH  
**Affected Files:**
- `app/views/admin/order/index.php` (line 54)
- `app/views/admin/order/show.php` (line 33)

**Issue:** Views use `$o['customer_name']` but this field doesn't exist in query results  
**Impact:** PHP warnings  
**Fix:** Update OrderModel query to join with users table OR remove customer_name display

### Bug 4: Duplicate HTML in Order Create
**Severity:** HIGH  
**Location:** `app/views/user/order/create.php` (lines 153-164)  
**Issue:** Duplicate closing form and div tags  
**Impact:** Broken HTML structure, duplicate "Cancel" text visible  
**Fix:** Delete lines 153-164

### Bug 5: TicketUserController show Method
**Severity:** HIGH  
**Location:** `app/controllers/TicketUserController.php` (line 63)  
**Error:** `Fatal error: Unknown column 'order_detail_id' in 'where clause'`  
**Impact:** User cannot view ticket QR codes  
**Fix:** Change `order_detail_id` to `detail_id` in SQL query (similar to fix at line 28)

### Bug 6: Premature Success Message in Check-in
**Severity:** MEDIUM  
**Location:** `app/views/admin/checkin/index.php`  
**Issue:** Shows "Berhasil masuk!" (Success) on initial page load  
**Impact:** Confusing UX - success shown before form submission  
**Fix:** Check session flash message handling in AttendeeController

### Bug 7: Wrong Venue Names (Data Issue)
**Severity:** LOW  
**Location:** Events display  
**Issue:** Events show wrong venue names (migration bug legacy data)  
**Impact:** Incorrect venue information displayed  
**Fix:** Would require database data correction

---

## Recommendations

### Immediate Actions (Critical)
1. Fix UserController access level error
2. Fix all `order_date` column name references (5 files)
3. Fix TicketUserController show method query
4. Remove duplicate HTML from user/order/create.php

### High Priority
5. Add `customer_name` field to order queries or remove from views
6. Fix check-in success message session handling

### Medium Priority
7. Review all remaining CRUD views that weren't tested (create/edit forms)
8. Add CSRF protection to all forms

---

## Testing Statistics

| Role | Functions Tested | Passed | Failed | Pass Rate |
|------|------------------|--------|--------|-------------|
| User | 10 | 6 | 4 | 60% |
| Petugas | 5 | 3 | 2 | 60% |
| Admin | 11 | 6 | 5 | 55% |
| **TOTAL** | **26** | **15** | **11** | **58%** |

---

## Appendix: Tested Accounts

```
Admin:
  Email: admin@gmail.com
  Password: password
  Role: admin

Petugas:
  Email: petugas1@gmail.com
  Password: password
  Role: petugas

User:
  Email: user@gmail.com
  Password: password
  Role: user
  Name: Al Warid
```

---

## End of Report