<?php

namespace App\Models\Lookups;

use App\Models\LearningResources\LearningResource;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;

class LearningResourceTypeLookup extends Model
{
    const TYPE_FILE_UPLOAD = 1;
    const TYPE_URL = 2;
    const TYPE_TEXT = 3;
    
    protected $guarded = [];

    public function resources()
    {
        return $this->hasMany(LearningResource::class, 'resource_type');
    }

    static function getDescription($id)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        foreach($constants AS $key => $value)
        {
            if($value == $id)
            {
                return str_replace('TYPE_', '', $key);
            }
        }

        return null;
    }

    static function getSelectData()
    {
        return [
            self::TYPE_FILE_UPLOAD => 'File Upload',
            self::TYPE_URL => 'External URL',
            self::TYPE_TEXT => 'Text Document',
        ];
    }
}