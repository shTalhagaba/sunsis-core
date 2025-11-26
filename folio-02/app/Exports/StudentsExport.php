<?php

namespace App\Exports;

use App\Helpers\AppHelper;
use App\Models\Address;
use App\Models\Lookups\EthnicityLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
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
            'Gender',
            'Date of Birth',
            'Ethnicity',
            'National Insurance',
            'ULN',
            'Primary Email',
            'Secondary Email',
            'Username',
            'System Access',
            'Employer',
            'Employer Location',
            'Employer Location Postcode',
            'Work Address Line 1',
            'Work Address Line 2',
            'Work Address Line 3',
            'Work Address Line 4',
            'Work Postcode',
            'Work Telephone',
            'Work Mobile',
            'Home Address Line 1',
            'Home Address Line 2',
            'Home Address Line 3',
            'Home Address Line 4',
            'Home Postcode',
            'Home Telephone',
            'Home Mobile',
            'Training Records Count',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Global scope in Student model
        $query = Student::filter($this->filters)
            ->with([
                'latestAuth', 
                'employer', 
                'location',
                'addresses',
            ])
            ->leftjoin('tr', 'users.id','=','tr.student_id')
            ->select('users.*')
            ->distinct('users.id')
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM tr WHERE tr.student_id = users.id) as training_records_count'));

        AppHelper::addCaseloadConditionEloquent($query, auth()->user());

        $students = $query->get();

        $ethnicityLookup = EthnicityLookup::getSelectData();

        $result = collect();
        foreach($students AS $student)
        {
            $workAddress = $student->addresses->where('label', Address::LABEL_WORK)->first();
            $homeAddress = $student->addresses->where('label', Address::LABEL_HOME)->first();

            $result->push([
                'firstnames' => $student->firstnames,
                'surname' => $student->surname,
                'gender' => $student->gender,
                'date_of_birth' => optional($student->date_of_birth)->format('d/m/Y'),
                'ethnicity' => $ethnicityLookup[$student->ethnicity] ?? '',
                'national_insurance' => $student->ni,
                'uln' => $student->uln,
                'primary_email' => $student->primary_email,
                'secondary_email' => $student->secondary_email,
                'username' => $student->username,
                'system_access' => $student->web_access == '1' ? 'Enabled' : 'Disabled',
                'employer' => optional($student->employer)->legal_name,
                'employer_location' => optional($student->location)->address_line_1,
                'employer_location_postcode' => optional($student->location)->postcode,
                'work_address_line_1' => optional($workAddress)->address_line_1,
                'work_address_line_2' => optional($workAddress)->address_line_2,
                'work_address_line_3' => optional($workAddress)->address_line_3,
                'work_address_line_4' => optional($workAddress)->address_line_4,
                'work_postcode' => optional($workAddress)->postcode,
                'work_telephone' => optional($workAddress)->telephone,
                'work_mobile' => optional($workAddress)->mobile,
                'home_address_line_1' => optional($homeAddress)->address_line_1,
                'home_address_line_2' => optional($homeAddress)->address_line_2,
                'home_address_line_3' => optional($homeAddress)->address_line_3,
                'home_address_line_4' => optional($homeAddress)->address_line_4,
                'home_postcode' => optional($homeAddress)->postcode,
                'home_telephone' => optional($homeAddress)->telephone,
                'home_mobile' => optional($homeAddress)->mobile,
                'training_records_count' => $student->training_records_count,
            ]);

        }

        return $result;
    }
}
