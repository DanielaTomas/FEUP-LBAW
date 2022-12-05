<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('pages.staticPages.contact');
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
}