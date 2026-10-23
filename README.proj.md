# BEAR.Skeleton

## Installation

    composer install

## Usage

### Invoke Request

    composer page get /

### Dev Observation

With `bear/event-sourcing` installed (`composer install`, not `--no-dev`),
`composer page` and `composer app` run in a `dev-` context and are observed
automatically: the request's resource tree is written to disk at process
shutdown, no explicit flush call needed.

    composer page get /

The log lands at `var/log/dev-cli-hal-app/observe/latest.json` (one
directory per context, the newest ~100 requests kept as timestamped
siblings); view it anytime with:

    vendor/bin/stree var/log/dev-cli-hal-app/observe/latest.json

`bin/page.php` and `bin/app.php` are development tools and need
`bear/event-sourcing` above - with `composer install --no-dev` use
`public/index.php` (`composer serve`) instead, which stays on its
plain/`prod-` context and never loads the observation bindings or writes a
log.

### Available Commands

    composer serve             // start builtin server
    composer test              // run unit test
    composer tests             // test and quality checks
    composer coverage          // test coverage
    composer cs-fix            // fix the coding standard
    composer doc               // generate API document
    composer run-script --list // list all commands
    
## Links

 * BEAR.Sunday http://bearsunday.github.io/
