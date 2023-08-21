<?php

namespace App\Services;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\Response;

class ContactService 
{

	public function add(StoreContactRequest $request)
	{
		$contact = Contact::create([
		    'name' => $request->getName(),
		    'email' => $request->getEmail(),
		    'message' => $request->getMessage(),		    
		]);
		
		return $contact;
	}

	public function update(StoreContactRequest $request, Contact $contact)
	{		
		$contact->update([
			'name' => $request->getName(),
			'email' => $request->getEmail(),
		    'message' => $request->getMessage(),		    
			'read' => $request->getReadStatus(),
		]);
		
		return $contact;
	}	
	
}