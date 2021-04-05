@extends('home')

@section('cont')

<kbd><a href="/user/index" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<div class="container">
    <!-- If user status is 0 -> Not activated then prevent access to the edit view -->
    @if($user->status == 0)
    <div class="container p-3 my-3 bg-primary text-white">
        <h2>This user is not activated. Please activate the user prior to editing details.</h2>
    </div>
    @else
    <h2 style="text-align:center;" class="text-dark">Edit Privileges of {{$user->name}}</h2>
    <hr>
    <div class="row justify-content-md-center border p-4 bg-white">
        <div class="col-6 ml-3">
            <form method="post" action="/admin/savePrivilege/{{$user->id}}">
                @csrf
                @method('patch')

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">ID</span>
                    </div>
                    <input type="text" class="form-control" name="id" value="{{$user->id}}" readonly>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Name</span>
                    </div>
                    <input type="text" class="form-control" name="name" value="{{$user->name}}" readonly>
                </div>



                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Role</span>
                    </div>
                    <select name="role" class="custom-select">
                        @if($user->role == NULL)
                        <option selected value="NULL">Select Role</option>
                        @else
                        <option selected value="{{$user->role_id}}">{{$user->role->title}}</option>
                        @endif
                        @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="float:right;">
                    <button type="submit" class="btn btn-warning">Submit</button>
                </div>
            </form>
        </div>
        @endif
    </div>
    <div class="row justify-content-md-center border p-4 bg-white">
        <div class="col-6 ml-3">
            <label class="mt-2"> Additional Access privileges currently Allowed: </label>
                    <hr>
                    <table>
                    @foreach($Useraccesses as $useraccess)
                        <tr>
                            <td>{{$useraccess->access->access}}</td>
                            <td><a href="/admin/removeUserAccess/{{$useraccess->id}}" class="btn btn-outline-warning" role="button">Remove</a></td>
                        </td>
                    @endforeach
                    </table> 
            <form method="post" action="/admin/userPriviledge/{{$user->id}}">
                @csrf
                <div class="form-check border-secondary rounded-lg mb-4" style="background-color:#ebeef0">
                    <label class="mt-2"> Add new module access: </label>
                    <hr>
                    <fieldset>
                    @foreach($accesses as $access)
                        <input type="checkbox" name="modules[]" value="{{$access->id}}" checked><label class="ml-2">{{$access->access}}</label> <br>
                    @endforeach
                    </fieldset>
                </div> 
                <div style="float:right;">
                    <button type="submit" class="btn btn-warning">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection