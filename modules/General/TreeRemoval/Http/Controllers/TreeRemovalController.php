<?php

namespace TreeRemoval\Http\Controllers;

use App\Models\Tree_Removal_Request;
use App\Models\Land_Parcel;
use App\Models\Land_Has_Gazette;
use App\Models\Land_Has_Organization;
use App\Models\Province;
use App\Models\District;
use App\Models\GS_Division;
use App\Models\Organization;
use App\Models\Process_Item;
use App\Models\Gazette;
use App\Models\Species_Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicationMade;
use App\Models\User;
use Illuminate\Support\Facades\Storage;



class TreeRemovalController extends Controller
{
    public function openForm()
    {
        $gazettes = Gazette::all();
        $organizations = Organization::where('type_id','=','1')->get();
        return view('treeRemoval::form', [
            'organizations' => $organizations,
            'gazettes' => $gazettes,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'landTitle' => 'required|unique:land_parcels,title',
            'district' => 'required|exists:districts,district',
            'gs_division' => 'required|exists:gs_divisions,gs_division',
            'activity_organization' => 'required|exists:organizations,title',
            'polygon' => 'required',
            'number_of_trees' => 'required|integer',
            'description' => 'required',
            'land_extent' => 'nullable|numeric|between:0,99.999',
            'number_of_tree_species' => 'nullable|integer',
            'number_of_flora_species' => 'nullable|integer',
            'number_of_reptile_species' => 'nullable|integer',
            'number_of_mammal_species' => 'nullable|integer',
            'number_of_amphibian_species' => 'nullable|integer',
            'number_of_fish_species' => 'nullable|integer',
            'number_of_avian_species' => 'nullable|integer',
            'externalRequestor' => 'nullable|regex:regex:/^[0-9]{9}[vVxX]$/',
            'erEmail' => 'nullable|email',
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

            $organization_id1 = Organization::where('title', request('activity_organization'))->pluck('id');
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

            $tree = new Tree_Removal_Request();
            //Required and/or filled in fields
            $tree->created_by_user_id = request('createdBy');
            $tree->description = request('description');
            $tree->no_of_trees = request('number_of_trees');

            //$district_id1 = District::where('district', request('district'))->pluck('id');
            $tree->district_id = $district_id1[0];

            //$gs_division_id1 = GS_Division::where('gs_division', request('gs_division'))->pluck('id');
            $tree->gs_division_id = $gs_division_id1[0];

            $tree->organization_id = $organization_id1[0];

            //Default value/ non-compulsory fields
            if (request('land_extent')) {
                $tree->land_size = request('land_extent');
            }
            if (request('number_of_flora_species')) {
                $tree->no_of_flora_species = request('number_of_flora_species');
            }
            if (request('number_of_avian_species')) {
                $tree->no_of_avian_species = request('number_of_avian_species');
            }
            if (request('number_of_reptile_species')) {
                $tree->no_of_reptile_species = request('number_of_reptile_species');
            }
            if (request('number_of_reptile_species')) {
                $tree->no_of_amphibian_species = request('number_of_amphibian_species');
            }
            if (request('number_of_mammal_species')) {
                $tree->no_of_mammal_species = request('number_of_mammal_species');
            }
            if (request('number_of_tree_species')) {
                $tree->no_of_tree_species = request('number_of_tree_species');
            }
            if (request('species_special_notes')) {
                $tree->species_special_notes = request('species_special_notes');
            }

            $tree->status_id = 1;

            $tree->land_parcel_id = $landid;

            $tree->tree_details = request('location');
            $tree->images = "{}";
            $tree->save();
            
            //saving the images to the db
            $latest = Tree_Removal_Request::latest()->first();
            if (request('images')) {
                //dd($request->images);
                $i = count($request->images);
                for ($y = 0; $y < $i; $y++) {
                    $file = $request->images[$y];
                    $filename = $file->getClientOriginalName();
                    $newname = $latest->id . 'NO' . $y . $filename;
                    $path = $file->storeAs('treeRemoval', $newname, 'public');
                    $photoarray[$y] = $path;
                }
                $tree = Tree_Removal_Request::where('id', $latest->id)->update(['images' => json_encode($photoarray)]);
            }

            $treeProcess = new Process_Item();
            $treeProcess->form_type_id = 1;
            $treeProcess->form_id = $latest->id;
            $treeProcess->created_by_user_id = request('createdBy');

            if (request('checkExternalRequestor')) {
                $treeProcess->ext_requestor = request('externalRequestor');
                $treeProcess->ext_requestor_email = request('erEmail');
            } else {
                $treeProcess->request_organization = auth()->user()->organization_id;
            }
            $treeProcess->activity_organization = $organization_id1[0];

            $treeProcess->status_id = 1;
            //dd($treeProcess);
            $treeProcess->save();

            $Users = User::where('role_id', '<', 3)->get();
            Notification::send($Users, new ApplicationMade($treeProcess));

            $latestTreeProcess = Process_Item::latest()->first();
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
            $organization_id1 = Organization::where('title', request('activity_organization'))->pluck('id');
            $landProcess->activity_organization = $organization_id1[0];

            $landProcess->status_id = 1;
            $landProcess->form_type_id = 5;
            $landProcess->created_by_user_id = request('createdBy');
            $landProcess->prerequisite_id = $latestTreeProcess->id;
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
        $item = Process_Item::find($id);
        $tree_removal = Tree_Removal_Request::find($item->form_id);
        $Photos=Json_decode($tree_removal->images);
        $location_data = $tree_removal->tree_details;
        $land_data = Land_Parcel::find($tree_removal->land_parcel_id);
        return view('treeRemoval::show', [
            'tree' => $tree_removal,
            'location' => $location_data,
            'polygon' => $land_data->polygon,
            'Photos' =>$Photos,
        ]);
    }

    //Typeahead AutoCompletes
    public function provinceAutocomplete(Request $request)
    {
        $data = Province::select("province")
            ->where("province", "LIKE", "%{$request->terms}%")
            ->get();

        return response()->json($data);
    }

    public function districtAutocomplete(Request $request)
    {
        $data = District::select("district")
            ->where("district", "LIKE", "%{$request->terms}%")
            ->get();

        return response()->json($data);
    }

    public function organizationAutocomplete(Request $request)
    {
        $data = Organization::select("title")
            ->where("title", "LIKE", "%{$request->terms}%")
            ->get();

        return response()->json($data);
    }

    public function GSAutocomplete(Request $request)
    {
        $data = GS_Division::select("gs_division")
            ->where("gs_division", "LIKE", "%{$request->terms}%")
            ->get();

        return response()->json($data);
    }

    public function SpeciesAutocomplete(Request $request)
    {
        $data = Species_Information::select("title")
            ->where("title", "LIKE", "%{$request->terms}%")
            ->get();

        return response()->json($data);
    }
}
