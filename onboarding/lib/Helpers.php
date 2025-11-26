<?php

class Helpers
{
    public static function isDigit($ch)
    {
        if(ord($ch)>=48 && ord($ch)<=57)
            return true;
        else
            return false;
    }

    public static function getKsbElementsAsArray(PDO $link, TrainingRecord $tr, SkillsAnalysis $sa)
    {
        $ksb_entries = [];
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $main_aim = $framework->getMainAim($link);
        $evidences = XML::loadSimpleXML($main_aim->evidences);
        foreach($evidences->units AS $unit_group)
        {
            $unit_group_title = $unit_group->attributes()->title->__toString();
	    if($unit_group_title != "KSB - Skills Scan")
                continue;
            foreach($unit_group->unit AS $unit)
            {
                $unit_title = $unit->attributes()->title->__toString();
                foreach($unit->element AS $element)
                {
                    $element_title = $element->attributes()->title->__toString();
                    foreach($element->evidence AS $evidence)
                    {
                        $attributes = $evidence->attributes();
                        $delhours = 0;
                        if(isset($attributes['delhours']))
                        {
                            $delhours = $evidence->attributes()->delhours->__toString();
                        }
                        if($delhours == 'null')
                        {
                            $delhours = 1;
                        }
                        $evidence_title = $evidence->attributes()->title->__toString();
                        $ksb_entries[] = [
                            'id' => null,
                            'tr_id' => $tr->id,
                            'unit_group' => $unit_group_title,
                            'unit_title' => $unit_title,
                            'evidence_title' => $evidence_title,
                            'del_hours' => trim($delhours) != '' ? $delhours : 1,
                            'skills_analysis_id' => $sa->id,
                            'score' => 0,
                        ];
                    }
                }
            }
        }

        return $ksb_entries;
    }

    public static function getValidExtensions()
    {
        return ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z'];
    }

    public static function trIdsForOtjPlanner()
    {
        return [
            107,
            120,
            127,
            157,
            174,
            194,
            208,
            243,
            292,
            660,
            685,
        ];
    }

    public static function fundModelExtraOptions()
    {
        return [
            [991, 'Learner Loan'],
            [992, 'Commercial'],
        ];
    }

    public static function array_group_by(array $items, $groupBy, $preserveKeys = false)
    {
        $nextGroups = [];
        if (is_array($groupBy)) {
            $nextGroups = $groupBy;
            $groupBy = array_shift($nextGroups);
        }

        $groupByFunc = function ($item) use ($groupBy) {
            if (is_array($item)) {
                return isset($item[$groupBy]) ? $item[$groupBy] : null;
            } elseif (is_object($item)) {
                return isset($item->$groupBy) ? $item->$groupBy : null;
            }
            return null;
        };

        $results = [];
        foreach ($items as $key => $item) {
            $groupKeys = call_user_func($groupByFunc, $item, $key);

            if (!is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }

            foreach ($groupKeys as $groupKey) {
                if (is_bool($groupKey)) {
                    $groupKey = (int)$groupKey;
                }

                $groupKey = trim($groupKey);

                if (!array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = [];
                }

                if ($preserveKeys) {
                    $results[$groupKey][$key] = $item;
                } else {
                    $results[$groupKey][] = $item;
                }
            }
        }

        // Handle nested groupBy if applicable
        if (!empty($nextGroups)) {
            foreach ($results as $groupKey => $subItems) {
                $results[$groupKey] = self::array_group_by($subItems, $nextGroups, $preserveKeys);
            }
        }

        return $results;
    }

    /**
     * Recursively convert values to UTF-8
     *
     * @param mixed $data
     * @return mixed
     */
    public static function utf8_sanitize_recursive($data) {
        if (is_array($data)) {
            return array_map([self::class, 'utf8_sanitize_recursive'], $data);
        }

        if (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'ISO-8859-1');
        }

        return $data;
    }
}