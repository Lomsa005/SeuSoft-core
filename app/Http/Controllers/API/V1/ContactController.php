<?php

namespace App\Http\Controllers\API\V1;

use App\Models\contact;
use App\Http\Requests\StorecontactRequest;
use App\Http\Requests\UpdatecontactRequest;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StorecontactRequest $request)
    {
        $contact = new Contact; 
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->message = $request->message;  
        $contact->save();
        \Log::info('Request Data:', $request->all());
        return response()->json([
            'message' => 'Contact created successfully',
            'data' => $contact
        ], 201); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecontactRequest  $request
     * @param  \App\Models\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecontactRequest $request, contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(contact $contact)
    {
        //
    }
}
