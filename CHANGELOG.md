# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- `bin/compile.php` builds the compiler directly: `new Compiler('BEAR\Skeleton', $context, dirname(__DIR__))`. `Compiler::fromInjector()` is gone in `bear/package` 1.24

### Removed
- `APP_WRITE_DIR`, and the write directory the entries took: an application declares where it writes in its own `ProdModule`, with `BEAR\Package\Module\ReadOnlyAppModule`

## [1.16.0] - 2026-08-17

An application directory can be read-only - a container image, a Phar archive - so the entries take a
write directory. Without `APP_WRITE_DIR` nothing changes: the application writes under its own `var/`.

### Added
- `APP_WRITE_DIR` on the entries: `public/index.php`, `bin/app.php`, `bin/page.php` and `bin/compile.php` read it and pass it on (#146)

### Changed
- `Injector::getInstance($context, $writeDir)` delegates to `BEAR\Package\Injector`; the `$tmpDir` and `$logDir` arguments are gone (#146)
- `Bootstrap::__invoke()` takes the write directory as a fourth argument (#146)
- Requires PHP `^8.2`, the floor `bear/package` already had, and `bear/package` `^1.22` (#146)
- `bin/compile.php` loads `.compile.php` when it is present (#141)

### Fixed
- `src/Install.php` injects `php bin/compile.php prod-app` as the `compile` script into created projects, instead of the deprecated `vendor/bin/bear.compile`
- `Bootstrap` calls `router->match()` inside its `try`, so a malformed request answers through the error handler instead of an uncaught fatal (bearsunday/BEAR.Package#493)

## [1.15.0] - 2026-07-10

### Added
- `bin/compile.php`: `Compiler::fromInjector(Injector::getInstance($context), $context)()`

### Changed
- `Injector::getInstance` builds `Meta` (optional `$tmpDir` / `$logDir` pass-through)
- Require `bear/app-meta` `^1.11` and `bear/package` `^1.21`
- `composer compile` uses `php bin/compile.php` instead of `bear.compile`

## [1.14.0] - 2026-01-11

### Added
- security.yml, apidoc.yml, alps.yml workflows
- Continuous integration triggers for push/PR

### Changed
- Workflows on PHP 8.5
- Keep `.github` on project installation
- psalm.xml taint analysis configuration

[1.16.0]: https://github.com/bearsunday/BEAR.Skeleton/compare/1.15.0...1.16.0
[1.15.0]: https://github.com/bearsunday/BEAR.Skeleton/compare/1.14.0...1.15.0
[1.14.0]: https://github.com/bearsunday/BEAR.Skeleton/compare/1.13.0...1.14.0
