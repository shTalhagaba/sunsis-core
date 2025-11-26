<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Configuration;

class ConfigurationService
{
    public function loadConfiguration()
    {
        $configurations = Configuration::all();

        foreach ($configurations as $config) 
        {
            Cache::put($config->entity, $config->value);
        }
    }

    public function get($entity)
    {
        return Cache::get($entity);
    }

    public function set($entity, $value)
    {
        $configuration = Configuration::updateOrCreate(
            ['entity' => $entity], // Find by 'entity'
            ['value' => $value]    // Update 'value' or set it if creating
        );

        Cache::put($entity, $value);

        return $configuration;
    }
}
