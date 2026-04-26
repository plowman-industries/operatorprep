# OperatorPrep — Site Repair Plan

This plan breaks `specs/site-audit.html` into deployable batches. One batch = one git commit. Execute in order; do not skip ahead.

**Status:** Not started. Awaiting first Claude Code session to confirm.

---

## How to use this file

1. Claude Code reads the site audit at `specs/site-audit.html` and confirms the issue count matches what's below.
2. Work through batches in order (P0 → P1 → P2 → P3).
3. After each batch: diff → human review → commit → update this file's status column.
4. Never merge multiple batches into one commit. Never skip the human review.

---

## Batches

### Batch 1 — Footer fixes (P0)
**Audit items:** A-01, A-03, A-10
**Files that change:** WordPress admin settings (no code edit), possibly `wp-theme/style.css` for fallback CSS
**Approach:**
- A-01: Disable Astra default footer via Customizer (Footer Builder → remove Copyright module)
- A-03: Fix broken `/certification/t1` links in footer HTML block → change to `/t1/`
- A-10: Resolves automatically with A-01
**Verification:** Load home page and one interior page; confirm only ONE footer renders; confirm all footer links return 200 (not 404)
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-01, A-03, A-10 fix footer duplication and broken cert links per design system v2.1 §4.6`

---

### Batch 2 — Homepage emoji removal (P0)
**Audit items:** A-02
**Files that change:** Homepage Custom HTML blocks, possibly `wp-theme/header.php` to inject the SVG sprite globally
**Approach:**
- Inject the SVG sprite from `specs/design-system.html` into `header.php` (or a global HTML block) so `<use href="#i-xxx">` works sitewide
- Replace every emoji on the home page with the corresponding SVG icon
- Mapping from audit A-02: 🎖️→i-certified, 💧→i-droplet, ⚡→i-power, 📍→i-location, 📝→i-test, 🃏→i-flashcard, 🔢→i-math, 📖→i-guide, 🔧→i-pipe, ⚙️→i-basin
**Verification:** View source on home page; confirm no emoji characters remain; confirm icons render correctly on Chrome, Safari, mobile
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-02 replace homepage emojis with SVG icons per brand guidelines v1.1 §05`

---

### Batch 3 — Navigation cleanup (P1)
**Audit items:** A-05, A-11, A-12
**Files that change:** WP nav menus, possibly `wp-theme/style.css`
**Approach:**
- A-05: Create `/certifications/` overview page (new WP page) listing all 15 certs grouped by track. Point nav "Certifications" there; keep "Pricing" pointing to `/pricing/`
- A-11: Hide Astra default page title on homepage via Customizer
- A-12: Remove stray Shopping Cart widget from widget areas
**Verification:** Click every nav item and confirm it goes to the expected page; confirm homepage has only one H1
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-05, A-11, A-12 nav and homepage semantic cleanup`

---

### Batch 4 — Duplicate page cleanup (P1)
**Audit items:** A-06
**Files that change:** WP pages (delete or redirect)
**Approach:**
- Identify which T4 Flashcards page has current content and correct subscription gating
- Delete the other or 301-redirect it via the Redirection plugin
- Audit other grades for similar `-2` suffixed duplicates
**Verification:** `/t4-flashcards-2/` either returns 301 or 404; no other `-N` suffixed duplicate pages exist
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-06 resolve duplicate T4 Flashcards page, audit for other duplicates`

---

### Batch 5 — Logo swap (P1)
**Audit items:** A-04
**Files that change:** WP Customizer (Site Identity), possibly install Safe SVG plugin
**Approach:**
- Install Safe SVG plugin if not already present (Astra free does not allow SVG uploads by default)
- Upload `specs/logo/brand-mark.svg` via Media Library
- Replace header logo in Customizer → Site Identity
**Verification:** Logo renders crisp on retina, scales on mobile, uses `currentColor` correctly in header and footer contexts
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-04 replace PNG logo banner with SVG brand mark`

---

### Batch 6 — Practice-test template rebuild (P1)
**Audit items:** A-07
**Files that change:** 15 WP pages (one per grade's practice-tests page) OR a WP shortcode/block that renders the template
**Approach:**
- Read `specs/design-system.html` Section 4.1 for the locked anatomy
- Build reusable HTML template matching the v2.1 study-dashboard pattern: page header → stats bar → mastery meter → mastery legend → Full Test standalone card → "Or Drill a Specific Category" label → category grid (4×2)
- Apply to T2 page first; verify; then replicate to other 14 grade pages
- Each page needs: grade-specific topic list, grade-specific stats (dynamic from quiz data if possible, hardcoded for MVP)
**Verification:** Rendered page matches the preview at `specs/operatorprep-v2.1-practice-test-preview.png`; mastery stripe colors reflect actual user progress; all links route to the right quiz endpoints
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-07 rebuild practice-test template per design system v2.1 §4.1`

---

### Batch 7 — Footer sitemap removal (P2)
**Audit items:** A-08
**Files that change:** WP Widgets
**Approach:** Remove the flat sitemap widget from wherever it's currently living
**Verification:** View source of home page; confirm only the 4-column curated footer renders; the 90+ URL list is gone
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-08 remove auto-generated flat sitemap from footer`

---

### Batch 8 — Coverage gap audit (P2)
**Audit items:** A-09
**Files that change:** WP pages (new), possibly `wp-theme/dashboard.php`
**Approach:**
- Build a 15×4 coverage matrix (grades × tools)
- Identify gaps (confirmed: T5 has no Math Drills; WW5 has no Math Drills)
- Decide per gap: build the missing page OR hide the dashboard link for that cell
- Document the supported tool matrix in the design system so new grades always get full coverage
**Verification:** Click every cell on the dashboard; no 404s; any hidden cells have a reason documented
**Status:** ☐ Not started
**Commit message:** `fix(audit): A-09 resolve tool coverage gaps across grades`

---

## Not in the audit but should happen soon

- **Update `specs/page-consistency-cheatsheet.html`** — currently references v1.2 components; should be updated to point at v2.1 study-dashboard anatomy
- **Update `specs/design-system.html` §4.2 and §4.3** (Flashcards and Math Drills) to use the same study-dashboard model as §4.1; currently they still describe the old hero-plus-grid structure

These are design system hygiene tasks, not site bugs. Do them in a separate branch, bump design system to v2.2.

---

_Append updates below. Do not rewrite history._
