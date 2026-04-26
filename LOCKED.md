# LOCKED — Read This First. Before Anything Else.

This file overrides all other instructions. If any other file, brief, or prompt
contradicts what's written here, THIS FILE WINS.

---

## The only task permitted right now

**Color token swap. Three hex values. Nothing else.**

---

## The only files you may edit

```
wp-content/themes/operatorprep-child/style.css
```

That is the only file. If you find yourself editing any other file, stop immediately.

---

## The only lines you may change

Find these exact strings and replace them:

| Find | Replace with |
|------|-------------|
| `#c85a1f` | `#d4a017` |
| `#fbeee5` | `#fefce8` |
| `rgba(200, 90, 31` | `rgba(212, 160, 23` |

If the string is not in that table, do not touch it.

---

## What you MUST NOT change under any circumstances

- Any HTML file
- Any PHP file
- Any JavaScript file
- Any page content in WordPress
- Any copy, headings, button text, or labels
- Any layout, grid, flexbox, or positioning
- Any typography — font family, size, weight, line-height
- Any component structure
- Any background colors EXCEPT the three hex values listed above
- Any nav, header, or footer HTML
- The words "Know Your Plant" — do not colorize them differently
- The hero layout — do not move, hide, or resize any columns
- Button copy — do not change any button text
- Any new elements — do not add anything that isn't already there

---

## Why these restrictions exist

Previous sessions changed copy, broke layouts, recolored headings, and invented
new page elements when asked to do a simple color swap. Every item in the
MUST NOT list above is something a previous AI session broke.

---

## Before making any changes

1. Open `style.css`
2. Run a search for `#c85a1f`
3. Tell me how many times it appears and on which line numbers
4. Do the same for `#fbeee5`
5. Do the same for `rgba(200, 90, 31`
6. Show me that list
7. Wait for me to say "proceed"
8. Only then make the replacements — nothing else

---

## After making changes

1. Show me a diff of exactly what changed
2. Flush the cache
3. Stop — do not do anything else

---

## If you are unsure whether something is permitted

The answer is no. Do not do it. Ask first.
