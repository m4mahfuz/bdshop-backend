<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
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
    
    public function __invoke(StoreContactRequest $request)
    {

        return response([
            'data'=> new ContactResource(
                $this->contact->add($request)
            )
        ], Response::HTTP_CREATED);
    }
}