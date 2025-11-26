<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SunesisHelper
{
    /**
     * Get the database connection for 'mysql_sun'.
     *
     * @return \Illuminate\Database\Connection
     */
    protected static function db()
    {
        return DB::connection('mysql_sun');
    }

    /**
     * Get dropdown values from a lookup table.
     *
     * @param string $lookupTable
     * @param string $key
     * @param string $value
     * @return array
     */
    public static function getDropdown($lookupTable, $key = 'id', $value = 'description')
    {
        return self::db()
            ->table($lookupTable)
            ->select($key, $value)
            ->orderBy($value, 'asc')
            ->pluck($value, $key)
            ->toArray();
    }

    /**
     * Get single record from a table.
     *
     * @param string $table
     * @param string $columnValue
     * @param string $columnName
     * @param array $extraConditions Where conditions in the format [['column', 'operator', 'value'], ...].
     * @return object
     */
    public static function getSingleRow($table, $columnValue, $columnName = 'id', $extraConditions = [])
    {
        $query = self::db()
            ->table($table)
            ->where($columnName, $columnValue);

        // Apply extra conditions
        foreach ($extraConditions as $condition) 
        {
            $query->where($condition[0], $condition[1], $condition[2]);
        }

        return $query->first();
    }

    /**
     * Get multiple records from a table with joins.
     *
     * @param string $table The main table.
     * @param array $joins Array of joins in the format [['table', 'first', 'operator', 'second'], ...].
     * @param array $conditions Where conditions in the format [['column', 'operator', 'value'], ...].
     * @param array $columns Columns to select.
     * @return \Illuminate\Support\Collection
     */
    public static function getRowsWithJoins($table, array $joins, array $conditions, array $columns = ['*'])
    {
        $query = self::db()->table($table)->select($columns);

        // Apply joins
        foreach ($joins as $join) 
        {
            $query->join($join[0], $join[1], $join[2], $join[3]);
        }

        // Apply where conditions
        foreach ($conditions as $condition) 
        {
            $query->where($condition[0], $condition[1], $condition[2]);
        }

        return $query->get();
    }


    public static function searchLearner($learnerFields = [], $withTrainings = true)
    {
        $query = self::db()
            ->table('users')
            ->distinct()
            ->select(
                'users.id as user_id',
                'users.username',
                'users.firstnames',
                'users.surname'
            )
            ->leftJoin('tr', 'tr.username', '=', 'users.username')
            ->leftJoin('student_frameworks', 'student_frameworks.tr_id', '=', 'tr.id')
            ->leftJoin('users as assessors', 'assessors.id', '=', 'tr.assessor')
            ->leftJoin('users as verifiers', 'verifiers.id', '=', 'tr.verifier')
            ->leftJoin('users as tutors', 'tutors.id', '=', 'tr.tutor')
            ;

        if ($withTrainings) {
            $query->addSelect(
                'tr.id as tr_id',
                'tr.start_date',
                'tr.target_date as planned_end_date',
                'tr.status_code',
                'student_frameworks.title as framework_title',
                'student_frameworks.id as framework_id',
                'assessors.id as assessor_id',
                'verifiers.id as verifier_id',
                'tutors.id as tutor_id',
                DB::raw('CONCAT(assessors.firstnames, " ", assessors.surname) as primary_assessor'),
                DB::raw('CONCAT(verifiers.firstnames, " ", verifiers.surname) as iqa'),
                DB::raw('CONCAT(tutors.firstnames, " ", tutors.surname) as tutor')
            )
            ->addSelect(DB::raw('(SELECT legal_name FROM organisations WHERE tr.employer_id = organisations.id) AS employer_name'))
            ->addSelect(DB::raw('(SELECT description FROM lookup_pot_status WHERE tr.status_code = lookup_pot_status.code) AS training_status'))
            ;
        }

        $updateFields = [
            'firstnames' => 'users.firstnames',
            'surname' => 'users.surname',
            'username' => 'users.username',
            'tr_id' => 'tr.id',
        ];

        foreach ($learnerFields as $field => $value) 
        {
            if ($value) 
            {
                if(isset($updateFields[$field]))
                    $query->where($updateFields[$field], 'like', "%$value%");
                else
                    $query->where($field, 'like', "%$value%");
            }
        }

        return $query->where('users.type', self::SUNESIS_TYPE_LEARNER)->get();
    }

    const SUNESIS_TYPE_LEARNER = 5;
}