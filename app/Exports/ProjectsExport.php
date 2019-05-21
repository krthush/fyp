<?php

namespace App\Exports;

use App\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ProjectsExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'id',
            'created_at',
            'updated_at',
            'user_id',
            'title',
            'description',
            'UG',
            'MSc',
            'experimental',
            'computational',
            'hidden',
            'popularity',
            'selected_user_id',
            'selected_user2_id'
        ];
    }

    public function collection()
    {
        return Project::all();
    }
}
