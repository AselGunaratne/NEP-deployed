<?php

namespace ApprovalItem\Http\Controllers;
use App\Models\User;
use App\Models\Organization;
use App\Models\Crime_report;
use App\Models\tree_removal_request;
use App\Models\Development_Project;
use App\Models\Environment_Restoration;
use App\Models\Environment_Restoration_Species;
use App\Models\Process_Item;
use App\Models\Form_Type;
use App\Models\Process_item_progress;
use App\Models\Process_item_status;
use App\Models\land_parcel;
use App\Models\Land_Has_Gazette;
use App\Models\Land_Has_Organization;
use App\Models\Environment_Restoration_Activity;
use App\Mail\RequestApproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\AssignOrganization;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
Use App\Notifications\StaffAssigned;
Use App\Notifications\AssignOrg;
use Illuminate\Support\Facades\Storage;
use PDF;
use Redirect;


class ApprovalItemController extends Controller
{
    
    public function confirm_assign_staff($id,$pid)
    {
        $array=DB::transaction(function () use($id,$pid){
            $Process_item =Process_item::find($pid);
        $new_assign=1;
        if($Process_item->activity_user_id != null){
            $new_assign='0';
        } 
        
        Process_item::where('id',$pid)->update([
            'activity_user_id' => $id,
            'status_id' => 3
            ]);
        $user = User::find($id);
        Notification::send($user, new StaffAssigned($Process_item));
        return $new_assign;
        });
        if($array == 0){
            return back()->with('message', 'Authority changed Successfully'); 
        }
        return back()->with('message', 'Authority assigned Successfully'); 
    }

    public function change_assign_organization($id,$pid)
    {
        DB::transaction(function () use($id,$pid){
            $Process_item =Process_item::find($pid);
            $Users = User::where([
                ['role_id', '=' , 3],           
                ['organization_id', '=', $id], 
            ])->orWhere([
                ['role_id', '=' , 4],           
                ['organization_id', '=', $id], 
            ])->get();
            Process_item::where('id',$pid)->update([
                'activity_organization' => $id ,
                'status_id' => 2
                ]);
            Process_item::where([
                ['prerequisite_id','=',$Process_item],
                ['prerequisite', '=' ,0],
                ])->update([
                    'activity_organization' => $id ,
                    'status_id' => 2
                    ]);
            Notification::send($Users, new AssignOrg($Process_item));
        });
        
        return back()->with('message', 'Assigned Organization Successfully'); 
    }

    public function assign_unregistered_organization(request $request)
    {
        $request -> validate([
            'organization' => 'required',
            'email' => 'required',
        ]);
        $array=DB::transaction(function () use($request){

        Process_item::where('id',$request['process_id'])->update([
            'ext_requestor' => $request['organization'],
            'status_id' => 2
        ]);
        $process_item =Process_item::find($request['process_id']);
        return($process_item);
        });
        $user =User::find($request['create_by']);
        if($array->form_type_id == '1'){ 
            $item = Tree_Removal_Request::find($array->form_id);
            $Photos=Json_decode($item->images);
            $tree_data = $item->tree_locations;
        } 
        else if($array->form_type_id == '2'){
            $item = Development_Project::find($array->form_id);
            $Photos=Json_decode($item->images);
            $tree_data = null;
        }
        else if($array->form_type_id == '3'){
            $item = Environment_Restoration::find($array->form_id);
            $Photos=null;
            $tree_data = Environment_Restoration_Species::all()->where('environment_restoration_id',$item->id);
            $Land_Organizations =Land_Has_Organization::where('land_parcel_id',$item->land_parcel_id)->get();
            //dd($tree_data);
            
            //dd($tree_data,$Land_Organizations);
        }
        else if($array->form_type_id == '4'){
            $item = Crime_report::find($array->form_id);
            $Photos=Json_decode($item->photos);
            $tree_data = null;
        }
        $land_parcel = Land_Parcel::find($item->land_parcel_id);
        //dd($array);
        //
        $pdf = PDF ::loadView('approvalItem::index',[
            'process_item' => $array,
            'user' =>$user,
            'item' => $item,
            'polygon' => $land_parcel->polygon,
            'tree_data' =>$tree_data,
        ]);
        
        $array->requestor_email=$request['email'];
        
        $process_item = $array->toarray();
        if($Photos != null){
            for($y=0;$y<count($Photos);$y++){
                //return Storage::disk('public')->download($photo);
                $contents[$y] =  Storage::disk('public')->get($Photos[$y]);
            }
        }
        if(isset($contents)){
            Mail::send('emails.assignorg', $process_item, function($message) use ($pdf,$contents,$Photos,$process_item){
            
                $message->to($process_item['requestor_email']);
                $message->subject('Assigning application');
                $message->attachData($pdf->output(),'document.pdf');
                for($y=0;$y<count($contents);$y++){
                    $message->attachData($contents[$y],$Photos[$y]);
                }
    
            }); 
        }
        else{
            Mail::send('emails.assignorg', $process_item, function($message) use ($pdf,$process_item){
            
                $message->to($process_item['requestor_email']);
                $message->subject('Assigning application');
                $message->attachData($pdf->output(),'document.pdf');
    
            }); 
        }
        
        
        
        
        return back()->with('message', 'Successfully forwarded the application through email'); 
    }

    public function showRequests()
    {
        $items = Process_Item::where([
            ['created_by_user_id', '=', Auth::user()->id],
            ['form_type_id', '<', 5],
        ])->get();
        return view('approvalItem::requests', [
            'items' => $items,
        ]);
    }

    public function choose_assign_staff($id)
    {
        $Process_item =Process_item::find($id);
        if($Process_item->status_id>2){
            return redirect()->action(
                [ApprovalItemController::class, 'investigate'], ['id' => $id]
            );
        } 
        $organization=Auth::user()->organization_id;
        $Prerequisites=Process_item::all()->where('prerequisite_id',$Process_item->id);
        $Organizations=Organization::all();
        if(Auth::user()->role_id=='3'){
            $Users = User::where([
                ['role_id', '>' , 3],           
                ['organization_id', '=', $organization], 
            ])->get();
        }
        else{
            $Users = User::where([
                ['role_id', '=' , 5],           
                ['organization_id', '=', $organization], 
            ])->get();
        }         
        $landProcess=Process_item::where([
            ['prerequisite_id', '=' , $Process_item->id],           
            ['prerequisite', '=', 0], 
        ])->first();
        
        if($Process_item->form_type_id == '1'){
            $treecut = Tree_Removal_Request::find($Process_item->form_id);
            $land_parcel = Land_Parcel::find($treecut->land_parcel_id);
            $Photos=Json_decode($treecut->images);
            
            //dd($Photos);
            return view('approvalItem::assignStaff',[
                'treecut' => $treecut,
                'land_process' =>$landProcess,
                'Users' => $Users,
                'Prerequisites' => $Prerequisites,
                'Process_item' =>$Process_item,
                'Organizations' => $Organizations,
                'Photos' =>$Photos,
                'polygon' => $land_parcel->polygon,
            ]);
        }
        else if($Process_item->form_type_id == '2'){
            $devp = Development_Project::find($Process_item->form_id);
            $land_parcel = Land_Parcel::find($devp->land_parcel_id);
            $Photos=Json_decode($devp->images);
            return view('approvalItem::assignStaff',[
                'devp' => $devp,
                'Users' => $Users,
                'land_process' =>$landProcess,
                'Prerequisites' => $Prerequisites,
                'Process_item' =>$Process_item,
                'Organizations' => $Organizations,
                'polygon' => $land_parcel->polygon,
                'Photos' => $Photos,
            ]);
        }
        else if($Process_item->form_type_id == '3'){
            $envrest = Environment_Restoration::find($Process_item->form_id);
            $land_parcel = Land_Parcel::find($envrest->land_parcel_id);
            $tree_data = Environment_Restoration_Species::all()->where('environment_restoration_id',$envrest->id);
            return view('approvalItem::assignStaff',[
                'envrest' => $envrest,
                'land_process' =>$landProcess,
                'Users' => $Users,
                'Prerequisites' => $Prerequisites,
                'Process_item' =>$Process_item,
                'Organizations' => $Organizations,
                'polygon' => $land_parcel->polygon,
                'tree_data' => $tree_data,
            ]);
        }
        else if($Process_item->form_type_id == '4'){
            $crime = Crime_report::find($Process_item->form_id);
            $land_parcel = Land_Parcel::find($crime->land_parcel_id);
            $Photos=Json_decode($crime->photos);
            return view('approvalItem::assignStaff',[
                'crime' => $crime,
                'Prerequisites' => $Prerequisites,
                'land_process' =>$landProcess,
                'Users' => $Users,
                'Process_item' =>$Process_item,
                'Organizations' => $Organizations,
                'polygon' => $land_parcel->polygon,
                'Photos' => $Photos,
            ]);
        }
        else{
            $item = Land_Parcel::find($Process_item->form_id);
            $Land_Organizations =Land_Has_Organization::where('land_parcel_id',$item->id)->get();
            return view('approvalItem::assignStaff',[
                'item' => $item,
                'Prerequisites' => $Prerequisites,
                'Land_Organizations' =>$Land_Organizations,
                'Users' => $Users,
                'Process_item' =>$Process_item,
                'Organizations' => $Organizations,
                'polygon' => $item->polygon,
            ]);
        } 
    }

