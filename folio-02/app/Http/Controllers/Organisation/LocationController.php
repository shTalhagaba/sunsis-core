<?php

namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganisationLocationRequest;
use App\Models\Organisations\Location;
use App\Models\Organisations\Organisation;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function create($organisationId)
    {
        $organisation = Organisation::findOrFail($organisationId);

        $cancelLink = 'history.back()';
        if($organisation->isEmployer())
        {
            $cancelLink = 'window.location.href="' . route('employers.show', $organisation) . '"';
        }

        return view('organisations.locations.create', compact('organisation', 'cancelLink'));
    }

    public function store(Organisation $organisation, StoreOrganisationLocationRequest $request)
	{
        $organisation->locations()->create($request->validated());
        
		return redirect($request->referer)
            ->with([
                'alert-success' => 'Location is created successfully.'
            ]);
	}

    public function show(Organisation $organisation, Location $location)
	{
        return response()->json($location, 200);
    }

    public function edit(Organisation $organisation, Location $location)
    {
        $cancelLink = 'history.back()';
        if($organisation->isEmployer())
        {
            $cancelLink = 'window.location.href="' . route('employers.show', $organisation) . '"';
        }

        return view('organisations.locations.edit', compact('organisation', 'location', 'cancelLink'));
    }

    public function update(Organisation $organisation, Location $location, StoreOrganisationLocationRequest $request)
    {
        $location->update($request->validated());

        return redirect($request->referer)
            ->with([
                'alert-success' => 'Location is updated successfully.'
            ]);
    }

    public function destroy(Organisation $organisation, Location $location)
    {
        if($location->students()->count() > 0)
        {
            return back()->with([
                'alert-danger' => 'Delete aborted, this location has student records in the system.'
            ]);
        }

        if($location->locations()->count() == 1)
        {
            return back()->with([
                'alert-danger' => 'Delete aborted, you cannot delete the only location of this organisation.'
            ]);
        }

        $location->delete();

        return back()->with([
            'alert-success' => 'Delete success, location has been deleted successfully.'
        ]);
    }
}
