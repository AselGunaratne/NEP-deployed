<?php

namespace LandParcel\Http\Controllers;

use App\Models\Land_Parcel;
use App\Models\Province;
use App\Models\District;
use App\Models\GS_Division;
use App\Models\Organization;
use App\Models\Gazette;
use App\Models\Land_Has_Gazette;
use App\Models\Land_Has_Organization;
use App\Models\Process_Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class LandController extends Controller
{
    public function form()
    {
        $gazettes = Gazette::all();
        $organizations = Organization::all();
        return view('land::form', [
            'organizations' => $organizations,
            'gazettes' => $gazettes,
        ]);
    }

    public function save(Request $request)
    {

        $request->validate([
            'landTitle' => 'required',
            'governing_orgs' => 'required',
            'gazettes' => 'required',
            'polygon' => 'required'
        ]);

        $land = new Land_Parcel();
        $land->title = request('landTitle');
        $land->governing_organizations = request('governing_orgs');
        $land->polygon = request('polygon');
        $land->created_by_user_id = request('createdBy');
        if (request('isProtected')) {
            $land->protected_area = request('isProtected');
        }
        $land->status_id = 1;
        $land->save();

        $landid = Land_Parcel::latest()->first()->id;

        $governing_organizations = request('governing_orgs');

        foreach ($governing_organizations as $governing_organization) {
            $land_has_organization = new Land_Has_Organization();
            $land_has_organization->land_parcel_id = $landid;
            $land_has_organization->organization_id = $governing_organization;
            $land_has_organization->save();
        }

        $gazettes = request('gazettes');

        foreach ($gazettes as $gazette) {
            $land_has_gazette = new Land_Has_Gazette();
            $land_has_gazette->land_parcel_id = $landid;
            $land_has_gazette->gazette_id = $gazette;
            $land_has_gazette->save();
        }

        foreach ($governing_organizations as $governing_organization) {
            $process = new Process_Item();
            $process->form_type_id = 5;
            $process->form_id = $landid;
            $process->created_by_user_id = request('createdBy');
            $process->request_organization = Auth::user()->organization_id;
            $process->activity_organization = $governing_organization;
            $process->status_id = 1;
            $process->save();
        }
        $processnewid= Process_Item::latest()->first()->id;
        //dd($processnewid);
        Process_Item::where('form_id',$landid)->where('id','!=',$processnewid)->update([
            'prerequisite' => 1,
            'prerequisite_id' =>$processnewid,
        ]);
        // if (request('file')) {
        //     $fileloc = request('file');
        //     Storage::delete('public/'.$fileloc);
        //     File::delete(public_path($fileloc));
        //     Storage::delete($fileloc);
        // }
        return redirect('/general/pending')->with('message', 'Request Created Successfully');
    }
    public function show($id)
    {
        $item = Process_Item::find($id);
        $land_data = Land_Parcel::find($item->form_id);
        //using the M:M relationship
        $governing = Land_Parcel::find($item->form_id)->organizations;

        return view('land::show', [
            'land' => $land_data,
            'polygon' => $land_data->polygon,
            'governing_orgs' => $governing,
        ]);
    }

    function action(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'select_file' => 'required'
        ]);
        if ($validation->passes()) {
            $image = $request->file('select_file');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('kml'), $new_name);
            return response()->json([
                'message'   => 'Image Upload Successfully',
                'uploaded_image' => "kml/$new_name",
                'class_name'  => 'alert-success'
            ]);
        } else {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name'  => 'alert-danger'
            ]);
        }
    }
}