    public function citizen_view_progress($id)
    {
        $Process_item =Process_item::find($id);
        $progress=Process_item_progress::all()->where('process_item_id',$id);
        if($Process_item->form_type_id == '1'){
            $treecut = Tree_Removal_Request::find($Process_item->form_id);
            return view('approvalItem::treeview',[
                'treecut' => $treecut,
                'progress' => $progress,
            ]);
        }
        else if($Process_item->form_type_id == '2'){
            $devp = Development_Project::find($Process_item->form_id);
            return view('approvalItem::developview',[
                'devp' => $devp,
                'progress' => $progress,
            ]);
        }
        else if($Process_item->form_type_id == '3'){
            $envrest = Environment_Restoration::find($Process_item->form_id);
            return view('approvalItem::envrestoreAssign',[
                'envrest' => $envrest,
                'progress' => $progress,
            ]);
        }
        else if($Process_item->form_type_id == '4'){
            $crime = Crime_report::find($Process_item->form_id);
            return view('approvalItem::crimeview',[
                'crime' => $crime,
                'progress' => $progress,
            ]);
        } 
    }

    public function choose_assign_organization($id)
    {
        $process_item =Process_item::find($id);
        $Organizations=Organization::all();
        if($process_item->form_type_id == '1'){
            $item = Tree_Removal_Request::find($process_item->form_id);
            $Photos=Json_decode($item->images);
            //dd($Photos);
        }
        else if($process_item->form_type_id == '2'){
            $item = Development_Project::find($process_item->form_id);
            $Photos=Json_decode($item->images);
        }
        else if($process_item->form_type_id == '3'){
            $item = Environment_Restoration::find($process_item->form_id);
            //dd($process_item,$item);
            $Photos=null;
        }
        else if($process_item->form_type_id == '4'){
            $item = Crime_report::find($process_item->form_id);
            $Photos=Json_decode($item->photos);
        }
        
        if($process_item->form_type_id != '5'){
            $land_parcel = Land_Parcel::find($item->land_parcel_id);
            
            $landProcess=Process_item::where([
                ['prerequisite_id', '=' , $process_item->id],           
                ['prerequisite', '=', 0], 
            ])->first();
            $landProcess2=Process_item::where('prerequisite_id', '=' , $process_item->id)->first();
            //dd($landProcess,$landProcess2);
            
            return view('approvalItem::assignOrg',[
                'item' => $item,
                'process_item' =>$process_item,
                'Organizations' => $Organizations,
                'polygon' => $land_parcel->polygon,
                'Photos' => $Photos,
                'land_process' => $landProcess,
            ]);
        }
        else{
            $item = Land_Parcel::find($process_item->form_id);
            $Land_Organizations =Land_Has_Organization::where('land_parcel_id',$item->id)->get();
            return view('approvalItem::assignOrg',[
                'item' => $item,
                'process_item' =>$process_item,
                'Organizations' => $Organizations,
                'polygon' => $item->polygon,
                'LandOrganizations' =>$Land_Organizations,
            ]);
        }
        
    }

