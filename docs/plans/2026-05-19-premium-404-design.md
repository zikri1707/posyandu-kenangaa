# Design Specification: Premium Standalone 404 Error Page

## Overview
Re-engineer the default 404 page into a premium, standalone, guest-safe error page matching the TailAdmin HTML template styling.

## Layout Structure
* **Container**: A relative, flex-column, screen-centered container (`min-h-screen`) styled for both light and dark modes.
* **Grid Background (`GridShape`)**:
  - Embedded as inline SVG paths at the top-right and bottom-left (rotated 180 degrees) with z-index positioning (`-z-10`).
* **Visual Components**:
  - Main text heading: `"ERROR"` in bold uppercase.
  - Light mode SVG: Inline version of `404.svg` using `#465FFF` (standard blue) fill colors, hidden in dark mode (`dark:hidden`).
  - Dark mode SVG: Inline version of `404-dark.svg` using `#7592FF` (light blue) fill colors, hidden in light mode (`hidden dark:block`).
* **Content Text**:
  - Description: `"We can’t seem to find the page you are looking for!"`
* **CTA Button**:
  - Styled link to `/` (home page) with white backgrounds, border-slate-300, shadow hover adjustments, and dark mode compliance.
* **Footer**:
  - Absolute bottom center copyright notice utilizing Laravel/PHP `date('Y')`.

## Security & Reliability
- Standalone page: By not extending `layouts.app` or any admin layout, we prevent Laravel layout rendering errors (like `isSuperAdmin() on null` for unauthenticated visitors).
- Tailwind and asset compilation: Using Vite asset bundling `@vite(['resources/css/app.css', 'resources/js/app.js'])`.
