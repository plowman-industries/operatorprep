# OperatorPrep.com
### Plowman Industries LLC | Private Repository

## Branch Structure
| Branch | Purpose | Deploys To |
|--------|---------|-----------|
| `main` | Production-ready code | Live site (manual trigger) |
| `staging` | Active development | staging2.operatorprep.com |

## Deploy Process
- Push to `staging` → auto-deploys to staging2.operatorprep.com
- Go to Actions → Deploy to Production → type DEPLOY → confirm

## Stack
WordPress 6.9.4 + Astra + Tutor LMS 3.9.7 + WooCommerce 10.6.1
