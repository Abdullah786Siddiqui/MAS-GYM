# MAS GYM Performance Engineering - Progress Tracker

## Current Status
- **Current Phase:** Phase 1: Bundle Size and Code Splitting
- **Current Step:** Phase 1 Completed
- **Status:** Done (Awaiting User Review and Prompt for Phase 2)

---

## Metrics Benchmark

### Phase 0 Baseline (Before Any Optimizations)
- **Total JS (uncompressed):** 485.01 kB (~155 kB gzip)
- **Total CSS (uncompressed):** 89.90 kB (~13.84 kB gzip)
- **Total Vite Build Size:** 574.91 kB (~169.3 kB gzip)
- **Main Entry Bundle (`app.js`):** 288.74 kB (104.35 kB gzip)
- **Top Asset Chunks:**
  - `assets/app-DiT967ey.js`: 288.74 kB (gzip: 104.35 kB)
  - `assets/app-BxsMGOjK.css`: 88.19 kB (gzip: 13.84 kB)
  - `assets/Assign-rep9LWbR.js`: 64.15 kB (gzip: 20.68 kB)
  - `assets/Index-Bvll3uiW.js` (Exercises): 19.85 kB (gzip: 5.70 kB)
  - `assets/AuthenticatedLayout-ALrM17NS.js`: 15.03 kB (gzip: 3.83 kB)
  - `assets/Index-Dq6noawf.js` (MuscleGroups): 13.42 kB (gzip: 4.13 kB)
  - FontAwesome Pro `all.min.css`: 511.99 kB
  - FontAwesome Pro Webfonts: 745.99 kB

### Phase 1 Results (After Code Splitting & Cleanup)
- **Main Entry Bundle (`app.js`):** **20.26 kB** (gzip: **7.95 kB**) ➔ **92.8% reduction!**
- **Vendor Chunks Isolated:**
  - `assets/vendor-inertia-DD4xQ8Nh.js`: 187.58 kB (gzip: 64.14 kB)
  - `assets/vendor-vue-5CGyIjPV.js`: 80.78 kB (gzip: 32.61 kB)
- **Total JS (uncompressed):** 485.83 kB (gzip: ~155 kB)
- **Total CSS (uncompressed):** 87.09 kB (gzip: 13.55 kB) — *Verified: all Tailwind classes intact*
- **Total Vite Build Size:** 572.92 kB (gzip: ~168.5 kB)

---

## Phase 1 Step Breakdown

| Step | Task | Status | Files Changed / Notes |
| :--- | :--- | :--- | :--- |
| **1.1** | Create & maintain `PERF_PROGRESS.md` | Completed | `PERF_PROGRESS.md` |
| **1.2** | Verify Inertia page resolver lazy-loading | Completed | Verified `import.meta.glob('./Pages/**/*.vue')` in `resources/js/app.js` creates dynamic lazy imports (`isDynamicEntry: true` on all 15 page components). |
| **1.3** | Identify unused dependencies, dead components, and unused imports | Completed | Audited repository and presented list to user. |
| **1.4** | Clean up confirmed unused imports and dead components | Completed | Removed unused `StatsCards` imports in `Exercises/Index.vue` and `MuscleGroups/Index.vue`. Removed deleted components: `ApplicationLogo.vue`, `Checkbox.vue`, `StatsCards.vue`. Kept `Components/Gym/*` and `@tailwindcss/vite` per user instruction. |
| **1.5** | Evaluate `defineAsyncComponent` for heavy components | Completed | Preserved synchronous modal mounts inside page chunks to prevent 0ms dialog flash / broken `<dialog>` state. Verified `vue-draggable-plus` (57 kB) is already isolated strictly to `Assign.vue` chunk. |
| **1.6** | Verify per-function imports & tree-shaking | Completed | All imports from `vue` and `@inertiajs/vue3` use named per-function syntax. |
| **1.7** | Configure `vite.config.js` | Completed | Added `target: 'es2022'`, `cssCodeSplit: true`, `sourcemap: false`, `minify: 'esbuild'`, and `manualChunks` separating `@vue` and `@inertiajs`. |
| **1.8** | Verify Tailwind unused class purging | Completed | Verified `content` array in `tailwind.config.js` purges unused classes; CSS output matches baseline (`88.07 kB` vs `88.19 kB`). |
| **1.9** | Run `npm run build` & confirm zero errors | Completed | Build passed in 3.71s with 0 errors/warnings. |

---

## Deleted Files Log (Restore Points)
The following files were removed in Step 1.4:
1. `resources/js/Components/ApplicationLogo.vue` (Unused wrapper, `AppLogo.vue` is used directly)
2. `resources/js/Components/Checkbox.vue` (Unused wrapper, native `<input type="checkbox">` used)
3. `resources/js/Components/StatsCards.vue` (Unused component; cards rendered inline)

*Note: All files in `resources/js/Components/Gym/` and package `@tailwindcss/vite` were intentionally preserved per user instruction.*

---

## Decisions Made
1. **Vendor Chunk Isolation:** Split `vendor-vue` and `vendor-inertia` so core framework code is cached permanently across application deployments.
2. **Preserve Synchronous Modals in Page Chunks:** Modals in `Exercises` and `MuscleGroups` are already inside isolated page chunks (`Index-*.js`), so keeping them synchronous avoids `<dialog>` transition timing issues and ensures instant opening.
3. **Tailwind Stability:** Maintained active `tailwind.config.js` PurgeCSS configuration so CSS size and visual rendering remain identical.

---

## Things Intentionally NOT Changed
- Preserved all files in `resources/js/Components/Gym/` for future features as requested.
- Kept `@tailwindcss/vite` in `package.json` as requested.
- Database schemas and Eloquent queries (strictly preserved).
- Visual styling, Tailwind color palette, layout, and component markup (strictly preserved).
- FontAwesome icon assets (deferred to Phase 2: Assets).

---

## Known Issues & Warnings
- `public/favicon.ico` is currently 0 bytes (addressed in Phase 2).
- FontAwesome Pro 512 kB `all.min.css` is in `<head>` (addressed in Phase 2).

---

## Next Action
Phase 1 complete. Awaiting user prompt for Phase 2: Assets (Fonts, Images, Icons).
