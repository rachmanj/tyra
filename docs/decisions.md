**Purpose**: Record technical decisions and rationale for future reference
**Last Updated**: 2025-02-13

# Technical Decision Records

## Decision Template

Decision: [Title] - [YYYY-MM-DD]

**Context**: [What situation led to this decision?]

**Options Considered**:

1. **Option A**: [Description]
   - ✅ Pros: [Benefits]
   - ❌ Cons: [Drawbacks]
2. **Option B**: [Description]
   - ✅ Pros: [Benefits]
   - ❌ Cons: [Drawbacks]

**Decision**: [What we chose]

**Rationale**: [Why we chose this option]

**Implementation**: [How this affects the codebase]

**Review Date**: [When to revisit this decision]

---

## Recent Decisions

### D1: Spatie Laravel Permission for RBAC - 2023

**Context**: Need role-based access control (superadmin, admin, user) for Tyre Management System.

**Options Considered**:

1. **Custom roles/permissions**: Tables and middleware built from scratch
   - ✅ Pros: Full control
   - ❌ Cons: Reinventing wheel, maintenance burden
2. **Spatie Laravel Permission**: Established package
   - ✅ Pros: Battle-tested, sync with Laravel, easy to use
   - ❌ Cons: Extra dependency

**Decision**: Use Spatie Laravel Permission

**Rationale**: Faster implementation, well-maintained, fits Laravel ecosystem.

**Implementation**: `User` model uses `HasRoles`; `UserController::data()` filters by role; routes protected by auth middleware.

**Review Date**: 2026-02

---

### D2: ToolController for Shared Tyre/Transaction Helpers - 2023

**Context**: TyreController and TransactionController need shared logic (getLastTransaction, getProjects, getEquipments).

**Options Considered**:

1. **Trait**: Shared trait for both controllers
   - ✅ Pros: Reusable
   - ❌ Cons: Multiple controllers depending on trait
2. **ToolController**: Dedicated controller with helper methods
   - ✅ Pros: Single place, injectable, can be called from views/routes
   - ❌ Cons: Mix of responsibilities (API calls + helpers)

**Decision**: ToolController with helper methods and Guzzle for external API

**Rationale**: Existing pattern; routes already use `ToolController::getLastHm`; `getProjects()` and `getEquipments()` centralize external calls.

**Implementation**: `app/Http/Controllers/ToolController.php`, injected into TyreController constructor; `UserController::data()` uses `app(ToolController::class)->getUserRoles()`.

**Review Date**: 2025-09

---

### D3: UHM Transaction Type for HM-Only Updates - 2024

**Context**: Need to record hour meter updates without full ON/OFF transaction.

**Decision**: Add `UHM` to `transactions.tx_type` enum (ON, OFF, UHM)

**Implementation**: Migration `2024_02_22_add_hm_to_tx_type_enum.php`; `TransactionController::updateHm` creates UHM transactions.

**Review Date**: 2026-02

---

### D4: Laravel 11 Skeleton (Providers, Middleware) - 2025

**Context**: Project uses Laravel 10 but structure follows Laravel 11 patterns where applicable.

**Decision**: Use `bootstrap/app.php` for middleware; avoid creating extra service providers unless necessary; register in `bootstrap/providers.php` if needed.

**Implementation**: Per `.cursorrules` and user rules; `app/Http/Kernel.php` still present (Laravel 10).

**Review Date**: When upgrading to Laravel 11

---

### D5: Dashboard Service with Caching and Project Filtering - 2025-02-13

**Context**: Dashboard had N+1 queries, duplicate data loading, hardcoded projects, and no role-based filtering. Need to improve performance and security for non-admin users.

**Options Considered**:

1. **In-controller caching**: Cache in DashboardController
   - ✅ Pros: Simple
   - ❌ Cons: Controller bloat, mixed concerns
2. **DashboardService with cache**: Service class handles aggregates and caching
   - ✅ Pros: Single responsibility, testable, reusable
   - ❌ Cons: Extra class

3. **Project filtering**: All users see all projects vs. role-based
   - ✅ Role-based (superadmin/admin see all, others see `auth()->user()->project`): Aligns with TyreController
   - ❌ All see all: Security mismatch for non-admin users

**Decision**: `DashboardService` with 10-min cache, model-based invalidation (Tyre/Transaction booted), and `DashboardController::getProjectsForUser()` for role-based project filtering

**Rationale**: Matches existing TyreController pattern; cache TTL balances freshness vs. load; config-based projects avoid code edits for project list changes.

**Implementation**: `app/Services/DashboardService.php`; `config/tyra.php` for `dashboard_projects`; `DashboardController::getProjectsForUser()`; `Tyre::booted()` and `Transaction::booted()` call `DashboardService::clearDashboardCache()`.

**Review Date**: 2026-02

---

### D6: Dashboard Projects in Config - 2025-02-13

**Context**: Dashboard projects hardcoded in multiple places; `ToolController::defaultprojects()` has different structure.

**Decision**: Add `config/tyra.php` with `dashboard_projects` array for dashboard project codes. `DashboardService::getDashboardProjects()` reads from config with fallback to constant.

**Implementation**: `config/tyra.php`; `DashboardService::DASHBOARD_PROJECTS` kept as fallback.

**Review Date**: 2026-02
