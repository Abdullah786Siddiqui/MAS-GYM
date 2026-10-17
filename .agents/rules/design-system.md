# MAS GYM — Design System & Theme Rules for AI Assistants

Whenever generating, modifying, or refactoring UI components, pages, widgets, or layouts for MAS GYM, you MUST strictly adhere to the following brand guidelines:

## 1. Brand Color Theme
- **Primary Brand**: Emerald (`emerald-500` / `#10b981`, hover `emerald-600` / `#059669`)
- **Secondary / Accent**: Teal (`teal-500` / `#14b8a6`, `teal-600` / `#0d9488`)
- **Primary Gradient**: `bg-gradient-to-r from-emerald-500 to-teal-600` (hover: `from-emerald-600 to-teal-700`)
- **Primary Ring / Focus**: `focus:border-emerald-500 focus:ring-emerald-500` or `focus:ring-emerald-500/20`
- **Tints / Badges**: `bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20`

## 2. Neutrals (Zinc Only)
- Never use `slate`, `gray`, or `neutral`. Always use `zinc`.
- **Dark Mode**:
  - Root Canvas: `bg-zinc-950` (`#09090b`)
  - Elevated Cards/Modals: `bg-zinc-900` (`#18181b`)
  - Elevated Surfaces/Inputs: `bg-zinc-850` / `bg-zinc-800`
  - Borders: `border-zinc-800` / `border-zinc-700/80`
  - Text Primary: `text-white` or `text-zinc-100`
  - Text Secondary: `text-zinc-400`
- **Light Mode**:
  - Root Canvas: `bg-zinc-50` (`#fafafa`)
  - Elevated Cards/Modals: `bg-white` (`#ffffff`)
  - Elevated Surfaces/Inputs: `bg-zinc-50` / `bg-zinc-100`
  - Borders: `border-zinc-200`
  - Text Primary: `text-zinc-900`
  - Text Secondary: `text-zinc-500`

## 3. Page Transitions
- MAS GYM uses an animated dumbbell vector loader (`resources/js/Components/PageLoader.vue`) mounted globally in `AuthenticatedLayout.vue` and `GuestLayout.vue`.
- Triggers on Inertia page visits (`router.on('start')` / `router.on('finish')`). Do not remove or bypass this loader.

See `DESIGN_SYSTEM.md` at the project root for full design tokens, component snippets, and hex values.
