<?php

namespace App\Exports;

use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SystemUsersExport implements FromCollection, WithHeadings
{
    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'First Name(s)',
            'Surname',
            'User Type',
            'System Access',
            'Username',
            'Last Login At',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::filter($this->filters)
            ->staffUsers()
            ->with('latestAuth')
            ->get();

        $result = collect();
        foreach($users AS $user)
        {
            $result->push([
                $user->firstnames,
                $user->surname,
                UserTypeLookup::getDescription($user->user_type),
                $user->web_access == '1' ? 'Enabled' : 'Disabled',
                $user->username,
                optional($user->latestAuth)->login_at, 
            ]);
        }

        return $result;
    }
}
