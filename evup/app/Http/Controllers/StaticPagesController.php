<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class StaticPagesController extends Controller
{
    /**
     * Displays about us page
     * 
     * @return View
     */
    public function getAboutUs()
    {
        return view('pages.staticPages.aboutUs');
    }

    /**
     * Displays about us page
     * 
     * @return View
     */
    public function getContactUs()
    {
        return view('pages.staticPages.contactUs');
    }

    /**
     * Displays about us page
     * 
     * @return View
     */
    public function getFaq()
    {
        return view('pages.staticPages.faq');
    }


    public function saveContact(Request $request) { 
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $contact = new Contact;

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->message = $request->message;

        $contact->save();

        Mail::send('contact-email',
             array(
                 'name' => $request->get('name'),
                 'email' => $request->get('email'),
                 'subject' => $request->get('subject'),
                 'user_message' => $request->get('message'),
             ), function($message) use ($request)
               {
                  $message->from($request->email);
                  $message->to('admin@evup.com');
               });

        return back()->with('success', 'Thank you for contacting us!');
    }
}