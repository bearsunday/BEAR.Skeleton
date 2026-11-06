# BEAR.Skeleton

## Installation

    composer install

## Usage

### Invoke Request

    composer page get /

### Available Commands

    composer serve             // start builtin server (hal-app)
    composer serve-dev         // ... recording the cache log (dev-hal-app)
    composer serve-dev-cache   // ... recording the cache log, with a cache store (dev-cache-hal-app)
    composer test              // run unit test
    composer tests             // test and quality checks
    composer coverage          // test coverage
    composer cs-fix            // fix the coding standard
    composer doc               // generate API document
    composer run-script --list // list all commands
    
### Observe the Cache

| Context | Cache store | Cache log |
|---|---|---|
| `hal-app` | none | not recorded |
| `dev-hal-app` | none | recorded |
| `dev-cache-hal-app` | the one production uses | recorded |

`dev-` is the development context (`src/Module/DevModule.php`). It installs the cache log, which records each request's cache decisions to `var/log/{context}/query-repository`; remove that `install()` to stop recording:

    vendor/bin/stree var/log/dev-hal-app/query-repository/latest.json

Without `cache-` the store is `NullAdapter`: it accepts a write (`saved: true`) and keeps nothing, so every read is a miss and every response is fresh. The log still shows what each resource declares, its tags, and whether its body can be stored.

With `cache-` entries are kept, so hits and 304s appear. They also outlive code edits: to see an edited resource, restart the server (APCu) or delete `var/tmp/dev-cache-hal-app/cache` (files, used when APCu is not loaded).

The words combine with any context, e.g. `dev-cache-cli-hal-app` for a CLI request. See [Reading the cache log](https://github.com/bearsunday/BEAR.QueryRepository/blob/1.x/docs/reading-the-log.md).

## Links

 * BEAR.Sunday http://bearsunday.github.io/
