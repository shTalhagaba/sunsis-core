<?php

namespace App\Exports;

use App\Models\Programmes\Programme;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProgrammeQualificationsExport implements FromCollection, WithHeadings
{
    private $programme;

    public function __construct(Programme $programme)
    {
        $this->programme = $programme;
    }

    public function headings(): array
    {
        return [
            'Programme Title',
            'QAN',
            'Qualification Title',
            'Qualification Sequence',
            'Main Qualification',
            'Qualification Proportion',
            'Qualification Duration',
            'Qualification Offset',
            'Unit Count',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $result = collect();
        foreach($this->programme->qualifications AS $qualification)
        {
            $result->push([
                $this->programme->title,

                $qualification->qan,
                $qualification->title,
                $qualification->sequence,
                $qualification->main,
                $qualification->proportion,
                $qualification->duration,
                $qualification->offset,
                $qualification->units()->count(),
            ]);
        }

        return $result;
    }
}
