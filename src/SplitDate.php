<?php

namespace Mimosu\NovaSplitDateInput;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Exception;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class SplitDate extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-split-date-input';

    /**
     * The delimiters that can be assigned to the field.
     *
     * @var array
     */
    public array $delimiters = [
        'year' => '-',
        'month' => '-',
        'day' => '',
    ];

    /**
     * The limit years that can be assigned to the field.
     *
     * @var array
     */
    public array $limitYears = [
        'min' => null,
        'max' => null
    ];

    /**
     * The year period
     */
    public const YEAR_PERIOD = 200;

    /**
     * Create a new field.
     *
     * @param string $name
     * @param string|\Closure|callable|object|null $attribute
     * @param (callable(mixed, mixed, ?string):(mixed))|null $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        $this->limitYears(now()->subYears(self::YEAR_PERIOD)->year, now()->year);

        parent::__construct($name, $attribute, $resolveCallback ?? function ($value) {
            if (! is_null($value)) {
                if ($value instanceof DateTimeInterface) {
                    return $value instanceof CarbonInterface
                        ? $value->toDateString()
                        : $value->format('Y-m-d');
                }

                throw new Exception("Date field must cast to 'date' in Eloquent model.");
            }
        });
    }

    /**
     * The minimum value that can be assigned to the field.
     *
     * @param string $year
     * @param string $month
     * @param string $day
     * @return $this
     */
    public function delimiters(string $year = '-', string $month = '-', string $day = ''): static
    {
        $this->delimiters = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ];

        return $this;
    }

    /**
     * @param int $minYear
     * @param int $maxYear
     * @return $this
     */
    public function limitYears(int $minYear, int $maxYear): static
    {
        if ($maxYear < $minYear) {
            // throw new Exception(__("The min year must be lower than the max year."));
            return $this;
        }

        $this->limitYears['min'] = $minYear;
        $this->limitYears['max'] = $maxYear;

        return $this;
    }

    /**
     * Resolve the default value for the field.
     *
     * @param NovaRequest $request
     * @return string|null
     */
    public function resolveDefaultValue(NovaRequest $request): string|null
    {
        /** @var \DateTimeInterface|string|null $value */
        $value = parent::resolveDefaultValue($request);

        if ($value instanceof DateTimeInterface) {
            return $value instanceof CarbonInterface
                ? $value->toDateString()
                : $value->format('Y-m-d');
        }

        return $value;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), array_filter([
            'delimiters' => $this->delimiters,
            'limitYears' => $this->limitYears,
        ]));
    }
}
