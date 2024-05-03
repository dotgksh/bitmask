<?php

/**
 * Example taken and adapted from Aaron Francis "Bitmasking in Laravel and MySQL" blog post.
 *
 * @link https://aaronfrancis.com/2021/bitmasking-in-laravel-and-mysql-26257c0b
 */

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\TinyBitmask;

enum FindMethod: int
{
    case ADDRESS = 0b00000001;
    case PARCEL = 0b00000010;
    case GEOCODE = 0b00000100;
    case DESCRIPTION = 0b00001000;
    case ALTERNATE = 0b00010000;
    case STREET = 0b00100000;
    case SINGLE_SCRAPER = 0b01000000;
    case SEARCH_SCRAPER = 0b10000000;

    public function methodName(): string
    {
        return match ($this) {
            FindMethod::PARCEL => 'attemptParcel',
            FindMethod::ADDRESS => 'attemptAddress',
            FindMethod::DESCRIPTION => 'attemptDescription',
            FindMethod::ALTERNATE => 'attemptAlternate',
            FindMethod::STREET => 'attemptStreet',
            FindMethod::SINGLE_SCRAPER => 'attemptSingleScraper',
            FindMethod::GEOCODE => 'attemptGeocode',
            FindMethod::SEARCH_SCRAPER => 'attemptSearchScraper',
        };
    }
}

class FindAttempts extends TinyBitmask
{
    public ?int $pid = null;

    public function recordAttempt(FindMethod $method): FindAttempts
    {
        return $this->set($method->value);
    }

    public function resetAttempt(FindMethod $method): FindAttempts
    {
        return $this->unset($method->value);
    }

    public function hasAttempted(FindMethod $method): bool
    {
        return $this->has($method->value);
    }
}

class Property // extends Model
{
    public ?int $pid = null;

    public FindAttempts $attempts;

    public function __construct(?FindAttempts $attempts = null)
    {
        $this->attempts = $attempts ?? new FindAttempts();
    }

    public function save(): void
    {
        // Save the property.
        dump([
            'property' => $this,
            'attempted' => array_map(fn (FindMethod $method) => [
                $method->name => $this->attempts->hasAttempted($method),
            ], FindMethod::cases()),
        ]);
    }
}

class FindIds // extends Command
{
    public function handle(): void
    {
        foreach ($this->queryProperties() as $property) {
            // Loop through the methods.
            foreach (FindMethod::cases() as $method) {
                // Skip ones we've already tried.
                if ($property->attempts->hasAttempted($method)) {
                    continue;
                }

                // Delegate to the appropriate method.
                $result = $this->{$method->methodName()}($property);

                // Methods can return `false` on failure, or if they
                // are currently disabled for any reason. We don't
                // record an attempt, as we'll try those again.
                if ($result !== false) {
                    $property->attempts->recordAttempt($method);
                }

                // Stop processing once we find the PID.
                if (! is_null($property->pid)) {
                    break;
                }
            }

            $property->save();
        }
    }

    /**
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $name, array $arguments): bool
    {
        $key = array_rand($odds = [false, false, false, true, false], 1);

        return $odds[$key];
    }

    /**
     * @return Property[]
     */
    private function queryProperties(): array
    {
        // Imagine this is querying the DB.
        return [
            new Property(FindAttempts::make()),
            new Property(FindAttempts::make(FindMethod::ADDRESS->value)),
            new Property(FindAttempts::make(FindMethod::PARCEL->value | FindMethod::STREET->value)),
        ];
    }
}

(new FindIds())->handle();
