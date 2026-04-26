# OperatorPrep — Project Brief for Claude Code

## What this repo is

The WordPress site and design specs for **[OperatorPrep.com](http://OperatorPrep.com)** — a water/wastewater operator exam prep platform covering 15 certifications (T1–T5 Water Treatment, D1–D5 Distribution, WW1–WW5 Wastewater). The site runs on WordPress with the Astra theme and WooCommerce for subscriptions ($19.99–$29.99/mo per cert).

Owned and operated by Phoenix / Plowman Industries LLC in Santa Barbara, CA. Built by a working operator (USMC veteran, active Water Treatment Grade 4) for other working operators.

## What the folders contain

```
operatorprep-site/
├── specs/                                   ← AUTHORITATIVE. Read these first.
│   ├── design-system.html                   ← v2.1 · tokens, components, page templates
│   ├── brand-guidelines.html                ← v1.1 · voice, personas, logo, color
│   ├── site-audit.html                      ← 12 prioritized issues with fix steps
│   ├── page-consistency-cheatsheet.html     ← one-pager for quick reference
│   └── logo/
│       ├── brand-mark.svg                   ← primary logo (scalable)
│       ├── brand-mark-1x.png                ← PNG fallbacks
│       ├── brand-mark-2x.png
│       └── brand-mark-3x.png
├── wp-theme/                                ← the actual WordPress child theme
├── PLAN.md                                  ← repair plan, batched by priority
├── CHANGELOG.md                             ← what changed, when, why
└── README.md                                ← this file
```

## How to work with this project

**Before you write any code, read the specs.** Specifically:

1. `specs/design-system.html` — the contract for tokens, components, and the four page templates (practice tests, flashcards, math drills, study guides)
2. `specs/brand-guidelines.html` — the voice rules, the three buyer personas, the logo rules, the no-emoji mandate
3. `specs/site-audit.html` — the list of what's currently broken and needs to be fixed

Then read `PLAN.md` for the current batch in progress. If `PLAN.md` doesn't exist yet, offer to write it based on the audit.

## Hard rules — do not violate

These come from `design-system.html` Section 08 (Usage):

1. **Tokens are the contract.** Every color, space, font comes from a `--token`. No hex codes or pixel values inline.
2. **Icons come from the sprite only.** No emojis. No Font Awesome. No Material Icons. The custom SVG sprite at the top of the design system defines every icon.
3. **Page templates are structural contracts.** The practice-test / flashcard / math-drill / study-guide landing pages MUST follow the anatomy in Section 04. Mastery legend, Full Test card, category grid — all required blocks in the order specified.
4. **Don't re-style components per-page.** If a new pattern is needed, add it to the design system first, bump the version, then apply.
5. **Don't paraphrase locked copy.** "95%+ Mastered", "85-94% Review", "Below 85% Needs Work", "Not Attempted" — these strings are locked. Don't change them to "Struggling" or "On Track" or anything cuter.

## How to propose changes to the specs themselves

If a real constraint requires a design system change:

1. Don't just edit the file. Explain why the current system can't express what's needed.
2. Propose a specific version bump (v2.1 → v2.2) with a changelog entry describing the change.
3. Wait for the human to approve before editing the spec.
4. Keep the previous version as `design-system-v2.1-backup.html`.

## Workflow for executing the site audit

The site audit (`specs/site-audit.html`) lists 12 issues across P0–P3 priority. Standard operating procedure:

1. Read the audit start-to-finish.
2. Write or update `PLAN.md` with each issue as a **batch**. One batch = one deployable unit of work. Ordered by priority.
3. For each batch, identify: files that will change, approach, verification step.
4. Execute one batch at a time. Show the diff before committing.
5. Do not move to the next batch until the human approves and commits the current one.
6. Commit messages: `fix(audit): A-01 remove duplicate Astra footer per design system v2.1`

**Why one batch at a time?** The entire reason this design system exists is that previous AI sessions drifted — making one change would quietly break three others. Small committed batches with review gates is how we stop the drift.

## What the audit priorities mean

- **P0 — Blocking.** Broken links, duplicate footers, emojis still in production. Fix first.
- **P1 — Major.** Violates the design system in a way users see (e.g., PNG logo instead of SVG).
- **P2 — Minor.** Inconsistency most users won't notice but that matters for polish.
- **P3 — Cleanup.** Optimization, SEO, empty tags.

## Personas — know who you're writing for

From `brand-guidelines.html` Section 07:

- **Alpha — The Working Operator** (60% of revenue). Has the job, needs the grade for the raise. Phone on break, laptop at home. Hates marketing hype and gamification.
- **Beta — The Career-Changer** (25%). Leaving HVAC/military/oilfield for water. Needs a clear map of what to learn first.
- **Gamma — The Sponsored Apprentice** (15%). Utility-funded, contractual deadline to pass.

When editing a page or writing new copy, name the persona at the top of the draft. One persona per asset. Don't blend them.

## The "operator voice" rule

From `brand-guidelines.html` Section 06:

- **Yes words:** operator, shift, plant, the floor, field, the why, get the grade, pass it
- **No words:** unlock, supercharge, seamless, holistic, leverage, dive in, journey, game-changer
- **The `//` motif** is a nod to code comments. Use it sparingly for section eyebrows. One per section max.
- **When in doubt, ask: would a shift supervisor say this?** If no, rewrite it.

## Versioning convention

- Design system is on v2.1. Brand guidelines on v1.1. Any spec change bumps the version at the top of the file AND adds a changelog entry.
- Keep previous versions as `*-backup.html` files. Never overwrite without a backup.
- When updating `PLAN.md` or `CHANGELOG.md`, append — don't rewrite history.

## Preferred AI interaction pattern for this repo

When the human opens this repo and asks for a change, the pattern is:

1. **Read the specs first.** Do not start editing theme files before you've read the design system and brand guidelines.
2. **Summarize back what you understand.** The human will correct misreadings before any code is written.
3. **Propose a plan.** What files will change, what approach, what could break.
4. **Execute one piece.** Show a diff. Get approval.
5. **Commit with a clear message.** Reference the audit item (`A-02`) or the spec section (`design-system.html §4.1`).

Do not do the whole audit in one shot. Do not propose sweeping refactors without reading the existing code. Do not add new components without checking whether an existing one fits. Do not use emojis anywhere, ever.

## When to ask the human vs. proceed

**Proceed without asking** for:
- Typo fixes
- Obvious bugs where the behavior clearly doesn't match the spec
- Standard edits that follow existing patterns in the repo

**Ask first** for:
- Any change to the design system or brand guidelines themselves
- Any new component or page template
- Any change that affects more than one audit item at once
- Any interpretation of an ambiguous audit item
- Any deviation from the locked page template anatomy
- Anything that would change copy the human has approved before

## Context about past drift (so you don't repeat it)

The design system went through 2.0 major versions and 7 minor versions because previous AI sessions kept:

- Removing the mastery legend from practice-test pages
- Using emojis instead of the SVG icon sprite
- Restructuring page templates per request instead of following the locked anatomy
- Inverting visual hierarchy (dark body + bright secondary cards, muted primary CTA)

The current v2.1 design system fixes all of these. Your job is to apply it, not to redesign it.

---

_Last updated when specs were dropped in. Update this file if folder structure or workflow changes._