    public function investigate($id)
    {
        $process_item =Process_item::find($id);
        $Organizations=Organization::all();
        $Form_Types=Form_Type::all();
        $Prerequisites=Process_item::all()->where('prerequisite_id',$process_item->id);
        $Process_item_statuses=Process_item_status::all();
        $Process_item_progresses=Process_item_progress::all()->where('process_item_id',$id);
        $organization=Auth::user()->organization_id;
        if(Auth::user()->role_id=='3'){
            $Users = User::where([
                ['role_id', '>' , 3],           
                ['organization_id', '=', $organization], 
            ])->get();
        }
        else{
            $Users = User::where([
                ['role_id', '=' , 5],           
                ['organization_id', '=', $organization], 
            ])->get();
        } 
        if($process_item->form_type_id == '1'){ 
            $item = Tree_Removal_Request::find($process_item->form_id);
            $Photos=Json_decode($item->images);
            $tree_data = $item->tree_details;
        } 
        else if($process_item->form_type_id == '2'){
            $item = Development_Project::find($process_item->form_id);
            $Photos=null;
            $tree_data = null;
        }
        else if($process_item->form_type_id == '3'){
            $item = Environment_Restoration::find($process_item->form_id);
            $Photos=null;
            $tree_data = Environment_Restoration_Species::all()->where('environment_restoration_id',$item->id);
        }
        else if($process_item->form_type_id == '4'){
            $item = Crime_report::find($process_item->form_id);
            $Photos=Json_decode($item->photos);
            $tree_data = null;
        }
        if($process_item->form_type_id != '5'){
            //dd($process_item,$item);
            $land_parcel = Land_Parcel::find($item->land_parcel_id);
            
            $landProcess=Process_item::where([
                ['prerequisite_id', '=' , $process_item->id],           
                ['prerequisite', '=', 0], 
            ])->first();

            $related_treecuts = Tree_Removal_Request::all()->where('land_parcel_id',$item->land_parcel_id);
            $related_devps = Development_Project::all()->where('land_parcel_id',$item->land_parcel_id);
            $related_crimes = Crime_report::all()->where('land_parcel_id',$item->land_parcel_id);

            return view('approvalItem::investigate',[
                'item' => $item,
                'Organizations' => $Organizations,
                'Prerequisites' => $Prerequisites,
                'process_item' =>$process_item,
                'polygon' => $land_parcel->polygon,
                'land_process'=>$landProcess,
                'Related_Treecuts' => $related_treecuts,
                'Related_Devps' => $related_devps,
                'Related_Crimes' => $related_crimes,
                'Process_item_statuses' =>$Process_item_statuses,
                'Process_item_progresses' =>$Process_item_progresses,
                'Photos' =>$Photos,
                'tree_data' =>$tree_data,
                'Users' => $Users,
                
            ]);
        }
        else{
            $item = Land_Parcel::find($process_item->form_id);
            $Land_Organizations =Land_Has_Organization::where('land_parcel_id',$item->id)->get();
            //dd($process_item,$item);
            return view('approvalItem::investigate',[
                'item' => $item,
                'process_item' =>$process_item,
                'Organizations' => $Organizations,
                'polygon' => $item->polygon,
                'LandOrganizations' =>$Land_Organizations,
                'Users' => $Users,
                'Process_item_statuses' =>$Process_item_statuses,
                'Process_item_progresses' =>$Process_item_progresses,
                'Prerequisites' => $Prerequisites,
            ]);
        }
        

           
        
    }
    public function create_prerequisite(Request $request)
    {
        
        $request -> validate([
            'organization' => 'required|not_in:0',
            'request' => 'required',
        ]);
        $id=$request['process_id'];
        $Process_item_old =Process_item::find($id);
        
        $Process_item =new Process_item;
        $Process_item->created_by_user_id = $request['create_by'];
        $Process_item->request_organization = $request['create_organization'];
        $Process_item->activity_organization = $request['organization'];
        $Process_item->form_id = $Process_item_old['form_id'];
        $Process_item->form_type_id = $Process_item_old['form_type_id'];   
        $Process_item->status_id = "2";
        $Process_item->prerequisite= "1";
        $Process_item->prerequisite_id = $Process_item_old['id'];
        $Process_item->remark = $request['request'];
        $Process_item->save();
        return back()->with('message', 'Prerequisite logged Successfully');  
    }

