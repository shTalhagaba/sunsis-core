<?php

namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganisationContactRequest;
use App\Models\Organisations\Organisation;
use App\Models\Organisations\OrganisationContact;

class ContactController extends Controller
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

        return view('organisations.contacts.create', compact('organisation', 'cancelLink'));
    }

    public function store(Organisation $organisation, StoreOrganisationContactRequest $request)
	{
        $organisation->contacts()->create($request->validated());
        
		return redirect( route($organisation->getRouteName(), $organisation) )
            ->with([
                'alert-success' => 'Contact is created successfully.'
            ]);
	}

    public function edit(Organisation $organisation, OrganisationContact $contact)
    {
        $cancelLink = 'history.back()';
        if($organisation->isEmployer())
        {
            $cancelLink = 'window.location.href="' . route('employers.show', $organisation) . '"';
        }

        return view('organisations.contacts.edit', compact('organisation', 'contact', 'cancelLink'));
    }

    public function update(Organisation $organisation, OrganisationContact $contact, StoreOrganisationContactRequest $request)
    {
        $contact->update($request->validated());

        return redirect( route($organisation->getRouteName(), $organisation) )
            ->with([
                'alert-success' => 'Contact is updated successfully.'
            ]);
    }

    public function destroy(Organisation $organisation, OrganisationContact $contact)
    {
        $contact->delete();

        return back()->with([
            'alert-success' => 'Delete success, contact has been deleted successfully.'
        ]);
    }
}