# BEAR.Skeleton

## Installation

    composer install
    composer setup

## Usage

### Run server

    COMPOSER_PROCESS_TIMEOUT=0 composer serve

### Console

    php bootstrap/web.php get '/'

### QA

    composer test     // phpunit
    composer tests    // phpunit + cs + qa
    composer coverage // test coverate
    composer cs-fix   // lint fix
