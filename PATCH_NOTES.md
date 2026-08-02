# OrbDNS-PDNSAdmin-PHP — MegaLinter Patch Notes
**Date:** 2026-08-03  
**Version:** 1.3.1-patch1  
**Based on:** MegaLinter 9.6.0 report

---

## Files Changed

### 1. `app/Core/Auth.php`
- **PHPStan fix** (`nullCoalesce.offset`): Line 37 used `($_SESSION['ip'] ?? '')` but `$_SESSION['ip']` is
  guaranteed non-null by the `isset()` check on line 33. Changed to direct access `$_SESSION['ip']` with
  `// NOSONAR` inline comment to suppress SonarQube false positive. Added `phpstan.neon.dist` suppression.

### 2. `app/Core/Session.php`
- **Psalm fix** (`UndefinedConstant PHP_SESSION_ACTIVE`): Added `/** @psalm-suppress UndefinedConstant */`
  docblock above the `session_status()` check. Also fixed in `psalm.xml` via `<UndefinedConstant>` suppression
  for all PHP session constants.

### 3. `app/Controllers/AuthController.php`
- **JSCPD fix** (duplicate code): Lines 70-77 and 84-91 contained identical flash+redirect patterns.
  Extracted into private `failLogin(string $message): never` helper method. This eliminates the 0.40%
  duplicate token threshold violation without changing any logic or behavior.

### 4. `public/assets/js/zones.js`
- **JS Standard fix** (`no-unused-vars`): The outer IIFE `(function ($) { ... })(jQuery)` caused `$` to
  appear as a redeclared unused variable despite the `/* global $ */` declaration. Refactored to top-level
  functions using `$` directly (already declared global). `setTimeout`/`setInterval` calls annotated with
  `// NOSONAR` for DevSkim DS172411 (no untrusted data involved).

### 5. `.mega-linter.yml`
- **YAML v8r fix**: Removed invalid `EXCLUDED_FILES` property (not in MegaLinter schema). Replaced with
  `FILTER_REGEX_EXCLUDE` regex pattern (correct schema property).
- Added `SQL_TSQLLINT` to `DISABLE_LINTERS` — SQL files use MySQL/MariaDB syntax, tsqllint is T-SQL only;
  all 20 errors were false positives.
- Added `ACTION_ZIZMOR_UNSECURED_ENV_VARIABLES: [GITHUB_TOKEN]` to fix zizmor 401 error.

### 6. `.github/dependabot.yml`
- **YAML v8r fix**: `package-ecosystem` was empty string `""` which is invalid. Changed to `"composer"`
  (for PHP/Composer deps) and added a second entry for `"github-actions"`.

### 7. `.devskim-ignore`
- Added explicit paths for `vendor/composer/installed.json` and `vendor/composer/installed.php` to suppress
  DS173237 false positives (Composer SHA hashes mistaken for tokens/keys).

### 8. `psalm.xml`
- Added `<UndefinedConstant>` issue handler to suppress `PHP_SESSION_ACTIVE`, `PHP_SESSION_NONE`,
  `PHP_SESSION_DISABLED` — these are valid PHP core constants but Psalm's stubs may not include them when
  `allConstantsGlobal` is not fully effective on all Psalm versions.

### 9. `phpstan.neon.dist`
- Added `ignoreErrors` entry for `Auth.php` line's `nullCoalesce.offset` to suppress PHPStan false positive
  at the proper tool configuration level (belt-and-suspenders with the inline NOSONAR comment).

### 10. `.cspell.json`
- Expanded `words` list with additional Indonesian words and technical terms (PDNS, TOTP, phpstan, etc.)
  to eliminate the 76 cspell spelling false positives.
- Added `vendor/**` and `*.cache` to `ignorePaths` to avoid scanning third-party files.

---

## Issues NOT Fixed (Explained)

| Issue | Reason |
|---|---|
| `betterleaks` — 3 errors | False positives: scanning `.php-cs-fixer.cache` which contains file hashes, not real secrets. Already in `EXCLUDED_FILES` (now `FILTER_REGEX_EXCLUDE`). |
| `devskim` DS126858 — `vendor/robthree/twofactorauth/lib/Algorithm.php` | In vendor; TOTP RFC 6238 requires SHA1 — changing to SHA256/SHA512 breaks TOTP compatibility. Do not modify vendor files. |
| `grype` — `super-linter/super-linter v7.1.0` GHSA-r79c-pqj3-577x | GitHub Actions dependency in workflow file; update `uses: super-linter/super-linter@v7` to `@v8.3.1` in the workflow. |
| `lychee` — README.md timeout | `bootstrapget.com` URL timeout is a network availability issue, not a code bug. |
| `lychee` — logo.png 404 | Image asset not committed to repo. Upload `public/assets/img/logo.png` or update README URL. |

---

## Apply Patches

```bash
# From repo root
unzip OrbDNS-PDNSAdmin-PHP-megalinter-patches.zip -d .
```

> **Note:** Review each file before committing. No functional PHP logic was changed.
