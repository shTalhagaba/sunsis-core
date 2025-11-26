<?php

namespace App\Http\Controllers\Programme;

use App\Exports\ProgrammesExport;
use App\Filters\ProgrammeFilters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgrammeRequest;
use App\Models\Programmes\Programme;
use App\Services\Programmes\ProgrammeService;
use Maatwebsite\Excel\Facades\Excel;


class ProgrammesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }
    
    public function index(Request $request, ProgrammeFilters $filters)
    {
        $this->authorize('index', Programme::class);

        $programmes = Programme::filter($filters)
            ->with(['programmeType'])
            ->withCount(['qualifications'])
            ->paginate(session('programmes_per_page', config('model_filters.default_per_page')));

        return view('programmes.index', compact('programmes', 'filters'));
    }

    public function export(ProgrammeFilters $filters)
    {
	    $this->authorize('export', Programme::class);

        return Excel::download(new ProgrammesExport($filters), 'programmes.xlsx');
    }

    public function create()
	{
	    $this->authorize('create', Programme::class);

		return view('programmes.create');
    }

    public function edit(Programme $programme)
    {
	    $this->authorize('update', $programme);

        return view('programmes.edit', compact('programme'));
    }

    public function store(StoreProgrammeRequest $request, ProgrammeService $programmeService)
	{
	    $this->authorize('create', Programme::class);

        $programme = $programmeService->create( $request->validated() );

        return redirect()
            ->route('programmes.show', $programme)
            ->with(['alert-success' => 'Programme has been created successfully.']);
    }

    public function update(StoreProgrammeRequest $request, Programme $programme, ProgrammeService $programmeService)
    {
	    $this->authorize('update', $programme);

        $programme = $programmeService->update($request->validated(), $programme);

        return redirect()
            ->route('programmes.show', $programme)
            ->with(['alert-success' => 'Programme has been updated successfully.']);
    }

    public function show(Programme $programme)
    {
	    $this->authorize('show', $programme);

        $programme->load([
            'qualifications',
            'sessions',
            'mediaSections',
            'media',
        ]);

        $mainDirectoryFiles = $programme->media->filter(function($media) {
            return ! $media->hasCustomProperty('section_name') ? true : false;
        });

        $sectionFilesCount['main'] = count($mainDirectoryFiles);
        foreach($programme->mediaSections AS $section)
        {
            if(! array_key_exists($section->slug, $sectionFilesCount))
            {
                $sectionFilesCount[$section->slug] = 0;
            }
            foreach($programme->media AS $media)
            {
                $sectionFilesCount[$section->slug] += $media->getCustomProperty('section_name') === $section->slug ? 1 : 0;
            }
    
        }

        $sectionName = (request()->has('section') && in_array(request()->section, $programme->mediaSections->pluck('slug')->toArray())) ? 
            request()->section : 
            '';

        $mediaFiles = $programme->media->filter(function($media) use ($sectionName) {
            return $sectionName != '' ? $media->getCustomProperty('section_name') === $sectionName : true;
        });

        if($sectionName == '')
        {
            $mediaFiles = $mainDirectoryFiles;
        }

        return view('programmes.show', compact('programme', 'mediaFiles', 'sectionName', 'sectionFilesCount'));
    }

    public function destroy(Programme $programme)
    {
        $this->authorize('delete', $programme);

        $programme->delete();

        return redirect()
            ->route('programmes.index')
            ->with(['alert-success' => 'Programme is now deleted successfully.']);
    }   
}
