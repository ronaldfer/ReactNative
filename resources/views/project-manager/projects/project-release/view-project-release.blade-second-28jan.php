@extends('layouts.app')
@section("content")
<style type="text/css">
   .table-content{
   height:300px;
   min-height: 350px;
   overflow-x:auto;
   }
</style>
<div class="container-fluid">
   <div class="row">
      <div class="col-md-10 mx-1 mx-auto">
         <div class="card">
            <div class="card-header">
               <h4 class="d-inline">
                  {{ $project_data->job_number }} - {{ $project_data->job_name }}
               </h4>
            </div>
            <div id="scroll-div"></div>
            <div class="card-body p-0" id="fixed-div">
               @if(session()->has('status'))
               <p class="alert alert-success">{{session()->get('status')}}</p>
               @endif
               <p class="my-3 prl-15">
                  <button type="button" id="clickstatusSummary" class="btn btn-primary btn-sm ">{{ strtoupper('Status Summary')}}</button>
                  <button type="button" id="clickJobPallets" class="click-btn btn btn-primary btn-sm disable">{{ strtoupper('Detailed Pallets Info') }}</button>
                  <button type="button" id="clickJobPlans" class="click-btn btn btn-primary btn-sm disable">{{ strtoupper("Project Plans") }}</button>
               </p>
               <!-- {{-- view project plans --}} -->
               <div class="d-none" id="projectPlans">
                  <div class="card-header mx-0">
                     <h4 class="d-inline">
                        {{ strtoupper("Plans") }}
                     </h4>
                  </div>
                  <div class="table-head">
                     <table class="table table-hover table-head-border">
                        <thead class="">
                           <tr>
                              <th>#</th>
                              <th> {{  __('Project Plans Name') }}</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
                  <div class="table-content">
                     <table class="table table-hover table-head-border">
                        <tbody class="border-0">
                           @forelse ($projects_plans as $pr_data_key =>$pr_data_value)
                           <tr>
                              <td>{{$pr_data_key+1}}</td>
                              <td>
                                 <a href="{{ route('pm.project-plans-note',['id' => $pr_data_value['id']]) }}" class="text-dark">
                                 {{ $pr_data_value['project_plan_ver'] }}
                                 </a>
                              </td>
                           </tr>
                           @empty
                           <td colspan="11" class="text-danger">
                              <p class="text-center m-0 p-0">No project releases exits</p>
                           </td>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- {{-- view status summary --}}-->
               <div class="d-non" id="statusSummary">
                  <div class="card-header mx-0">
                     <h4 class="d-inline">
                        {{ strtoupper('Releases') }}
                     </h4>
                  </div>
                  <div class="table-head">
                     <table class="table table-hover table-head-border">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th> {{  __('Job Number') }}</th>
                              <th> {{ __('Release Number') }}</th>
                              <th> {{ __('Released') }}</th>
                              <th> {{ __('Produced') }}</th>
                              <th> {{ __('Staged') }}</th>
                              <th> {{ __('Shipped') }}</th>
                              <th> {{ __('Released Value') }}</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
                  <div class="table-content">
                     <table class="table table-hover table-head-border">
                        <tbody class="text-center">
                           @forelse ($project_summary as $pr_data_key =>$pr_data_value)
                           <tr>
                              <td>{{$pr_data_key+1}}</td>
                              <td>{{ $pr_data_value['JobNumber'] }}</td>
                              <td>
                                 <a href="{{-- route('pm.project-release-note',['id' => $pr_data_value['id']]) --}}" class="text-dark">
                                 @if(!empty($pr_data_value['Suffix']))
                                 {{ $pr_data_value['ReleaseNumber'] }} - {{ $pr_data_value['Suffix'] }}
                                 @else
                                 {{ $pr_data_value['ReleaseNumber'] }}
                                 @endif</a>
                              </td>
                              <td>{{ $pr_data_value['Released'] }}</td>
                              <td>{{ $pr_data_value['Produced'] }}</td>
                              <td>{{ $pr_data_value['Staged'] }}</td>
                              <td>{{ $pr_data_value['Shipped'] }}</td>
                              <td>$ {{ number_format($pr_data_value['ReleasedValue'], 2) }}</td>
                           </tr>
                           @empty
                           <td colspan="11" class="text-danger">
                              <p class="text-center m-0 p-0">No project releases exits</p>
                           </td>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- {{-- Pallets --}} -->
               <div id="palletsJobs" class="d-none">
                  <div class="card-header mx-0">
                     <h4 class="d-inline">
                        {{ strtoupper('Detailed Pallet Info') }}
                     </h4>
                  </div>
                  <div class="table-head">
                     <table class="table table-hover table-head-border">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th> {{  __('Pallet Number') }}</th>
                              <th> {{ __('Release Number') }}</th>
                              <th> {{ __('Staging Date') }}</th>
                              <th> {{ __('Shipment Date') }}</th>
                              <th> {{ __('Pallet Content') }}</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
                  <div class="table-content">
                     <table class="table table-hover table-head-border">
                        <tbody>
                           @forelse($palletsFileData as $palletsFileData_key => $palletsFileData_value)
                           <tr>
                              <td>{{ $palletsFileData_key+1 }}</td>
                              <td>{{ $palletsFileData_value->PalletNumber }}</td>
                              <td>{{ $palletsFileData_value->ReleaseNumber }}</td>
                              <td>{{ $palletsFileData_value->StagingDate }}</td>
                              <td>{{ $palletsFileData_value->ShipmentDate }}</td>
                              <td>{{ $palletsFileData_value->finalStoneData }}</td>
                           </tr>
                           @empty
                           <td class="text-danger" colspan="6">No Data found</td>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript">
  $(function() {
    moveScroller();
  });
</script>
@endsection