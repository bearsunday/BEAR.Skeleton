# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.15.0] - 2026-07-10

### Added
- `bin/compile.php` using `Compiler::fromInjector()` + `run()` (optional `BEAR_TMP_DIR` / `BEAR_LOG_DIR` at process edge)

### Changed
- `Injector` builds `Meta` explicitly and accepts optional `$tmpDir` / `$logDir` (defaults unchanged)
- Require `bear/app-meta` `^1.11` and `bear/package` `^1.21`
- `composer compile` script uses `php bin/compile.php` instead of `bear.compile`

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
