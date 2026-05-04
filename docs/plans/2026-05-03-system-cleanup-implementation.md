# System Cleanup & Rebranding Implementation Plan

> **For Antigravity:** REQUIRED SUB-SKILL: Load executing-plans to implement this plan task-by-task.

**Goal:** Rebrand the repository to `Alarave` and remove unused tests and empty directories.

**Architecture:** This is a maintenance and configuration task involving file deletions, text replacements, and workflow adjustments.

**Tech Stack:** Git, PHP/Laravel, GitHub Actions.

---

### Task 1: Rebrand Repository References

**Files:**
- Modify: `README.md`
- Modify: `composer.json`

**Step 1: Update README.md URLs**
Replace `ahmadrizal1st/posyandu-admin-dashboard` with `Alarave/posyandu-admin`.
Expected: All links and clone instructions point to the correct repo.

**Step 2: Update composer.json name**
Change `"name": "laravel/livewire-starter-kit"` to `"name": "alarave/posyandu-admin"`.

**Step 3: Commit rebranding**
Run: `git add README.md composer.json; git commit -m "chore: rebrand repository to alarave/posyandu-admin"`

---

### Task 2: Remove Unused Test Files

**Files:**
- Delete: `tests/Feature/ExampleTest.php`
- Delete: `tests/Unit/ExampleTest.php`
- Delete: `tests/Feature/DashboardTest.php`

**Step 1: Delete the files**
Run: `rm tests/Feature/ExampleTest.php, tests/Unit/ExampleTest.php, tests/Feature/DashboardTest.php`
Expected: Files are removed from the filesystem.

**Step 2: Run remaining tests to verify integrity**
Run: `php artisan test`
Expected: All remaining tests pass.

**Step 3: Commit deletions**
Run: `git add tests; git commit -m "cleanup: remove redundant and example test files"`

---

### Task 3: Cleanup Empty Directories and Workflows

**Files:**
- Delete: `app/Livewire/Misc`
- Modify: `.github/workflows/lint.yml`
- Modify: `.github/workflows/tests.yml`

**Step 1: Remove empty Misc directory**
Run: `rmdir app/Livewire/Misc` (if empty)

**Step 2: Remove Flux steps from lint.yml**
Remove the "Add Flux Credentials" step.

**Step 3: Remove Flux steps from tests.yml**
Remove the "Add Flux Credentials" step.

**Step 4: Commit cleanup**
Run: `git add app/Livewire .github/workflows; git commit -m "cleanup: remove empty directories and flux workflow steps"`
