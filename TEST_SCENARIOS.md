# MyTicket - Test Scenarios

## 1. Authentication & Authorization Tests

### 1.1 Login Security
| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| AUTH-01 | Invalid email format | Enter "invalid-email" in email field | Validation error |
| AUTH-02 | SQL injection attempt | Enter "' OR '1'='1" in email field | Login fails, no SQL error |
| AUTH-03 | XSS in login form | Enter `<script>alert(1)</script>` in email | Input sanitized, no alert |
| AUTH-04 | Brute force protection | Attempt 10 failed logins | Account lockout or rate limiting |
| AUTH-05 | Session fixation | Login, note session ID, logout, login again | New session ID generated |
| AUTH-06 | Password strength | Register with weak password "123" | Validation error |
| AUTH-07 | Empty credentials | Submit login with empty fields | Validation error |

### 1.2 Role-based Access Control
| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| RBAC-01 | User accesses admin URL | Login as user, navigate to `?page=user&action=index` | 403 Forbidden or redirect |
| RBAC-02 | Petugas accesses admin URL | Login as petugas, navigate to admin user management | 403 Forbidden or redirect |
| RBAC-03 | Admin accesses all roles | Login as admin, access petugas and user pages | Access granted |
| RBAC-04 | Direct URL access without login | Access `?page=dashboard&action=admin` without session | Redirect to login |
| RBAC-05 | Cross-role navigation | As user, try to access `?page=attendee&action=index` | Redirect to appropriate dashboard |

---

## 2. User Registration Tests

| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| REG-01 | Duplicate email | Register with existing email | Error: Email already exists |
| REG-02 | Invalid email | Enter "test@invalid" | Validation error |
| REG-03 | Empty required fields | Leave name/email/password empty | Validation error for each |
| REG-04 | Very long inputs | Enter 1000 character name | Truncated or validation error |
| REG-05 | Special characters in name | Enter "Test<script>User" | Name sanitized, no XSS |
| REG-06 | Password confirmation (if exists) | Enter mismatched passwords | Validation error |
| REG-07 | SQL injection in registration | Enter "'; DROP TABLE users; --" | Registration fails safely |

---

## 3. Event & Ticket Tests

### 3.1 Event Browsing
| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| EVT-01 | Empty event list | Browse events when none exist | "No events available" message |
| EVT-02 | Event details with invalid ID | Navigate to `?page=event&action=show&id=99999` | 404 or error message |
| EVT-03 | XSS in event name | Create event with `<script>` in name | Name displayed safely |
| EVT-04 | SQL injection in event ID | Navigate to `?page=event&action=show&id=1' OR '1'='1` | Handled safely |
| EVT-05 | Pagination (if exists) | Navigate through event pages | Correct pagination |

### 3.2 Ticket Purchase
| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| TKT-01 | Exceed quota | Try to buy 1000 tickets when quota is 100 | Error: Insufficient quota |
| TKT-02 | Zero quantity | Enter quantity "0" | Validation error |
| TKT-03 | Negative quantity | Enter quantity "-1" | Validation error |
| TKT-04 | Non-numeric quantity | Enter "abc" in quantity | Validation error |
| TKT-05 | Invalid voucher code | Enter "INVALIDCODE" | Error: Invalid voucher |
| TKT-06 | Expired voucher | Use expired voucher code | Error: Voucher expired |
| TKT-07 | Exhausted voucher quota | Use voucher with 0 quota | Error: Quota exhausted |
| TKT-08 | Concurrent purchase | Two users buy last ticket simultaneously | One succeeds, one fails (race condition) |
| TKT-09 | Price calculation | Apply percentage voucher | Correct discount calculated |
| TKT-10 | Order without login | Access order URL without session | Redirect to login |

---

## 4. Order Management Tests

| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| ORD-01 | View order details of another user | User A tries to view User B's order | 403 Forbidden |
| ORD-02 | Cancel order | Cancel pending order | Order status updated, quota restored |
| ORD-03 | Cancel paid order | Try to cancel paid order | Error: Cannot cancel paid order |
| ORD-04 | Order with deleted event | View order for deleted event | Graceful handling |
| ORD-05 | Order history pagination | User with 100+ orders | Pagination works correctly |
| ORD-06 | Order total calculation | Verify subtotal - discount = total | Math is correct |

---

## 5. Check-in System Tests

| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| CHK-01 | Duplicate check-in | Check in same ticket twice | Error: Already checked in |
| CHK-02 | Check-in with invalid code | Enter "FAKECODE123" | Error: Ticket not found |
| CHK-03 | Check-in expired ticket | Try to check in past event | Error or warning |
| CHK-04 | SQL injection in ticket code | Enter "' OR '1'='1" | Handled safely |
| CHK-05 | Check-in race condition | Two staff scan same ticket simultaneously | One succeeds, one gets error |
| CHK-06 | Check-in status persistence | Check in, logout, login, verify status | Status remains "checked" |

---

## 6. Voucher Management Tests

| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| VCH-01 | Duplicate voucher code | Create voucher with existing code | Error: Code already exists |
| VCH-02 | Percentage > 100% | Create voucher with 150% discount | Validation error |
| VCH-03 | Negative discount | Create voucher with -10% discount | Validation error |
| VCH-04 | Zero quota voucher | Create voucher with quota 0 | Cannot be used |
| VCH-05 | Voucher case sensitivity | Code "DISCOUNT" vs "discount" | Case insensitive match |
| VCH-06 | Multiple vouchers | Try to apply 2 vouchers to one order | Only one applied or error |

---

## 7. Admin Panel Tests

### 7.1 CRUD Operations
| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| ADM-01 | Delete own admin account | Try to delete currently logged in admin | Error: Cannot delete own account |
| ADM-02 | Delete non-existent user | Delete user ID 99999 | Error: User not found |
| ADM-03 | Edit with invalid email | Change user email to "invalid" | Validation error |
| ADM-04 | Create venue with negative capacity | Enter capacity "-100" | Validation error |
| ADM-05 | XSS in venue name | Enter `<script>alert(1)</script>` | Name sanitized |
| ADM-06 | Delete venue with events | Try to delete venue that has events | Error: Venue in use |
| ADM-07 | Delete event with tickets/sales | Try to delete event with orders | Error: Event has orders |

### 7.2 Export Functionality
| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| EXP-01 | PDF export | Click PDF button on users page | PDF downloaded correctly |
| EXP-02 | Excel export | Click Excel button on orders page | Excel file downloaded correctly |
| EXP-03 | Export empty data | Export when table is empty | Empty but valid file |
| EXP-04 | Export with special characters | Data contains emoji or special chars | File generated correctly |

---

## 8. Session & Security Tests

| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| SEC-01 | Session timeout | Login, wait 30 min, try action | Redirect to login |
| SEC-02 | CSRF protection | Try POST without CSRF token | Request rejected |
| SEC-03 | Secure cookie flag | Inspect session cookie | HttpOnly and Secure flags |
| SEC-04 | Password hashing | Check database password field | Hashed, not plaintext |
| SEC-05 | SQL injection in search | Enter "' OR '1'='1" in search | Handled safely, no data leak |
| SEC-06 | File upload (if exists) | Upload PHP file as image | Rejected or sanitized |

---

## 9. Performance Tests

| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| PERF-01 | Load test events page | 100 concurrent users | Page loads < 3 seconds |
| PERF-02 | Database query optimization | View order with many attendees | Query uses indexes |
| PERF-03 | Large dataset pagination | Navigate page 100 of orders | Fast response |
| PERF-04 | QR code generation | Generate 100 QR codes | No memory issues |

---

## 10. Integration Tests

| ID | Scenario | Steps | Expected Result |
|----|----------|-------|-----------------|
| INT-01 | Full purchase flow | Register → Login → Browse → Buy → View Order → View Ticket | All steps work |
| INT-02 | Full check-in flow | Create order → Get ticket → Staff check-in → Verify status | Status updated correctly |
| INT-03 | Voucher workflow | Create voucher → User applies → Verify discount → Check quota | Quota decremented |
| INT-04 | Refund workflow | Cancel order → Verify quota restored | Quota back to original |

---

## Test Execution Status

| Category | Total | Passed | Failed | Pending |
|----------|-------|--------|--------|---------|
| Authentication | 7 | - | - | 7 |
| RBAC | 5 | - | - | 5 |
| Registration | 7 | - | - | 7 |
| Event/Ticket | 10 | - | - | 10 |
| Order Management | 6 | - | - | 6 |
| Check-in | 6 | - | - | 6 |
| Voucher | 6 | - | - | 6 |
| Admin CRUD | 7 | - | - | 7 |
| Export | 4 | - | - | 4 |
| Security | 6 | - | - | 6 |
| Performance | 4 | - | - | 4 |
| Integration | 4 | - | - | 4 |
| **TOTAL** | **72** | **-** | **-** | **72** |

---

## Notes

- Use Playwright MCP for automated browser testing
- Use SQL queries to verify database state
- Test with both valid and invalid data
- Always test security scenarios with malicious input
- Performance tests require load testing tools
