<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NotStarted()
 * @method static static Ongoing()
 * @method static static Finished()
 */
final class TaskStatusEnum extends Enum
{
    const NotStarted = 0;
    const Ongoing = 1;
    const Finished = 2;

    public static function getDescription(mixed $value): string
    {
        return parent::getDescription($value);
    }
}
