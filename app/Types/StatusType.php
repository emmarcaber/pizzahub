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
}
