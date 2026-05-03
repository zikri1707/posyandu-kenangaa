# Design Document: Global & Local Search Improvements

**Date:** 2026-05-03
**Topic:** Improving Search Bars with Instant Dropdown & Premium UI

## Overview
This design aims to replace the current static global search with a Livewire-powered instant search dropdown and standardize the visual design of all search bars across the admin dashboard to a "Premium" aesthetic.

## Architecture

### Components
- **`App\Livewire\GlobalSearch`**: Core Livewire component for cross-model searching.
- **`resources/views/livewire/global-search.blade.php`**: Frontend UI for the global search with a results dropdown.
- **`resources/views/components/layouts/ui/navbar.blade.php`**: Integration point for the global search component.

### Data Flow
1. User types in the global search input.
2. Livewire syncs the `search` property with a 300ms debounce.
3. The component queries `Patient`, `Schedule`, and `Article` models.
4. Results are grouped by category and displayed in a floating dropdown.
5. Clicking a result redirects the user to the specific resource.

## Visual Design (Premium)
- **Instant Search Dropdown**: 
    - Floating card with `backdrop-blur-md` and `shadow-2xl`.
    - Distinct category headers (Patients, Schedules, Articles).
    - Result items with icons and metadata.
    - Keyboard shortcut `/` to focus.
- **Local Search (Modules)**:
    - Standardized focus effects (Glow, expansion).
    - Inline loading spinners during Livewire sync.
    - Clear button (X) for quick reset.

## Implementation Details
- **Global Search**:
    - Query optimization using `limit(5)` per category.
    - Result formatting (highlights or clear metadata).
- **Styling**:
    - Shared CSS classes or utility patterns for "Premium Search" across all modules.

## Success Criteria
- Global search is interactive and provides real-time feedback.
- All search bars in the dashboard share a consistent, high-end look and feel.
- Performance remains high with proper debouncing and optimized queries.
