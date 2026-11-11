# BEAR.Skeleton

## Installation

    composer install

## Usage

### Invoke Request

    composer page get /

### Available Commands

    composer serve             // start builtin server (dev-cache-hal-app)
    composer test              // run unit test
    composer tests             // test and quality checks
    composer coverage          // test coverage
    composer cs-fix            // fix the coding standard
    composer doc               // generate API document
    composer run-script --list // list all commands
    
### Observe the Cache

`composer page` and `composer app` run in the development context: every response is fresh, and a request that touches a `#[Cacheable]` resource records its cache log (one with no cache activity writes no file). `composer serve` adds the store production uses, so hits and 304s appear as they would in production.

| Command | Context | Cache store | Cache log |
|---|---|---|---|
| `composer page`, `composer app` | `dev-cli-hal-app`, `dev-cli-hal-api-app` | none | recorded |
| `composer serve` | `dev-cache-hal-app` | the one production uses | recorded |

`dev-` is the development context (`src/Module/DevModule.php`). It installs the cache log, which writes the cache decisions of each request (a CLI command or an HTTP request) to `var/log/{context}/query-repository` and keeps the latest 10; remove that `install()` to stop recording. The skeleton has no `#[Cacheable]` resource yet: put `#[Cacheable]` on `src/Resource/Page/Index.php`, run `composer page get /`, then read the log:

    vendor/bin/stree var/log/dev-cli-hal-app/query-repository/latest.json

Without `cache-` the store is `NullAdapter`: it accepts a write (`saved: true`) and keeps nothing, so every read is a miss. The log still shows what each resource declares, its tags, and whether its body can be stored.

With `cache-` entries outlive code edits: to see an edited resource, restart the server (APCu) or delete `var/tmp/{context}/cache` (files, used when APCu is not loaded and in the CLI).

`bin/page.php` and `bin/app.php` take another context from `BEAR_CONTEXT`, e.g. `BEAR_CONTEXT=dev-cache-cli-hal-app composer page get /`. `public/index.php` reads no environment: `dev-cache-hal-app` under the built-in server, `prod-hal-app` otherwise. See [Reading the cache log](https://github.com/bearsunday/BEAR.QueryRepository/blob/1.x/docs/reading-the-log.md).

## Links

 * BEAR.Sunday http://bearsunday.github.io/
