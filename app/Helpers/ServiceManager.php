<?php

namespace App\Helpers;

use App\Models\Service;
use App\Models\ServiceField;

class ServiceManager
{
    /**
     * Get a service by name and ensure it exists with the provided fields.
     *
     * @param string $serviceName
     * @param array $fields Array of fields ['name' => 'Field Name', 'code' => 'Field Code']
     * @return \App\Models\Service
     */
    public static function getServiceWithFields(string $serviceName, array $fields)
    {
        $service = Service::firstOrCreate(
            ['name' => $serviceName],
            ['is_active' => true]
        );

        foreach ($fields as $field) {
            ServiceField::firstOrCreate(
                [
                    'field_code' => $field['code']
                ],
                [
                    'service_id' => $service->id,
                    'field_name' => $field['name'],
                    'is_active' => true,
                    'base_price' => $field['price'] ?? 0
                ]
            );
        }

        return $service;
    }
}
