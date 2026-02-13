**Purpose**: Future features and improvements prioritized by value
**Last Updated**: 2025-02-13

# Feature Backlog

## Next Sprint (High Priority)

### External Equipment API Integration

- **Description**: Replace hardcoded `ToolController::defaultprojects()` with live ArkFleet projects API
- **User Value**: Accurate project list from fleet management system
- **Effort**: Medium
- **Dependencies**: URL_ARKFLEET env, API contract
- **Files Affected**: `app/Http/Controllers/ToolController.php`

### Dashboard Performance (implemented 2025-02-13)

- **Description**: Optimize DashboardController queries (N+1, eager loading) for large tyre datasets
- **Status**: Done — DashboardService with SQL aggregates, 10-min cache, model-based invalidation

## Upcoming Features (Medium Priority)

### User DataTables Permission Filter

- **Description**: Ensure non-superadmin users cannot see superadmin in DataTables
- **Effort**: Small
- **Value**: Security/compliance
- **Files Affected**: `app/Http/Controllers/UserController.php`, `resources/views/users/`

### Tyre Inactive Reason Enum Sync

- **Description**: Align `inactive_reason` enum across migrations, model, and forms (Scrap, Breakdown, Repair, Consignment Rotable, Spare)
- **Effort**: Small
- **Value**: Data consistency

## Ideas & Future Considerations (Low Priority)

### API Versioning

- **Concept**: Expose tyre/transaction data via REST API for mobile or external tools
- **Potential Value**: Integration with fleet systems, mobile apps
- **Complexity**: Medium

### Audit Trail

- **Concept**: Extend ActivityLog usage for key actions (tyre status changes, user changes)
- **Potential Value**: Compliance, debugging
- **Complexity**: Low–Medium

## Technical Improvements

### Performance & Code Quality

- Extract Dashboard rekap logic to a dedicated service class – **Done** (2025-02-13) – see `DashboardService`
- Add Form Request validation classes for TyreController, UserController – Impact: Medium
- Replace `$guarded = []` with `$fillable` in models – Impact: Low (security)

### Infrastructure

- Docker/Sail for consistent dev environment
- Automated tests for TyreController, TransactionController flows
