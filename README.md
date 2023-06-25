

## Local setup

1. Go to the project root directory.
2. composer install --ignore-platform-reqs
3. Copy .env.example file to .env
4. ./vendor/bin/sail up

## Check if flights are claimable.

1. From the project root directory start the command: ./vendor/bin/sail artisan app:check-flights-claimability
