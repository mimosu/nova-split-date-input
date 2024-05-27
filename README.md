# The Nova Split Date Input

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mimosu/nova-split-date-input.svg)](https://packagist.org/packages/mimosu/nova-split-date-input)
[![Total Downloads](https://img.shields.io/packagist/dt/mimosu/nova-split-date-input.svg)](https://packagist.org/packages/mimosu/nova-split-date-input)
[![License](https://img.shields.io/packagist/l/mimosu/nova-split-date-input.svg)](https://github.com/mimosu/nova-split-date-input/blob/main/LICENSE)

## Installation

```bash
composer require mimosu/nova-split-date-input
```

## Usage

```php

use Mimosu\NovaSplitDateInput\SplitDate;
    //...
    public function fields(NovaRequest $request): array
    {
        return [
            SplitDate::make(__('Date of birth'), 'date_of_birth')
                ->delimiters(__('Year'), __('Month'), __('Day'))
                ->limitYears(1900, 2024)
                ->rules('required', 'date'),
        ];
    }
```

## Screenshots

![Screenshot 1](https://github.com/mimosu/nova-split-date-input/blob/main/img/Screenshot_en.png?raw=true)
![Screenshot 2](https://github.com/mimosu/nova-split-date-input/blob/main/img/Screenshot_jp.png?raw=true)

## License

The MIT License (MIT).
