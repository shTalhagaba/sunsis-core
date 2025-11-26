<?php

namespace App\Http\Controllers\Organisation;

use App\Exports\OrganisationsExport;
use App\Filters\EmployerFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployerRequest;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Organisations\Location;
use App\Models\Organisations\Organisation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, EmployerFilters $filters)
    {
        $this->authorize('index', Organisation::class);

        $employers = Organisation::filter($filters)
            ->employers()
            ->with([
                'locations' => function ($query) {
                    $query->where('is_legal_address', 1);
                },
            ])
            ->withCount(['students', 'locations'])
            ->paginate(session('employers_per_page', config('model_filters.default_per_page')));

        return view('organisations.employers.index', compact('employers', 'filters'));
    }

    public function export(EmployerFilters $filters)
    {
        $this->authorize('index', Organisation::class);

        return Excel::download(new OrganisationsExport($filters), 'Employers.xlsx');
    }

    public function show($id)
    {
        $organisation = Organisation::findOrFail($id);

        abort_if(!$organisation->isEmployer(), 404);

        $this->authorize('show', $organisation);

        $organisation->load([
            'mediaSections',
            'media',
        ]);

        $systemUsers = $organisation
            ->users()
            ->where('user_type', UserTypeLookup::TYPE_EMPLOYER_USER)
            ->orderBy('firstnames')
            ->get();

        $mainDirectoryFiles = $organisation->media->filter(function ($media) {
            return ! $media->hasCustomProperty('section_name') ? true : false;
        });

        $sectionFilesCount['main'] = count($mainDirectoryFiles);
        foreach ($organisation->mediaSections as $section) {
            if (! array_key_exists($section->slug, $sectionFilesCount)) {
                $sectionFilesCount[$section->slug] = 0;
            }
            foreach ($organisation->media as $media) {
                $sectionFilesCount[$section->slug] += $media->getCustomProperty('section_name') === $section->slug ? 1 : 0;
            }
        }

        $sectionName = (request()->has('section') && in_array(request()->section, $organisation->mediaSections->pluck('slug')->toArray())) ?
            request()->section :
            '';

        $mediaFiles = $organisation->media->filter(function ($media) use ($sectionName) {
            return $sectionName != '' ? $media->getCustomProperty('section_name') === $sectionName : true;
        });

        if ($sectionName == '') {
            $mediaFiles = $mainDirectoryFiles;
        }
        return view('organisations.employers.show', compact('organisation', 'systemUsers', 'mediaFiles', 'sectionName', 'sectionFilesCount'));
    }

    public function create()
    {
        $this->authorize('createEmployer', [Organisation::class, Organisation::TYPE_EMPLOYER]);

        $orgType = Organisation::TYPE_EMPLOYER;

        $main_location = new Location();

        $sectors = Organisation::getDDLOrgSectors();

        return view('organisations.employers.create', compact('orgType', 'main_location', 'sectors'));
    }

    public function store(StoreEmployerRequest $request)
    {
        $this->authorize('createEmployer', [Organisation::class, Organisation::TYPE_EMPLOYER]);

        $validatedData = $request->validated();

        $organisation = Organisation::create($validatedData + ['org_type' => Organisation::TYPE_EMPLOYER]);

        $organisation->locations()->create([
            'is_legal_address' => 1,
            'title' => $validatedData['title'],
            'address_line_1' => $validatedData['address_line_1'],
            'address_line_2' => $validatedData['address_line_2'],
            'address_line_3' => $validatedData['address_line_3'],
            'address_line_4' => $validatedData['address_line_4'],
            'postcode' => $validatedData['postcode'],
            'telephone' => $validatedData['telephone'],
            'mobile' => $validatedData['mobile'],
            'email' => $validatedData['email'],
            'fax' => $validatedData['fax'],
        ]);

        return redirect()->route('employers.show', ['id' => $organisation->id]);
    }

    public function edit($id)
    {
        $organisation = Organisation::findOrFail($id);

        $this->authorize('updateEmployer', $organisation);

        $orgType = Organisation::TYPE_EMPLOYER;

        $main_location = $organisation->mainLocation();

        $sectors = Organisation::getDDLOrgSectors();

        return view('organisations.employers.edit', compact('organisation', 'orgType', 'main_location', 'sectors'));
    }

    public function update(StoreEmployerRequest $request, $id)
    {
        $organisation = Organisation::findOrFail($id);

        $this->authorize('updateEmployer', $organisation);

        $validatedData = $request->validated();

        $organisation->update($validatedData);

        $mainLocation = $organisation->mainLocation();

        $mainLocation->update([
            'is_legal_address' => 1,
            'title' => $validatedData['title'],
            'address_line_1' => $validatedData['address_line_1'],
            'address_line_2' => $validatedData['address_line_2'],
            'address_line_3' => $validatedData['address_line_3'],
            'address_line_4' => $validatedData['address_line_4'],
            'postcode' => $validatedData['postcode'],
            'telephone' => $validatedData['telephone'],
            'mobile' => $validatedData['mobile'],
            'email' => $validatedData['email'],
            'fax' => $validatedData['fax'],
        ]);

        return redirect()->route('employers.show', ['id' => $organisation->id]);
    }
}
