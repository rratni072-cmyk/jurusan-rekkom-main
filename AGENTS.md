# AGENTS.md

Project: Website Jurusan Rekayasa dan Komputer, Politeknik Pertanian Negeri Samarinda.

This file is the Codex entry point for local project rules. Before doing any
non-trivial task, read `.windsurfrules` first, then read the detailed rule files
that match the task.

## Rule Loading Protocol

Always treat `.windsurfrules` as the rule index. It maps common task types to
the required detailed rules and workflows.

For task-specific work, read only the relevant files before editing or running
meaningful commands:

- Feature or CRUD work:
  - `.agents/rules/arsitektur-proyek.md`
  - `.agents/rules/kualitas-kode.md`
  - `.agents/rules/keamanan.md`
  - `.agents/rules/performance.md`
  - `.agents/rules/activity-log.md`
  - `.agents/workflows/new-feature.md`
- Debugging or bug fixing:
  - `.agents/rules/skill-debugging.md`
  - `.agents/rules/skill-verification.md`
- UI, frontend, or visual changes:
  - `.agents/rules/desain-ui.md`
  - `.agents/rules/responsive-mobile.md`
  - `.agents/rules/library-standard.md`
  - `.agents/rules/bahasa-indonesia.md`
  - `.agents/rules/a11y.md`
  - `.windsurf/rules/anti-ai-generated.md`
- Mobile or responsive work:
  - `.agents/rules/responsive-mobile.md`
  - `.agents/rules/desain-ui.md`
  - `.agents/rules/a11y.md`
- SEO or meta tags:
  - `.agents/rules/seo-meta.md`
  - `.agents/rules/bahasa-indonesia.md`
- Forms or validation:
  - `.agents/rules/validation-messages.md`
  - `.agents/rules/bahasa-indonesia.md`
  - `.agents/rules/keamanan.md`
- File upload or asset handling:
  - `.agents/rules/asset-management.md`
  - `.agents/rules/keamanan.md`
  - `.agents/rules/performance.md`
- Blade refactor:
  - `.agents/rules/blade-components.md`
- Tests:
  - `.agents/rules/testing-strategy.md`
  - `.agents/rules/skill-verification.md`
- Deployment:
  - `.agents/rules/deployment.md`
- Git work:
  - `.agents/rules/git-convention.md`
- Login testing:
  - `.agents/rules/akun-test.md`

If a matching workflow exists in `.agents/workflows/` or `.windsurf/workflows/`,
follow it.

## Project Architecture

The intended architecture is:

```text
Routes -> Middleware -> Controller -> Form Request -> Repository -> Model -> DB
```

Keep controllers light. Put query and business logic in repositories or services
when the logic is more than simple orchestration.

Use these UI domains:

- Public frontend: Eterna template, Bootstrap 5, views under `resources/views/frontend/`.
- Admin panel: CoreUI, Bootstrap 5, views under `resources/views/admin/`, protected by `auth`.

## Non-Negotiable Constraints

- Do not mix Tailwind with Bootstrap.
- Do not add React, Vue, Livewire, Alpine.js, Select2, or Summernote.
- Use Laravel Form Request classes for validation.
- Use explicit model `$fillable`; do not use `$guarded = []`.
- Use eager loading for relations rendered in lists.
- Use pagination for public list pages.
- Keep UI copy in formal Bahasa Indonesia.
- Do not invent institution facts, accreditation data, officer names, or claims.
- Use official identity from `.windsurf/rules/identitas-website.md`.
- Use Bootstrap Icons, not decorative emoji, for UI icons.
- Public website must look like an official higher-education website, not a generic AI landing page.
- Admin mutations must be logged with Spatie activity log conventions.
- Do not hardcode credentials in source code.
- Do not commit `.env`, `vendor/`, `node_modules/`, or mockup HTML.

## Verification Requirements

Do not claim a task is complete without evidence appropriate to the change.

Required checks when applicable:

- PHP file edited: run `php vendor/laravel/pint/builds/pint <file>`.
- Broad PHP change: run `php vendor/laravel/pint/builds/pint --test`.
- Feature or bug fix: run focused tests first, then broader tests when feasible.
- Route changes: run `php artisan route:list --except-vendor`.
- Visual/frontend changes: verify manually in browser or with screenshots at relevant breakpoints.

If a baseline check already fails for unrelated reasons, report the existing
failure clearly and verify the changed scope as far as possible.

## Current Baseline Notes

As of the last project context read, the repository has many existing modified
and untracked files. Do not revert unrelated changes.

Known baseline issues observed:

- `php artisan route:list --except-vendor` succeeds.
- `php artisan test` has existing failures in auth/profile/home/dashboard tests.
- `php vendor/laravel/pint/builds/pint --test` has existing style issues.

Re-check these before relying on them, because the worktree may change.
