<?php

namespace App\Exports;

use App\Models\Organisations\Organisation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrganisationsExport implements FromCollection, WithHeadings
{
    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            'Status',
            'Legal Name',
            'Trading Name',
            'Company Number',
            'VAT',
            'EDRS',
            'Sector',
            'Address Line 1',
            'Address Line 2',
            'Address Line 3',
            'Address Line 4',
            'Postcode',
            'Telephone',
            'Mobile',
            'Fax',
            'Locations Count',
            'Students Count',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $employers = Organisation::filter($this->filters)
            ->employers()
            ->with([
                'locations' => function ($query) {
                    $query->where('is_legal_address', 1);
                },
            ])
            ->withCount(['students', 'locations'])
            ->get();

        $result = collect();
        foreach($employers AS $employer)
        {
            $mainSite = $employer->locations->first();
            $result->push([
                $employer->active ? 'Active' : 'Not Active',
                $employer->legal_name,
                $employer->trading_name,
                $employer->company_number,
                $employer->vat_number,
                $employer->edrs,
                $employer->sector,
                optional($mainSite)->address_line_1,
                optional($mainSite)->address_line_2,
                optional($mainSite)->address_line_3,
                optional($mainSite)->address_line_4,
                optional($mainSite)->postcode,
                optional($mainSite)->telephone,
                optional($mainSite)->mobile,
                optional($mainSite)->fax,
                $employer->locations_count,
                $employer->students_count,
            ]);
        }

        return $result;
    }
}
