# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed
- `src/Install.php` injects `php bin/compile.php prod-app` as the `compile` script into created projects, instead of the deprecated `vendor/bin/bear.compile`

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

[1.15.0]: https://github.com/bearsunday/BEAR.Skeleton/compare/1.14.0...1.15.0
[1.14.0]: https://github.com/bearsunday/BEAR.Skeleton/compare/1.13.0...1.14.0
