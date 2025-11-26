<?php

class AppHelper
{
    public static function duplexTrainingLevelsDdl()
    {
        return [
            ['L1', 'Level 1'], 
            ['L2', 'Level 2'], 
            ['L3', 'Level 3'], 
            ['L4', 'Level 4'], 
            ['ML3', 'MAN Level 3'], 
            ['FG', 'F-Gas'],
            ['ADASL1', 'ADAS Level 1'],
            ['ADASL2', 'ADAS Level 2'],
            ['ADASL3', 'ADAS Level 3'],
            ['LVDT', 'LVDT'],
        ];
    }

    public static function duplexTrainingLevelsList()
    {
        return [
            'L1' => 'Level 1', 
            'L2' => 'Level 2', 
            'L3' => 'Level 3', 
            'L4' => 'Level 4', 
            'ML3' => 'MAN Level 3', 
            'FG' => 'F-Gas',
            'ADASL1' => 'ADAS Level 1',
            'ADASL2' => 'ADAS Level 2',
            'ADASL3' => 'ADAS Level 3',
            'LVDT' => 'LVDT',
        ];
    }

    public static function duplexTrainingLevelDesc($key)
    {
        $list = self::duplexTrainingLevelsList();

        return isset($list[$key]) ? $list[$key] : $key;
    }
}