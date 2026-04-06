**Purpose**: AI's persistent knowledge base for project context and learnings
**Last Updated**: [Auto-updated by AI]

## Memory Maintenance Guidelines

### Structure Standards

- Entry Format: ### [ID] [Title (YYYY-MM-DD)] ✅ STATUS
- Required Fields: Date, Challenge/Decision, Solution, Key Learning
- Length Limit: 3-6 lines per entry (excluding sub-bullets)
- Status Indicators: ✅ COMPLETE, ⚠️ PARTIAL, ❌ BLOCKED

### Content Guidelines

- Focus: Architecture decisions, critical bugs, security fixes, major technical challenges
- Exclude: Routine features, minor bug fixes, documentation updates
- Learning: Each entry must include actionable learning or decision rationale
- Redundancy: Remove duplicate information, consolidate similar issues

### File Management

- Archive Trigger: When file exceeds 500 lines or 6 months old
- Archive Format: `memory-YYYY-MM.md` (e.g., `memory-2025-01.md`)
- New File: Start fresh with current date and carry forward only active decisions

---

## Project Memory Entries

### [3] Dashboard Improvements Plan (2025-02-13) ✅ COMPLETE

- **Implementations**: Fixed broken chart (canvas in `chart_card.blade.php`); added 10-min cache with Tyre/Transaction model invalidation; centralized projects in `config/tyra.php`; 4th mini box (inactive count), icons (fa-circle, fa-coins, fa-history, fa-archive), Rp. currency; responsive table wrapper; project-based filtering for non-admin users (superadmin/admin see all, others see `auth()->user()->project` only)
- **Key Files**: `config/tyra.php`, `DashboardService::getDashboardData($projects)`, `DashboardController::getProjectsForUser()`, `DashboardService::clearDashboardCache()` invoked from Tyre/Transaction booted
- **Cross-references**: See `docs/decisions.md` D5, D6; `docs/architecture.md` Dashboard Rekap Flow, Security

### [2] DashboardController Refactor (2025-02-13) ✅ COMPLETE

- **Challenge**: DashboardController had N+1 queries, duplicate data loading, hardcoded projects, mixed naming, business logic in controller
- **Solution**: Created `App\Services\DashboardService` with SQL aggregates (SUM, CASE) instead of loading collections; centralized `DASHBOARD_PROJECTS`; single per-dimension query for rekap data
- **Key Learning**: Use `selectRaw` with `SUM(CASE WHEN...)` for grouped aggregates instead of loading full collections and summing in PHP. View `mini_boxes` now receives `active_tyre_count` (int) instead of `active_tyres` (collection).

### [1] Documentation Allocation per .cursorrules (2025-02-13) ✅ COMPLETE

- **Challenge**: Codebase had placeholder docs; .cursorrules require living documentation in specific files
- **Solution**: Analyzed Tyra (Tyre Management System), populated `docs/architecture.md`, `docs/todo.md`, `docs/backlog.md`, `docs/decisions.md` with current state
- **Key Learning**: docs/architecture.md = current system state + Mermaid; docs/todo.md = immediate tasks; docs/backlog.md = future features; docs/decisions.md = technical decisions; MEMORY.md = significant learnings. Cross-reference between them.
