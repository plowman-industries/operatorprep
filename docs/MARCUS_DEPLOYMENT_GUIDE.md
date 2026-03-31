# MARCUS COLE — CTO DEPLOYMENT & GIT GUIDE
## OperatorPrep.com | Plowman Industries LLC
### Version 1.0 | Effective: April 2026

---

## THE STACK OVERVIEW

| Layer | Tech | Notes |
|-------|------|-------|
| Hosting | SiteGround GrowBig | gcam1130.siteground.biz |
| CMS | WordPress 6.9.4 | |
| Theme | Astra 4.12.5 + child theme | Child theme is in git |
| LMS | Tutor LMS 3.9.7 | Not in git — managed via WP Admin |
| E-commerce | WooCommerce 10.6.1 | Not in git — managed via WP Admin |
| Payments | Stripe via WC Subscriptions | Critical — see Type 4 change rules |
| Version Control | GitHub (private) | github.com/plowman-industries/operatorprep |
| CI/CD | GitHub Actions | Staging auto-deploy / Production manual-only |

---

## ONE-TIME SETUP (Do This First)

### 1. Clone the Repo

```bash
git clone git@github.com:plowman-industries/operatorprep.git
cd operatorprep
```

### 2. Add GitHub Secrets
Go to GitHub → Settings → Secrets and variables → Actions → New repository secret.
Add all of these:

| Secret Name | Value |
|-------------|-------|
| `PROD_SSH_HOST` | `gcam1130.siteground.biz` |
| `PROD_SSH_USER` | `u2283-kdljzuwnwnbp` |
| `PROD_SSH_PORT` | `18765` |
| `PROD_SSH_KEY` | Contents of your SSH private key |
| `STAGING_SSH_HOST` | SiteGround staging host (from Site Tools → Staging) |
| `STAGING_SSH_USER` | Staging SSH user |
| `STAGING_SSH_PORT` | `18765` |
| `STAGING_SSH_KEY` | Contents of your SSH private key |

**Never hardcode credentials in any file. Never.**

### 3. Set Up SiteGround Staging
SiteGround → Site Tools → WordPress → Staging → Create Staging.
SiteGround clones the full live site to a staging URL automatically.
Note the staging URL and add it to Phoenix's memory.

### 4. Copy Current Theme Files Into Repo

```bash
# SSH into production and pull the child theme
scp -P 18765 -r u2283-kdljzuwnwnbp@gcam1130.siteground.biz:~/www/operatorprep.com/public_html/wp-content/themes/operatorprep-child ./wp-content/themes/

# Initial commit
git add .
git commit -m "chore: initial commit — existing child theme and custom plugin"
git push origin main
```

---

## DAILY WORKFLOW — How Every Feature Gets Built

### Starting a Sprint

```bash
# Always start from the latest staging
git checkout staging
git pull origin staging

# Create your feature branch
git checkout -b feature/sprint-2-pricing-page
```

### Doing the Work

Edit files locally. For WordPress-specific work (WP Admin changes, plugin settings), do those on the SiteGround staging environment directly.

For theme/code changes: edit locally, then push to staging branch to trigger auto-deploy.

### Committing Your Work

```bash
# Stage your changes
git add wp-content/themes/operatorprep-child/
git add wp-content/plugins/operatorprep-custom/

# Commit with a clear message
# Format: type(scope): description
# Types: feat / fix / chore / style / refactor
git commit -m "feat(pricing): add all 15 WC product checkout links to pricing page"

# Push to your feature branch
git push origin feature/sprint-2-pricing-page
```

### Merge to Staging for QA

```bash
git checkout staging
git merge feature/sprint-2-pricing-page
git push origin staging
# GitHub Actions auto-deploys to SiteGround staging
```

Vector QA runs. Phoenix reviews. Wait for green light.

### After Green Light — Merge to Main

```bash
git checkout main
git pull origin main
git merge staging
git push origin main
```

Then trigger production deploy (see below).

---

## DEPLOYING TO PRODUCTION

**This is a manual step. Always.**

1. Go to GitHub → Actions → "Deploy to Production" workflow
2. Click "Run workflow"
3. Select branch: `main`
4. In the confirmation field, type exactly: `DEPLOY`
5. Fill in the sprint description (e.g., "Sprint 2 — Pricing page with WC checkout links")
6. Click Run

The workflow SSH's into production, deploys the theme and custom plugin, flushes cache, and logs everything.

**After every production deploy — run the smoke test:**

```
✓ https://operatorprep.com/ loads
✓ Nav links work (no 404s)
✓ Pricing page — correct prices, links go to WooCommerce checkout
✓ A product page loads (e.g., /product/water-treatment-t1/)
✓ Homepage looks clean on mobile (check on your phone)
```

