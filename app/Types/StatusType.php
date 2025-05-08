<?php

namespace App\Types;

enum StatusType
{
    case PENDING;
    case PREPARING;
    case BAKING;
    case OUT_FOR_DELIVERY;
    case DELIVERED;
    case CANCELLED;

    public static function optionsKeyValue(): array
    {
        return [
            self::PENDING->name => 'Pending',
            self::PREPARING->name => 'Preparing',
            self::BAKING->name => 'Baking',
            self::OUT_FOR_DELIVERY->name => 'Out for Delivery',
            self::DELIVERED->name => 'Delivered',
            self::CANCELLED->name => 'Cancelled',
        ];
    }

    public static function optionsLower(): array
    {
        return [
            'pending',
            'preparing',
            'baking',
            'out_for_delivery',
            'delivered',
            'cancelled',
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING->name => 'warning',
            self::PREPARING->name => 'info',
            self::BAKING->name => 'primary',
            self::OUT_FOR_DELIVERY->name => 'success',
            self::DELIVERED->name => 'success',
            self::CANCELLED->name => 'danger',
        ];
    }

    public static function getColor(string $status): string
    {
        return self::colors()[$status] ?? 'secondary';
    }
}
