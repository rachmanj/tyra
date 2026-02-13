**Purpose**: Track current work and immediate priorities
**Last Updated**: 2025-02-13

## Task Management Guidelines

### Entry Format

Each task entry must follow this format:
[status] priority: task description [context] (completed: YYYY-MM-DD)

### Context Information

Include relevant context in brackets to help with future AI-assisted coding:

- **Files**: `[src/components/Search.tsx:45]` - specific file and line numbers
- **Functions**: `[handleSearch(), validateInput()]` - relevant function names
- **APIs**: `[/api/jobs/search, POST /api/profile]` - API endpoints
- **Database**: `[job_results table, profiles.skills column]` - tables/columns
- **Error Messages**: `["Unexpected token '<'", "404 Page Not Found"]` - exact errors
- **Dependencies**: `[blocked by auth system, needs API key]` - blockers

### Status Options

- `[ ]` - pending/not started
- `[WIP]` - work in progress
- `[blocked]` - blocked by dependency
- `[testing]` - testing in progress
- `[done]` - completed (add completion date)

### Priority Levels

- `P0` - Critical (app won't work without this)
- `P1` - Important (significantly impacts user experience)
- `P2` - Nice to have (improvements and polish)
- `P3` - Future (ideas for later)

---

# Current Tasks

## Working On Now

- `[ ] P2: Review and stabilize modified controllers [app/Http/Controllers/DashboardController.php, ToolController.php, UserController.php]`

## Up Next (This Week)

- `[ ] P2: Verify .cursorrules database reference (dds_backend vs tyra/laravel) [.cursorrules, config/database.php]`

## Blocked/Waiting

- _(none)_

## Recently Completed

- `[done] P2: Dashboard improvements – chart fix, caching, config, UX, project filtering [DashboardController, DashboardService, config/tyra.php, views] (completed: 2025-02-13)`
- `[done] P2: Documentation allocation per .cursorrules [docs/architecture.md, todo.md, backlog.md, decisions.md, MEMORY.md] (completed: 2025-02-13)`

## Quick Notes

- Git status shows modified: DashboardController, ToolController, UserController
- Application is live; changes must preserve existing behavior
- See `docs/architecture.md` for system overview and `docs/backlog.md` for future ideas
- Dashboard improvements (2025-02-13): documented in `docs/architecture.md`, `docs/decisions.md` D5/D6, `docs/backlog.md`, `MEMORY.md` [3]
