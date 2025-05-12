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

    public static function options(): array
    {
        return [
            self::PENDING->name,
            self::PREPARING->name,
            self::BAKING->name,
            self::OUT_FOR_DELIVERY->name,
            self::DELIVERED->name,
            self::CANCELLED->name,
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

    public static function descriptions(): array
    {
        return [
            'pending' => 'Your order is pending.',
            'preparing' => 'Your order is being prepared.',
            'baking' => 'Your order is being baked.',
            'out_for_delivery' => 'Your order is out for delivery.',
            'delivered' => 'Your order has been delivered.',
            'cancelled' => 'Your order has been cancelled.',
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

    public static function getLabel(string $status): string
    {
        return strtolower(self::optionsKeyValue()[$status] ?? 'Unknown');
    }

    public static function getDescription(string $status): string
    {
        return self::descriptions()[$status] ?? 'Unknown status';
    }
}
