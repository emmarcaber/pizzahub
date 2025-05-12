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
            'pending' => 'Pending',
            'preparing' => 'Preparing',
            'baking' => 'Baking',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
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
            'pending' => 'warning',
            'preparing' => 'info',
            'baking' => 'primary',
            'out_for_delivery' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
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
