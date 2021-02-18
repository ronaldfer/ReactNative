@extends('layouts.app')
@section('content')
@if (session('status'))
<div class="alert alert-success" role="alert">
   {{ session('status') }}
</div>
@endif
@if(Auth::user()->hasRole('Admin'))
<div class="container-fluid">
   <div class="row justify-content-center mt-5 align-items-center">
      <div class="col-md-8 my-auto">
         <div class="card">

         <div class="card-header">{{ __('Dashboard') }}</div>
         <div class="card-body">
            <div class="row">
               <!-- <div class="col-md-4">
                  <div class="card">
                     <div class="card-body text-center">
                        <p><i class="fa fa-users fa-2x text-info" aria-hidden="true"></i></p>
                        <h6>Total Staff</h6>
                        <p><b>{{-- $total_staff --}}</b></p>
                     </div>
                  </div>
                  </div> -->
               <div class="col-md-4">
                  <div class="card">
                     <div class="card-body text-center">
                        <p><i class="fa fa-handshake-o fa-2x text-info" aria-hidden="true"></i></p>
                        <a href="{{ route('admin.all-project-manager') }}" style="color: #596377">
                           <h6>Project Managers</h6>
                        </a>
                        <p><b>{{$total_pm}}</b></p>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="card">
                     <div class="card-body text-center">
                        <p><i class="fa fa-building-o fa-2x text-info" aria-hidden="true"></i></p>
                        <a href="{{ route('admin.all-company') }}" style="color: #596377" class="link">
                           <h6>Companies</h6>
                        </a>
                        <p><b>{{$total_company}}</b></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
   @elseif(Auth::user()->hasRole('ProjectManager'))
      <div class="container-fluid">
         <div class="row">
         <!-- <div class="col-md-4">
            <div class="card">
               <div class="card-body text-center">
                   <a href="{{-- route('pm.view-projects') --}}" style="color: #596377" class="link"><h6>Total Projects</h6></a>
                  <p><b>{{-- $total_projects --}}</b></p>
               </div>
            </div>
            </div> --><!--
            <section class="container-fluid">
               <div class="row">-->
         <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                  <h5 class="mt-2 float-left">{{ __(strtoupper('All Projects')) }}</h5>
               </div>
               <div class="card-body table-responsive">
                  @if(session()->has('status'))
                  <p class="alert alert-success">
                     {{session()->get('status')}}
                  </p>
                  @endif
                  <table class="table table-hover">
                     <thead class="">
                        <tr>
                           <th>#</th>
                           <th> {{  __('Project Name') }}</th>
                           <th> {{  __('Project Manager') }}</th>
                           <th> {{  __('Company Name') }}</th>
                        </tr>
                     </thead>
                     <tbody class="border-0">
                        @forelse ($projects_list as $projects_data =>$projects_value)
                        <tr>
                           <td>{{$projects_data+1}}</td>
                           <td>
                              <a class="" href="{{ route('pm.view-project-release',['id' => $projects_value['id']]) }}">{{ $projects_value['job_name'] }}</a>
                           </td>
                           <td>
                              <?php echo Auth::user()->first_name. " " . Auth::user()->last_name; ?>
                           </td>
                           <td>
                              @foreach($company_list as $company_key => $company_value)
                              @if($company_value['id'] == $projects_value['company_id'])
                              {{ $company_value['company_name'] }}
                              @endif
                              @endforeach
                           </td>
                        </tr>
                        @empty
                        <td colspan="11" class="text-danger">
                           <p class="text-center m-0 p-0"><b>No project exists.</b></p>
                        </td>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <!--</div>
            </section> -->
         <!-- Modal -->
         <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header">
                     <h4 class="modal-title">View Details</h4>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body table-responsive">
                     <table class="table text-center table-bordered">
                        <tr>
                           <td><b>Project Name</b></td>
                           <td id="project_name"></td>
                        </tr>
                        <tr>
                           <td><b>Customer Name</b></td>
                           <td id="company_name"></td>
                        </tr>
                        <tr>
                           <td><b>Project Manager</b></td>
                           <td id="pm_name"></td>
                        </tr>
                        <tr>
                           <td><b>State</b></td>
                           <td id="project_state"></td>
                        </tr>
                        <tr>
                           <td><b>City</b></td>
                           <td id="project_city"></td>
                        </tr>
                        <tr>
                           <td><b>Project Pcs</b></td>
                           <td id="project_pcs"></td>
                        </tr>
                        <tr>
                           <td><b>Project Made</b></td>
                           <td id="project_made"></td>
                        </tr>
                        <tr>
                           <td><b>Project Stage</b></td>
                           <td id="project_staged"></td>
                        </tr>
                        <tr>
                           <td><b>Shipped</b></td>
                           <td id="project_shipped"></td>
                        </tr>
                        <tr>
                           <td><b>Project Status</b></td>
                           <td id="project_AAS_complete_date"></td>
                        </tr>
                        <tr>
                           <td><b>Location</b></td>
                           <td id="project_location"></td>
                        </tr>
                     </table>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
               </div>
            </div>
         </div>
         <script type="text/javascript"></script>
      </div>
      </div>
   @endif
</div>
@endsection