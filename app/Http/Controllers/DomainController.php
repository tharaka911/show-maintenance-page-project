<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use stdClass;

class DomainController extends Controller
{
    /**
     * Display a listing of the domains.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request);
        $domain = request()->getHost(); // Get the current domain name
        $maintenanceFile = public_path($domain . "/" . $domain . '.html'); // Generate the path to the maintenance HTML file

        // Check if the maintenance HTML file exists
        if (file_exists($maintenanceFile)) {
            return response()->file($maintenanceFile);
        }

        // Fallback to a default maintenance HTML file if the specific file doesn't exist
        return response()->file(public_path('default.html'));
    }

    /**
     * Store a newly created domain in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'domain_name' => 'required',
            'project_name' => 'required|regex:/^[a-zA-Z0-9_-]+$/',
            'maintenance_page' => 'required|mimes:html',
            'maintenance_page_css' => 'required',
            'maintenance_page_js' => 'required'
        ]);


        // Store the HTML file in the public folder
        $file = $request->file('maintenance_page');
        $file2 = $request->file('maintenance_page_css');
        $file3 = $request->file('maintenance_page_js');

        // Generate the file name
        $html_page = $data['domain_name'] . '.html';
        // $css_page = $data['domain_name'] . '_style.css';
        // $js_page = $data['domain_name'] . '_script.js';

        // Move the uploaded file to the public folder with the desired file name
        $file->move(public_path($data['domain_name']), $html_page);
        $file2->move(public_path($data['domain_name']), $file2->getClientOriginalName());
        $file3->move(public_path($data['domain_name']), $file3->getClientOriginalName());


        // dd($request);

        // Create a new Domain instance and set the file name
        $domain = new Domain();
        $domain->domain_name = $data['domain_name'];
        $domain->project_name = $data['project_name'];
        $domain->maintenance_page = "public/" . $data['domain_name'] . "/" . $html_page;
        // dd($request);
        $domain->save();

        // return redirect()->back()->with('status', 'Domain created successfully.');
        return redirect()->back()->withErrors([])->withInput($data);
    }

    /**
     * Update the specified domain in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projectId)
    {
        //dd($request);

        $domain_id = Domain::findOrFail($projectId);
        dd($domain_id);
        
        $data = $request->validate([
            'project_name' => 'required',
            'maintenance_page' => 'mimes:html',
            'maintenance_page_css' => 'required',
            'maintenance_page_js' => 'required'
        ]);

        //catche the domain name form db


        $file = $request->file('maintenance_page');
        if ($file) {
            $html_page = $data['domain_name'] . '.html';
            $file->move(public_path(), $html_page);
            $data['maintenance_page'] = $html_page;
        }

        $domain->update($data);

        return redirect()->back()->withErrors([])->withInput($data);
    }

    /**
     * Remove the specified domain from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect()->back()->with('status', 'Domain deleted successfully.');
    }
}