    public function cancel_prerequisite($id,$userid)
    {
        $Process_item =Process_item::find($id);
        if($Process_item->created_by_user_id==$userid){
            $Process_item->update(['status_id' => 8]);
            return back()->with('message', 'Prerequisite is removed successfully');
        }
        return back()->with('message', 'Prerequisite logged by someone else');  
    }

    public function progress_update(Request $request)
    {
        
        $request -> validate([
            'status' => 'required|not_in:0',
            'request' => 'required',
        ]);
        $id=$request['process_id'];
        Process_item::where('id',$id)->update(['status_id' => 4]);
        $Process_item_progress =new Process_item_progress;
        $Process_item_progress->created_by_user_id = $request['create_by'];
        $Process_item_progress->process_item_id = $request['process_id'];
        $Process_item_progress->status_id = $request['status'];
        $Process_item_progress->remark = $request['request'];
        $Process_item_progress->save();
        $Process_item_statuses=Process_item_status::all();
        
        //dd($Process_item_progress,$Process_item_statuses);
        return back()->with('message', 'Progress updated Successfully');  
    }

    public function final_approval(Request $request)
    {
        
        $request -> validate([
            'status' => 'required|not_in:0',
            'request' => 'required',
        ]);
        $id=$request['process_id'];
        $title=Process_item_status::where('id',$request['status'])->first()->status_title;
        if($request['status']==5){
            $Incomplete_prerequisites2=Process_item::all()->where(
                'status_id','!=','5',
            )->where(
                'status_id','!=','8',
            )->where('prerequisite_id',$id);
            if($Incomplete_prerequisites2->isNotEmpty()){
                //dd($Incomplete_prerequisites2);
                return back()->with('warning', 'Prerequisites need to be approved first');  
                
            }
            else{
                
                Process_item::where('id',$id)->update(['status_id' => 5]);
                $Process_item_progress =new Process_item_progress;
                $Process_item_progress->created_by_user_id = $request['create_by'];
                $Process_item_progress->process_item_id = $request['process_id'];
                $Process_item_progress->status_id = $request['status'];
                $Process_item_progress->remark = 'Final Approval of application '.$request['request'];
                $Process_item_progress->save();
            }
        }
        else{
                Process_item::where('id',$id)->update(['status_id' => 6]);
                $Process_item_progress =new Process_item_progress;
                $Process_item_progress->created_by_user_id = $request['create_by'];
                $Process_item_progress->process_item_id = $request['process_id'];
                $Process_item_progress->status_id = $request['status'];
                $Process_item_progress->remark = 'Final Reject of application '.$request['request'];
                $Process_item_progress->save();
        }
        
        //dd($title);
        
        
        //$title="this";
        //dd($Process_item_progress,$Process_item_statuses);
        
        return back()->with('message', 'Request '.$title);  
    }

}
