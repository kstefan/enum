enum
====

PHP implementation of Enum

```php
<?php

use Enum\AbstractEnum;

class Day extends AbstractEnum
{
    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    protected static $default = self::SUNDAY;

    public function getLabel()
    {
        return date('l', strtotime(sprintf('Sunday + %d Days', $this->getValue())));
    }
}

$defaultDay = new Day();
echo $defaultDay->getValue();
// 0
echo $defaultDay->getLabel();
// Sunday

$monday = new Day(Day::MONDAY);
echo $monday->getValue();
// 1
echo $monday;
// 1
print_r(Day::getValues());
// Array ( [SUNDAY] => 0 [MONDAY] => 1 [TUESDAY] => 2 [WEDNESDAY] => 3 [THURSDAY] => 4 [FRIDAY] => 5 [SATURDAY] => 6 )
echo $monday->getLabel();
// Monday
print_r(Day::getLabels());
// Array ( [0] => Sunday [1] => Monday [2] => Tuesday [3] => Wednesday [4] => Thursday [5] => Friday [6] => Saturday )
```
