<?php

namespace DevelopmentProject\Http\Controllers;

use App\Models\Development_Project;
use App\Models\Land_Parcel;
use App\Models\Gazette;
use App\Models\Organization;
use App\Models\User;
use App\Models\District;
use App\Models\GS_Division;
use App\Models\Process_Item;
use App\Models\Land_Has_Organization;
use App\Models\Land_Has_Gazette;
use Illuminate\Http\Request;
use App\Models\Test_Map;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicationMade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class DevelopmentProjectController extends Controller
{

    //Returns the view for the application form passing in data of lands, organziations and gazettes
    public function form()
    {
        $gazettes = Gazette::all();
        $organizations = Organization::where('type_id', '=', '1')->get();
        return view('developmentProject::form', [
            'organizations' => $organizations,
            'gazettes' => $gazettes,
        ]);
    }

    // Saves the form to the development projects table as well as creates 1 or more entries in the process items table
    // depenign on the number of governing organizations selected.
    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'landTitle' => 'required|unique:land_parcels,title',
            'organization' => 'required|exists:organizations,title',
            'gazette' => 'nullable|exists:gazettes,gazette_number',
            'polygon' => 'required',
            'district' => 'required|exists:districts,district',
            'gs_division' => 'required|exists:gs_divisions,gs_division',
            'description' => 'required',
            'externalRequestor' => 'nullable|regex:/^[0-9]{9}[vVxX]$/',
            'erEmail' => 'nullable|email',
            'land_extent' => 'nullable|numeric|between:0,99.999',
            'land_gazettes' => 'nullable',
            'land_governing_orgs' => 'nullable',
        ]);



        DB::transaction(function () use ($request) {
            $land = new Land_Parcel();
            $land->title = request('landTitle');

            $land->polygon = request('polygon');

            $land->created_by_user_id = request('createdBy');

            if (request('isProtected')) {
                $land->protected_area = request('isProtected');
            }

            $district_id1 = District::where('district', request('district'))->pluck('id');
            $land->district_id = $district_id1[0];

            $gs_division_id1 = GS_Division::where('gs_division', request('gs_division'))->pluck('id');
            $land->gs_division_id = $gs_division_id1[0];

            $organization_id1 = Organization::where('title', request('organization'))->pluck('id');
            $land->activity_organization = $organization_id1[0];

            $land->status_id = 1;
            $land->save();

            $landid = Land_Parcel::latest()->first()->id;

            if (request('land_governing_orgs')) {
                $governing_organizations = request('land_governing_orgs');

                foreach ($governing_organizations as $governing_organization) {
                    $land_has_organization = new Land_Has_Organization();
                    $land_has_organization->land_parcel_id = $landid;
                    $land_has_organization->organization_id = $governing_organization;
                    $land_has_organization->save();
                }
            }

            if (request('land_gazettes')) {
                $gazettes = request('land_gazettes');

                foreach ($gazettes as $gazette) {
                    $land_has_gazette = new Land_Has_Gazette();
                    $land_has_gazette->land_parcel_id = $landid;
                    $land_has_gazette->gazette_id = $gazette;
                    $land_has_gazette->save();
                }
            }


            $dev = new Development_Project();
            $dev->title = request('title');

            if (request('isProtected')) {
                $dev->protected_area = request('isProtected');
            }

            $dev->created_by_user_id = request('createdBy');

            if (request('land_extent')) {
                $dev->land_size = request('land_extent');
            }

            $dev->description = request('description');

            $dev->organization_id = $organization_id1[0];

            if (request('gazette')) {
                $gazette = Gazette::where('gazette_number', request('gazette'))->pluck('id');
                $dev->gazette_id = $gazette[0];
            }

            $dev->status_id = 1;

            $dev->land_parcel_id = $landid;

            $dev->save();

            //saving the images to the db
            $latest = Development_Project::latest()->first();
            if (request('images')) {
                //dd($request->images);
                $i = count($request->images);
                for ($y = 0; $y < $i; $y++) {
                    $file = $request->images[$y];
                    $filename = $file->getClientOriginalName();
                    $newname = $latest->id . 'NO' . $y . $filename;
                    $path = $file->storeAs('developmentproject', $newname, 'public');
                    $photoarray[$y] = $path;
                }
                $dev = Development_Project::where('id', $latest->id)->update(['images' => json_encode($photoarray)]);
            }


            $devProcess = new Process_Item();
            $devProcess->form_id = $latest->id;
            $devProcess->form_type_id = 2;
            $devProcess->created_by_user_id = request('createdBy');

            if (request('checkExternalRequestor')) {
                $devProcess->ext_requestor = request('externalRequestor');
                $devProcess->ext_requestor_email = request('erEmail');
            } else {
                $devProcess->request_organization = auth()->user()->organization_id;
            }
            $devProcess->activity_organization = $organization_id1[0];

            $devProcess->status_id = 1;

            $devProcess->save();

            $users = User::where('role_id', '<', 3)->get();
            Notification::send($users, new ApplicationMade($devProcess));
        
            $latestDevProcess = Process_Item::latest()->first();
            $landProcess = new Process_Item();
            $landProcess->form_id = $landid;
            $landProcess->remark = "Verify these land details";
            $landProcess->prerequisite = 0;
            
            if (request('checkExternalRequestor')) {
                $landProcess->ext_requestor = request('externalRequestor');
                $landProcess->ext_requestor_email = request('erEmail');
            } else {
                $landProcess->request_organization = auth()->user()->organization_id;
            }
            $organization_id1 = Organization::where('title', request('organization'))->pluck('id');
            $landProcess->activity_organization = $organization_id1[0];

            $landProcess->status_id = 1;
            $landProcess->form_type_id = 5;
            $landProcess->created_by_user_id = request('createdBy');
            $landProcess->prerequisite_id = $latestDevProcess->id;
            $landProcess->save();
        
            $Users = User::where('role_id', '<', 3)->get();
            Notification::send($Users, new ApplicationMade($landProcess));
        });

        //making a downloadable version of the KML file
        try {
            $kml = request('kml');
            Storage::put('attempt1.kml', $kml);
        } catch (\Exception $e) {
            dd($e);
        }
        return redirect('/general/pending')->with('message', 'Request Created Successfully');
    }

    public function show($id)
    {
        $process_item = Process_Item::find($id);
        $development_project = Development_Project::find($process_item->form_id);
        
        $Photos=Json_decode($development_project->images);
        
        $land_data = Land_Parcel::find($development_project->land_parcel_id);
        return view('developmentProject::show', [
            'development_project' => $development_project,
            'polygon' => $land_data->polygon,
            'Photos' =>$Photos,
        ]);
    }

    public function gazetteAutocomplete(Request $request)
    {
        $data = Gazette::select("gazette_number")
            ->where("gazette_number", "LIKE", "%{$request->terms}%")
            ->get();

        return response()->json($data);
    }
}
