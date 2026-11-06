# BEAR.Skeleton

## Installation

    composer install

## Usage

### Invoke Request

    composer page get /

### Available Commands

    composer serve             // start builtin server
    composer test              // run unit test
    composer tests             // test and quality checks
    composer coverage          // test coverage
    composer cs-fix            // fix the coding standard
    composer doc               // generate API document
    composer run-script --list // list all commands
    
### Observe the Cache

The builtin server (`composer serve`) runs the `dev-hal-app` context. A `dev-` context records each request's cache decisions (stored or not and why, hit, 304) to `var/log/{context}/query-repository`:

    vendor/bin/stree var/log/dev-hal-app/query-repository/latest.json

Prefix any context with `dev-` to record it, e.g. `dev-cli-hal-app` for a CLI request. See [Reading the cache log](https://github.com/bearsunday/BEAR.QueryRepository/blob/1.x/docs/reading-the-log.md).

## Links

 * BEAR.Sunday http://bearsunday.github.io/
