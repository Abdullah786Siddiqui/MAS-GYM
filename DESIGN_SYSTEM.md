# MAS GYM — Design System & Color Theme Specification

> **Official Design System & Brand Guidelines**  
> *Targeted for Developers, Designers, and AI Assistants (Antigravity, Cursor, Windsurf, Copilot, etc.)*  
> *All new components, pages, widgets, and layouts MUST strictly follow this specification.*

---

## 1. Brand Identity & Color Palette

MAS GYM uses a modern athletic aesthetic featuring high-energy **Emerald** and **Teal** gradients paired with deep, refined **Zinc** neutrals in both Dark and Light modes.

### 1.1 Primary Brand Colors

| Role | Color Name | Hex Code | RGB | Tailwind Class | Usage |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Primary Brand** | Emerald 500 | `#10b981` | `rgb(16, 185, 129)` | `emerald-500` | Main CTA buttons, active state accents, brand icons, logo badge |
| **Brand Hover / Dark** | Emerald 600 | `#059669` | `rgb(5, 150, 105)` | `emerald-600` | Button hover states, dark mode text contrast |
| **Brand Deep** | Emerald 700 | `#047857` | `rgb(4, 120, 87)` | `emerald-700` | Active button presses, deep gradients |
| **Accent / Gradient Stop** | Teal 500 | `#14b8a6` | `rgb(20, 184, 166)` | `teal-500` | Secondary gradient accent (`from-emerald-500 to-teal-600`) |
| **Accent Deep** | Teal 600 | `#0d9488` | `rgb(13, 148, 136)` | `teal-600` | Gradient end points, contrast icons |
| **Soft Tint (Light)** | Emerald 50 | `#ecfdf5` | `rgb(236, 253, 245)` | `bg-emerald-500/10` or `emerald-50` | Badge backgrounds, active item backdrops |
| **Soft Glow (Dark)** | Emerald 950 | `#022c22` | `rgb(2, 44, 34)` | `dark:bg-emerald-950/20` | Ambient glows, card tint in dark mode |

---

### 1.2 Neutral Palette (Zinc Foundation)

> **Important**: Always use `zinc` for grays. Do NOT mix with `gray`, `slate`, or `neutral`.

| Theme Mode | Layer | Tailwind Token | Hex Code | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| **Dark Mode** | App Canvas Background | `bg-zinc-950` | `#09090b` | Main application backdrop (`body`, root layout) |
| **Dark Mode** | Elevated Card / Container | `bg-zinc-900` | `#18181b` | Main cards, tables, modals, sidebars |
| **Dark Mode** | Interactive Surface | `bg-zinc-850` / `bg-zinc-800` | `#27272a` | Input fields, hover rows, secondary buttons |
| **Dark Mode** | Subtle Borders | `border-zinc-800` / `border-zinc-700/80` | `#27272a` / `#3f3f46` | Separators, card borders, table dividers |
| **Dark Mode** | Primary Heading / Text | `text-white` / `text-zinc-100` | `#ffffff` / `#f4f4f5` | Main titles, table headers, bold labels |
| **Dark Mode** | Body / Secondary Text | `text-zinc-400` | `#a1a1aa` | Descriptions, metadata, timestamps |
| **Light Mode** | App Canvas Background | `bg-zinc-50` / `bg-zinc-100/60` | `#fafafa` / `#f4f4f5` | Page body background |
| **Light Mode** | Elevated Card / Container | `bg-white` | `#ffffff` | Primary cards, modals, content boxes |
| **Light Mode** | Interactive Surface | `bg-zinc-50` / `bg-zinc-100` | `#f4f4f5` | Search inputs, table header strips |
| **Light Mode** | Subtle Borders | `border-zinc-200` / `border-zinc-100` | `#e4e4e7` / `#f4f4f5` | Clean borders, divide lines |
| **Light Mode** | Primary Heading / Text | `text-zinc-900` | `#18181b` | Main titles, bold labels |
| **Light Mode** | Body / Secondary Text | `text-zinc-500` / `text-zinc-600` | `#71717a` / `#52525b` | Secondary descriptions, subtext |

---

### 1.3 Semantic & Functional Colors

| Semantic State | Color Token | Hex | Badge Class |
| :--- | :--- | :--- | :--- |
| **Success / Active** | Emerald 500 | `#10b981` | `bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20` |
| **Danger / Remove / Delete** | Rose 500 | `#f43f5e` | `bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20` |
| **Warning / Pending** | Amber 500 | `#f59e0b` | `bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20` |
| **Info / Barbell Variant** | Teal 500 | `#14b8a6` | `bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20` |

---

## 2. Reusable Component Standards

### 2.1 Primary Action Buttons
```html
<button class="relative inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:from-emerald-600 hover:to-teal-700 hover:shadow-lg hover:shadow-emerald-500/30 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 active:scale-[0.98] transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed">
    <i class="fa-duotone fa-plus"></i>
    <span>Create Action</span>
</button>
```

### 2.2 Form Input Fields & Search Bars
```html
<input
    type="text"
    class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700/80 bg-zinc-50/70 dark:bg-zinc-800/60 px-3.5 py-2.5 text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:border-emerald-500 dark:focus:border-emerald-500 focus:bg-white dark:focus:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 shadow-sm"
/>
```

### 2.3 Status & Category Badges
```html
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20">
    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
    Active
</span>
```

### 2.4 Page Change Dumbbell Loader
- **Component**: `resources/js/Components/PageLoader.vue`
- **Behavior**: Mounted at root of `AuthenticatedLayout.vue` and `GuestLayout.vue`.
- **Triggers**: Inertia.js `router.on('start')` and `router.on('finish')` with 60ms debounce.
- **Visuals**: Center card with glowing ambient backdrop, animated vector dumbbell performing pumping bicep curls, "MAS FITNESS" branding, and shimmering emerald progress line.

---

## 3. Dark & Light Mode Rules

1. **Never use static dark text on dark surfaces**: Always provide `text-zinc-900 dark:text-white`.
2. **Never use static light text on light surfaces**: Always provide `text-zinc-500 dark:text-zinc-400`.
3. **Card Containers**: Must always be `bg-white dark:bg-zinc-900 border border-zinc-200/90 dark:border-zinc-800/90`.
4. **Icons**: Use FontAwesome Duotone (`fa-duotone`) for premium aesthetics, tinted with `text-emerald-500` or `text-zinc-400`.
5. **Focus Rings**: Always use `focus:border-emerald-500 focus:ring-emerald-500` (or `focus:ring-emerald-500/20`).

---

*File maintained automatically as part of the MAS GYM Brand Design System.*
