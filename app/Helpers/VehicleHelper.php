<?php

namespace App\Helpers;

class VehicleHelper
{
    public static function makes(): array
    {
        return [
            'Acura', 'Audi', 'BMW', 'Buick', 'Cadillac', 'Chevrolet', 'Chrysler',
            'Dodge', 'Ford', 'Genesis', 'GMC', 'Honda', 'Hyundai', 'Infiniti',
            'Jaguar', 'Jeep', 'Kia', 'Land Rover', 'Lexus', 'Lincoln', 'Mazda',
            'Mercedes-Benz', 'Mini', 'Mitsubishi', 'Nissan', 'Ram', 'Subaru',
            'Tesla', 'Toyota', 'Volkswagen', 'Volvo',

            'Freightliner', 'Hino', 'International', 'Isuzu', 'Kenworth',
            'Mack', 'Peterbilt', 'Sterling', 'Western Star',

            'Coachmen', 'Fleetwood', 'Forest River', 'Jayco', 'Newmar',
            'Thor Motor Coach', 'Tiffin', 'Winnebago',

            'Bayliner', 'Boston Whaler', 'Kawasaki', 'Sea-Doo', 'Sea Ray', 'Yamaha',

            'Club Car', 'E-Z-GO', 'Evolution', 'Garia', 'GEM', 'ICON',
            'Tomberlin', 'Yamaha Golf-Car',
        ];
    }

    public static function colors(): array
    {
        return [
            'Black', 'Blue', 'Brown', 'Gold', 'Gray', 'Green', 'Orange',
            'Purple', 'Red', 'Silver', 'Tan', 'White', 'Yellow', 'Unknown',
        ];
    }
}