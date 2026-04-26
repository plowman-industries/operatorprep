# OperatorPrep — Cowork Brief
## Task: Homepage Full Rebuild to Design System v2.1

---

## What you are doing

Rebuilding the [OperatorPrep.com](http://OperatorPrep.com) homepage so it fully matches the design system. The current homepage (`homepage-v2.html`) is a manually coded HTML file with hardcoded hex values and its own embedded `<style>` block that has never been connected to the design system. Patching individual hex values has not worked. The file needs a clean rewrite using the correct tokens, components, and structure.

---

## Site architecture

| Item | Detail |
|------|--------|
| Platform | WordPress with Astra theme |
| Child theme | `operatorprep-child` at `wp-content/themes/operatorprep-child/` |
| Homepage HTML | `wp-content/themes/operatorprep-child/homepage-v2.html` |
| Homepage CSS | `wp-content/themes/operatorprep-child/style.css` |
| Global fonts | Snippet 61 — loads Fraunces, Inter Tight, JetBrains Mono from Google Fonts |
| Global CSS tokens | Snippet 60 (or wp-custom-css) — defines `--c-ink`, `--c-signal`, `--c-mist` etc. |
| Deploy path | feature branch → PR → staging2.operatorprep.com → production |
| Repo | `~/Projects/operatorprep/` |
| Cache | SiteGround — flush after every deploy |

---

## Design system token reference

These are the ONLY values to use. No hardcoded hex values anywhere.

### Colors
```css
--c-ink:          #0b1220;   /* dark navy — page text, dark sections */
--c-ink-2:        #1a2332;   /* slightly lighter navy */
--c-steel:        #3a4a5c;   /* secondary text */
--c-steel-2:      #6b7a8c;   /* muted text, placeholders */
--c-paper:        #ffffff;   /* white — card backgrounds */
--c-mist:         #f4f6f8;   /* light gray — section backgrounds */
--c-fog:          #e4e8ed;   /* borders, dividers */
--c-signal:       #0f5ea8;   /* primary blue — CTAs, links, icons */
--c-signal-dark:  #0a4a87;   /* hover state for signal */
--c-signal-wash:  #e8f1fa;   /* light blue tint — subtle highlights */
--c-current:      #c85a1f;   /* rust/orange — CA badges, accents */
--c-current-wash: #fbeee5;   /* light rust tint */
--c-growth:       #2d6a4f;   /* green — mastered state */
--c-alert:        #a8321a;   /* error red */
```

### Typography
```css
--f-display: 'Fraunces', Georgia, serif;        /* headings */
--f-body:    'Inter Tight', system-ui, sans-serif; /* body text */
--f-mono:    'JetBrains Mono', monospace;        /* labels, eyebrows, code */
```

### Spacing (4px base)
```css
--sp-1: 4px;   --sp-2: 8px;   --sp-3: 12px;  --sp-4: 16px;
--sp-5: 20px;  --sp-6: 24px;  --sp-7: 32px;  --sp-8: 48px;
--sp-9: 64px;  --sp-10: 96px;
```

### Border radius
```css
--r-sm: 4px;   --r-md: 8px;   --r-lg: 12px;  --r-xl: 20px;
```

---

## Homepage section structure

Rebuild the homepage with these sections in this exact order. Each section's background is specified — do not deviate.

### Section 1 — Hero (DARK)
- Background: `var(--c-ink)` (#0b1220)
- Two-column layout: left = headline + CTA buttons, right = certifications card
- Headline: `Pass Your Certification. Know Your Plant.`
- Subhead: `7,000+ practice questions across 15 water and wastewater certifications — every answer backed by the field context an experienced operator would know. Not just the answer. The why.`
- Primary CTA button: `→ START STUDYING – $19.99` — background `var(--c-signal)`, color white
- Secondary CTA: `Free Formula Sheet` — ghost style, white border
- Right card: shows certification tracks (Water Treatment T1-T5, Water Distribution D1-D5, Wastewater WW1-WW5) with pricing starting from $19.99/mo and a "VIEW PLANS →" button
- Eyebrow above headline: `// BUILT BY OPERATORS, FOR OPERATORS` — font `var(--f-mono)`, color `var(--c-signal)`, small caps

### Section 2 — Stats bar (LIGHT)
- Background: `var(--c-paper)`
- Four stats in a row: `7,000+ Practice Questions` | `600+ Flashcards` | `15 Certifications` | `3 Cert Tracks`
- Stat numbers: font `var(--f-display)`, size 2.5rem, color `var(--c-ink)`
- Stat labels: font `var(--f-mono)`, size 0.75rem, uppercase, color `var(--c-steel)`

### Section 3 — Credentials bar (LIGHT)
- Background: `var(--c-signal-wash)` (#e8f1fa)
- Border top + bottom: 1px solid rgba(15,94,168,0.12)
- Four credential items in a row:
  - Developer Credentials: USMC Veteran · Passed CA Grade 5 Exam
  - Active Operator: Water Treatment Operator Grade 4 · Advanced Water Treatment Operator Grade 4 · Water Treatment Plant Operation Specialist Certificate
  - Specialized Certs: Distribution Operator · Hydraulic Specialist · E&I Technologist · Mechanical Technologist
  - Based In: Santa Barbara, CA · Plowman Industries LLC
- Icons: SVG icons from design system sprite, color `var(--c-signal)`
- Text: font `var(--f-body)`, color `var(--c-ink)`
- Label above each item: font `var(--f-mono)`, size 0.7rem, uppercase, color `var(--c-steel)`

### Section 4 — What's Included (LIGHT)
- Background: `var(--c-paper)`
- Eyebrow: `// WHAT'S INCLUDED`
- Heading: `Everything You Need to Pass.`
- Four feature cards in a row: Practice Tests | Flashcards | Math Drills | Study Guides
- Card background: `var(--c-paper)`, border 1px solid `var(--c-fog)`, border-radius `var(--r-md)`
- Each card: SVG icon (from sprite), label in mono, description, arrow link
- Links color: `var(--c-signal)`

### Section 5 — 15 Certifications (LIGHT)
- Background: `var(--c-mist)` (#f4f6f8)
- Eyebrow: `// CERTIFICATIONS`
- Heading: `15 Certifications. One Platform.`
- Subhead: `Choose your certification track and grade. Subscribe to unlock full access.`
- Three track cards side by side:
  - Water Treatment: T1–T4 at $19.99/mo, T5–CA at $29.99/mo
  - Water Distribution: D1–D4 at $19.99/mo, D5–CA at $29.99/mo
  - Wastewater Treatment: WW1–WW4 at $19.99/mo, WW5–CA at $29.99/mo
- Track card background: `var(--c-paper)`, border 1px solid `var(--c-fog)`
- Grade badges (standard): background `var(--c-signal)` (#0f5ea8), color white, font `var(--f-mono)`, border-radius `var(--r-sm)`
- Grade badges (CA): background `var(--c-current)` (#c85a1f), color white

### Section 6 — CTA (DARK — intentional, keep dark)
- Background: `var(--c-ink)` (#0b1220)
- Eyebrow: `// GET CERTIFIED`
- Heading: `Your exam is waiting.` + `So is your raise.` (second line in `var(--c-signal)`)
- Subtext: `Every question you skip today is a question you'll face on exam day. Study with context, not just correct answers.`
- CTA button: `→ START STUDYING TODAY` — background `var(--c-signal)`, color white

---

## The SVG icon sprite

The design system uses a custom SVG sprite. Every icon is referenced as:
```html
<svg><use href="#i-droplet"/></svg>
```

Available icons relevant to this page:
- `#i-certified` — shield/badge (use for Developer Credentials)
- `#i-droplet` — water drop (use for Active Operator / Water Treatment)
- `#i-power` — lightning (use for Specialized Certs)
- `#i-location` — pin (use for Based In)
- `#i-test` — document (use for Practice Tests)
- `#i-flashcard` — cards (use for Flashcards)
- `#i-math` — formula (use for Math Drills)
- `#i-guide` — book (use for Study Guides)
- `#i-pipe` — pipe (use for Water Distribution)
- `#i-basin` — tank (use for Wastewater)

The full sprite definition is in `specs/design-system.html` — search for `<svg xmlns="http://www.w3.org/2000/svg" style="display:none;">` and copy the entire sprite block into the top of the homepage HTML (or into `header.php`).

---

## The style.css fix required FIRST

Before rebuilding the HTML, fix this rule in `style.css`:

**Find:**
```css
.entry-content { background: var(--op-navy) !important }
```

**Replace with:**
```css
.entry-content { background: transparent; }
```

This `!important` override forces the entire WordPress content area dark regardless of what child elements set. It must be removed first or every background fix in the HTML will be overridden.

---

## What NOT to do

- **No hardcoded hex values** in the HTML or CSS. Every color must reference a `--c-*` token.
- **No emojis** anywhere. Icons come from the SVG sprite only.
- **No inline `style=""` attributes** on section wrappers. All styling goes in the `<style>` block or `style.css`.
- **No blank lines inside the `<style>` block** — WordPress's `wpautop()` filter injects `<p>` tags at blank lines inside style blocks, which breaks the CSS parser. Write all CSS rules without blank lines between them, or move the entire `<style>` block to `style.css` instead (preferred).
- **Do not touch** the practice-test pages, flashcard pages, math-drill pages, study-guide pages, pricing page, FAQ, About, or Contact. Homepage only.
- **Do not merge to main** without a visual check on staging2.operatorprep.com first.

---

## Preferred approach — move styles out of HTML

The cleanest fix is to move the homepage CSS entirely out of the embedded `<style>` block in `homepage-v2.html` and into a dedicated section in `style.css`. This eliminates the `wpautop()` blank-line injection problem permanently. Add a comment marker in `style.css`:

```css
/* ============================================================
   HOMEPAGE v2.1
   ============================================================ */
```

Then paste all the CSS rules into `style.css` under that marker. The `homepage-v2.html` file then contains only HTML — no `<style>` block at all.

---

## Deploy checklist

Before merging to main, confirm on staging2.operatorprep.com:

- [ ] Hero section — dark ink background, light text, correct fonts
- [ ] Stats bar — white background, four stats visible
- [ ] Credentials bar — light blue (#e8f1fa) background, four items
- [ ] What's Included section — white background, four feature cards with SVG icons
- [ ] 15 Certifications section — light gray (#f4f6f8) background, three track cards, correct badge colors
- [ ] CTA section — dark ink background, heading + blue subline, one button
- [ ] Footer — four columns, correct links (no /certification/ URLs), no bullet points, no "Powered by Astra"
- [ ] No emojis visible anywhere on the page
- [ ] Fonts loading: Fraunces (headings), Inter Tight (body), JetBrains Mono (labels)
- [ ] Mobile responsive: sections stack to single column at 560px

---

*This brief references: design-system.html v2.1 · brand-guidelines.html v1.1 · site-audit.html A-02, A-07*