If anything is wrong: SiteGround → Site Tools → Backups → restore the backup from before the deploy. Then fix it on staging.

---

## BRANCH RULES

| Branch | Who touches it | How |
|--------|---------------|-----|
| `main` | Marcus only | Via PR merge after Phoenix + Jesse approval |
| `staging` | Marcus | Direct merge from feature branches |
| `feature/*` | Marcus | Created per sprint, deleted after merge |

**Never commit directly to `main`.** Always via PR from staging.

---

## WHAT GOES IN GIT vs. WHAT DOESN'T

### In Git ✓
- `wp-content/themes/operatorprep-child/` — All child theme files (CSS, PHP templates, functions.php)
- `wp-content/plugins/operatorprep-custom/` — Any custom functionality we wrote
- `.github/workflows/` — Deploy pipelines
- `scripts/` — Maintenance and setup scripts
- `docs/` — This guide and Phoenix's guide

### NOT in Git ✗
- WordPress core (`/wp-admin/`, `/wp-includes/`, root PHP files)
- Third-party plugins (Astra, Tutor LMS, WooCommerce, WC Subscriptions, SG plugins)
- `/wp-content/uploads/` — Media library
- `wp-config.php` — Has database credentials
- Any `.env` file

---

## WORDPRESS ADMIN — SAFE OPERATIONS

These are safe to do directly in WP Admin on staging without going through git:

- Adding/editing pages
- WooCommerce product settings
- Tutor LMS course content
- Menu structure
- Widgets / sidebars
- Plugin settings (non-payment)

These require staging first, git commit, and Phoenix approval before production:

- Any Stripe / WooCommerce Payments settings
- Plugin installs or removals
- Theme file edits
- PHP customizations

---

## WOOCOMMERCE SUBSCRIPTION PRODUCTS — REFERENCE

| Product | WP ID | Price |
|---------|-------|-------|
| Water Treatment T1 | 574 | $19.99/mo |
| Water Treatment T2 | 575 | $19.99/mo |
| Water Treatment T3 | 576 | $19.99/mo |
| Water Treatment T4 | 577 | $19.99/mo |
| Water Treatment T5 | 578 | $29.99/mo |
| Water Distribution D1 | 579 | $19.99/mo |
| Water Distribution D2 | 580 | $19.99/mo |
| Water Distribution D3 | 581 | $19.99/mo |
| Water Distribution D4 | 582 | $19.99/mo |
| Water Distribution D5 | 583 | $29.99/mo |
| Wastewater WW1 | 584 | $19.99/mo |
| Wastewater WW2 | 585 | $19.99/mo |
| Wastewater WW3 | 586 | $19.99/mo |
| Wastewater WW4 | 587 | $19.99/mo |
| Wastewater WW5 | 588 | $29.99/mo |

Checkout URL format: `https://operatorprep.com/?add-to-cart=[PRODUCT_ID]`

Direct product page format: `https://operatorprep.com/product/[product-slug]/`

---

## SSH ACCESS — PRODUCTION

```bash
ssh -p 18765 u2283-kdljzuwnwnbp@gcam1130.siteground.biz
```

WordPress root: `/home/u2283-kdljzuwnwnbp/www/operatorprep.com/public_html/`

Useful WP-CLI commands once connected:
```bash
# Flush cache
wp cache flush

# Check active plugins
wp plugin list --status=active

# Flush rewrite rules
wp rewrite flush

# Check maintenance mode
ls -la .maintenance

# Turn off maintenance mode
rm .maintenance
```

---

## EMERGENCY ROLLBACK

```bash
# Option 1 — SiteGround (fastest)
# Site Tools → Backups → Select backup from before deploy → Restore

# Option 2 — Git revert (if the issue is in tracked code)
git checkout main
git revert HEAD
git push origin main
# Then trigger production deploy again
```

Always tell Phoenix what happened and what you reverted to. Same day.

---

## GIT COMMIT MESSAGE FORMAT

```
type(scope): short description

Types:
  feat     — New feature or page
  fix      — Bug fix
  chore    — Maintenance, dependency updates
  style    — CSS/visual only, no logic changes
  refactor — Code restructure, no behavior change

Examples:
  feat(pricing): add 15 WC product links with correct checkout URLs
  fix(mobile): correct nav overflow at 375px viewport
  chore(deploy): update staging SSH workflow credentials
  style(homepage): adjust hero padding for mobile
```

---

*You own the technical execution. Phoenix owns the process. Jesse owns the outcome. Build clean, deploy clean, log everything.*
