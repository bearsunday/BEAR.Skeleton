# BEAR.Skeleton

## Installation

    composer install

## Usage

### Invoke Request

    composer page get /

### Available Commands

    composer serve             // start builtin server (dev-cache-hal-app)
    composer cache get /       // request a page with the cache store (dev-cache-cli-hal-app)
    composer test              // run unit test
    composer tests             // test and quality checks
    composer coverage          // test coverage
    composer cs-fix            // fix the coding standard
    composer doc               // generate API document
    composer run-script --list // list all commands
    
### Observe Requests and the Cache

`composer page` and `composer app` run in the development context: every response is fresh, and each request records the resource requests it made together with their cache decisions. `composer cache` and `composer serve` add a local cache store for development (APCu, or files under `var/tmp`), so hits and 304s show up. Production uses a shared store such as Redis, bound in its own module.

| Command | Context | Cache store | Log |
|---|---|---|---|
| `composer page`, `composer app` | `dev-cli-hal-app`, `dev-cli-hal-api-app` | none | recorded |
| `composer cache` | `dev-cache-cli-hal-app` | local (APCu or files) | recorded |
| `composer serve` | `dev-cache-hal-app` | local (APCu or files) | recorded |

`dev-` is the development context (`src/Module/DevModule.php`). It installs bear/event-sourcing's `DevObservationModule`, which writes each request (a CLI command or an HTTP request) as one tree to `var/log/{context}/events`, response bodies under `bodies/` there, and keeps the latest 10; remove that `install()` to stop recording. Run a request, then read its tree:

    composer page get /
    vendor/bin/stree var/log/dev-cli-hal-app/events/latest.json

A `#[Cacheable]` resource adds its cache decisions (`cache_miss`, `save_value`, `cache_hit`, ...) under its request. An AI agent reads the tree with the bear-observe skill in `vendor/bear/event-sourcing/skills/bear-observe`.

Without `cache-` the store is `NullAdapter`: it accepts a write (`saved: true`) and keeps nothing, so every read is a miss. The log still shows what each resource declares, its tags, and whether its body can be stored.

With `cache-` entries outlive code edits: to see an edited resource, restart the server (APCu) or delete `var/tmp/{context}/cache` (files, used when APCu is not loaded and in the CLI).

`bin/page.php` and `bin/app.php` take another context from `BEAR_CONTEXT`, e.g. `BEAR_CONTEXT=cli-hal-app composer page get /` runs without the development context and records nothing. `public/index.php` reads no environment: `dev-cache-hal-app` under the built-in server, `prod-hal-app` otherwise. See [Reading the cache log](https://github.com/bearsunday/BEAR.QueryRepository/blob/1.x/docs/reading-the-log.md).

## Links

 * BEAR.Sunday http://bearsunday.github.io/
