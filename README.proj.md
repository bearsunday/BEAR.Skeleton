# BEAR.Skeleton

## Installation

    composer install

## Usage

### Invoke Request

    composer page get /

### Dev Observation

With `bear/event-sourcing` installed (`composer install`, not `--no-dev`),
any resource request made in a `dev-` context is observed automatically: the
module arms a write at process shutdown, so the log lands on disk whether the
request came from `bin/observe.php`, a test, or anything else - no explicit
flush call needed.

`bin/observe.php` is the documented way to make such a request from the CLI
(the shipped entry points `bin/page.php` and `bin/app.php` stay on their
fixed, non-dev contexts and are never observed):

    composer observe get /

The log lands at `var/log/dev-cli-hal-app/latest.json` (one directory per
context, the newest ~100 requests kept as timestamped siblings). The command
prints the path; view it anytime with:

    vendor/bin/stree var/log/dev-cli-hal-app/latest.json

Requests made through the normal entry points (`composer page`, `composer
app`) stay on a plain context and never load the observation bindings or
write a log.

### Available Commands

    composer serve             // start builtin server
    composer observe           // request a resource in a dev context, record it
    composer test              // run unit test
    composer tests             // test and quality checks
    composer coverage          // test coverage
    composer cs-fix            // fix the coding standard
    composer doc               // generate API document
    composer run-script --list // list all commands
    
## Links

 * BEAR.Sunday http://bearsunday.github.io/
