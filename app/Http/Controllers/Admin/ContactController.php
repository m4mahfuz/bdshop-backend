<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    private $contact;

    public function __construct(ContactService $contact)
    {
        $this->contact = $contact;
    }
    
    public function index()
    {        
        $tags = Contact::orderBy('id', 'desc')->cursorPaginate(10);

        return (new ContactCollection($tags))->additional(
            [
                'meta' => [
                    'totalContacts' => Contact::count(), //
                ]
            ]
        );
    }

   // public function store(StoreContactRequest $request)
   //  {

   //      return response([
   //          'data'=> new ContactResource(
   //              $this->contact->add($request)
   //          )
   //      ], Response::HTTP_CREATED);
   //  }

    public function update(StoreContactRequest $request, Contact $contact)
    {        
        return response([
            'data' => ContactResource::make($this->contact->update($request, $contact))
        ], Response::HTTP_OK);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }
}