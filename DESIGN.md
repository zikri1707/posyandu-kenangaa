# Design System: Posyandu Care Analytics

## 1. Visual Theme & Atmosphere
A balanced, modern data-centric interface with a structural density of 6 and fluid CSS motion. The atmosphere is analytical, clinical, and highly structured, prioritizing deep data visibility. It balances high-tech data visualization with human-centered healthcare services through soft glassmorphism, subtle gradients, and rounded geometry.

## 2. Color Palette & Roles
- **Canvas Base** (#faf8ff) — Primary background surface, providing a soft, extremely light indigo-tinted foundation.
- **Glass Surface** (rgba(255, 255, 255, 0.9)) — Modals, floating headers, and top-level cards utilizing a 12px backdrop blur.
- **Deep Ink** (#131b2e) — Primary text, high-contrast headings, and tooltip backgrounds.
- **Muted Outline** (#bcc9c6 / #e2e8f0) — Subtle borders, grid dividers, and secondary container edges.
- **Clinical Emerald** (#00685f) — Primary accent for main actions, positive health trends (e.g., Gizi Baik), and active states.
- **Analytical Indigo** (#4648d4) — Secondary accent for data categorization (e.g., Lansia) and secondary indicators.
- **Alert Rose** (#ba0035 / #ba1a1a) — Critical alerts, negative trends (e.g., Stunting), and high-risk indicators.

## 3. Typography Rules
- **Display/Headlines:** Outfit — Used for massive numeric data points and section headers. High weight (bold/black), track-tight spacing.
- **Body/Data:** Outfit — Clean, geometric readability for data tables, chart legends, and clinical notes. Muted text (#3d4947) for dense metadata.
- **Labels:** Outfit — Uppercase, highly tracked (`tracking-widest`), bold text used for table headers and tiny metadata tags.
- **Banned:** Inter, standard system sans-serifs, and all serif fonts.

## 4. Component Stylings
- **Buttons/Tabs:** Pill-shaped (`rounded-full`) or highly rounded (`rounded-xl`). Primary buttons use solid Clinical Emerald without outer glows. Active tabs use filled backgrounds, inactive tabs rely on hover states.
- **Cards:** Clean white or `surface-container-lowest` backgrounds with generous 24px (`rounded-3xl`) or 16px (`rounded-2xl`) corners. 1px borders. Hover states invoke soft shadows or slight scale lifts.
- **Inputs & Filters:** Unified control cards featuring borderless inputs/selects on subtle gray backgrounds. Focus rings leverage Clinical Emerald.
- **Tables:** Clean, border-bottom only rows with highly padded cells. Headers use uppercase tracked typography.
- **Charts:** Trend lines use thick 3px strokes with 10% opacity area gradient fills. Donut charts use a 75% cutout for an airy, precise look. Legends must use custom HTML/CSS points, not default canvas boxes.

## 5. Layout Principles
- **Grid Architecture:** CSS Grid-first approach. 4-column structures for top-level metrics, expanding to asymmetric splits (e.g., 1/3 and 2/3) for complex chart layouts.
- **Dashboard Structure:** Persistent fixed left sidebar (280px) and sticky top glassmorphic header.
- **Spatial Separation:** Generous padding inside cards (`p-6`, `p-8`). No cramped data cells.
- **Scrollbars:** Custom minimal scrollbars (6px-8px width) with rounded thumbs (`#cbd5e1`), replacing default browser styling.

## 6. Motion & Interaction
- **Perpetual Micro-Interactions:** Subtle, very slow floating animations on background aesthetic elements (e.g., 20s infinite alternate transforms).
- **Hover Physics:** Interactive buttons and table rows use standard Tailwind transitions (`transition-all`, `hover:bg-surface-container-low`) for tactile feedback.
- **Glassmorphism:** Navigation bars and specific control panels use `backdrop-blur-md` to maintain context over scrolling content.

## 7. Anti-Patterns (Banned)
- No emojis anywhere.
- No `Inter` or generic serif fonts.
- No pure black (`#000000`).
- No generic stock names in tables (use authentic, localized names like "Siti Aisyah").
- No unstyled standard browser scrollbars.
- No overlapping text without a highly contrasted solid or glassmorphic background.
- No default Chart.js styling (must customize tooltips, fonts, and grid lines).
