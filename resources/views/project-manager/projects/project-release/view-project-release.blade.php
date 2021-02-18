@extends('layouts.app')
@section("content")
<style type="text/css">

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
            <div class="card-body p-0">
               @if(session()->has('status'))
               <p class="alert alert-success">{{session()->get('status')}}</p>
               @endif
               <div class="sticky-tabs" style="">
                  <p class="my-3 prl-15">
                     <button type="button" id="clickstatusSummary" class="btn btn-primary btn-sm ">{{ strtoupper('Status Summary')}}</button>
                     <button type="button" id="clickJobPallets" class="click-btn btn btn-primary btn-sm disable">{{ strtoupper('Detailed Pallets Info') }}</button>
                     <button type="button" id="clickJobPlans" class="click-btn btn btn-primary btn-sm disable">{{ strtoupper("Project Plans") }}</button>
                  </p>
               </div>
               <!-- {{-- view project plans --}} -->
               <div class="d-none" id="projectPlans">
                  <div class="card-header mx-0">
                     <h4 class="d-inline">
                        {{ strtoupper("Plans") }}
                     </h4>
                  </div>
                  <table class="table table-hover table-head-border sticky-head">
                     <thead class="">
                        <tr>
                           <th style="width: 10%">No</th>
                           <th style="width: 90%"> {{  __('Description') }}</th>
                        </tr>
                     </thead>
                  </table>
                  <table class="table table-hover table-head-border">
                     <tbody class="border-0">
                        @forelse ($projects_plans as $pr_data_key =>$pr_data_value)
                        <tr>
                           <td style="width: 10%">{{$pr_data_key+1}}</td>
                           <td style="width: 90%">
                              <a href="{{ route('pm.project-plans-note',['id' => $pr_data_value['id']]) }}" class="text-dark">
                              {{ $pr_data_value['project_plan_ver'] }}
                              </a>
                           </td>
                        </tr>
                        @empty
                        <!-- <td colspan="11" class="text-danger">
                           <p class="text-center m-0 p-0">No project releases exits</p>
                        </td> -->
                        @endforelse
                     </tbody>
                  </table>
               </div>
               <!-- {{-- view status summary --}}-->
               <div class="d-non" id="statusSummary">
                  <div class="card-header mx-0">
                     <h4 class="d-inline">
                        {{ strtoupper('Releases') }}
                     </h4>
                  </div>
                  <table class="table table-hover table-head-border sticky-head" style="">
                     <thead>
                        <tr>
                           <th style="width: 10%">No</th>
                           <th style="width: 15%"> {{  __('Job Number') }}</th>
                           <th style="width: 15%"> {{ __('Release Number') }}</th>
                           <th style="width: 10%"> {{ __('Released') }}</th>
                           <th style="width: 10%"> {{ __('Produced') }}</th>
                           <th style="width: 10%"> {{ __('Staged') }}</th>
                           <th style="width: 10%"> {{ __('Shipped') }}</th>
                           <th style="width: 20%"> {{ __('Released Value') }}</th>
                        </tr>
                     </thead>
                  </table>
                  <table class="table table-hover table-head-border">
                     <tbody class="text-center">
                        @forelse ($project_summary as $pr_data_key =>$pr_data_value)
                           @php $releaseNumber = $pr_data_value['ReleaseNumber']."-".$pr_data_value['Suffix']."-".$pr_data_value['JobNumber'] @endphp
                        <tr>
                           <td style="width: 10%">{{$pr_data_key+1}}</td>
                           <td style="width: 15%">{{ $pr_data_value['JobNumber'] }}</td>
                           <td style="width: 15%">
                              <a href="{{ route('pm.project-release-note',['id' => $releaseNumber]) }}" class="text-dark">
                              @if(!empty($pr_data_value['Suffix']))
                                 {{ $pr_data_value['ReleaseNumber'] }} - {{ $pr_data_value['Suffix'] }}
                              @else
                                 {{ $pr_data_value['ReleaseNumber'] }}
                              @endif</a>
                           </td>
                           <td style="width: 10%">{{ $pr_data_value['Released'] }}</td>
                           <td style="width: 10%">{{ $pr_data_value['Produced'] }}</td>
                           <td style="width: 10%">{{ $pr_data_value['Staged'] }}</td>
                           <td style="width: 10%">{{ $pr_data_value['Shipped'] }}</td>
                           <!-- <td style="width: 20%">$ {{-- $pr_data_value['ReleasedValue'] --}}</td> -->
                           <td style="width: 20%">$ {{ number_format((int)$pr_data_value['ReleasedValue'], 2) }}</td>
                        </tr>
                        @empty
                       <!--  <td colspan="11" class="text-danger">
                           <p class="text-center m-0 p-0">No project releases exits</p>
                        </td> -->
                        @endforelse
                     </tbody>
                  </table>
               </div>
               <!-- {{-- Pallets --}} -->
               <div id="palletsJobs" class="d-none">
                  <div class="card-header mx-0">
                     <h4 class="d-inline">
                        {{ strtoupper('Detailed Pallet Info') }}
                     </h4>
                  </div>
                  <table class="table table-hover table-head-border last-child-fixed sticky-head">
                     <thead>
                        <tr>
                           <th style="width: 5%">No</th>
                           <th style="width: 15%"> {{  __('Pallet Number') }}</th>
                           <th style="width: 15%"> {{ __('Release Number') }}</th>
                           <th style="width: 20%"> {{ __('Staging Date') }}</th>
                           <th style="width: 15%"> {{ __('Shipment Date') }}</th>
                           <th style="width: 30%"> {{ __('Pallet Content') }}</th>
                        </tr>
                     </thead>
                  </table>
                  <table class="table table-hover table-head-border last-child-fixed">
                     <tbody>
                        @forelse($palletsFileData as $palletsFileData_key => $palletsFileData_value)
                           @php $stageData = explode(" ",$palletsFileData_value->StagingDate);
                                $shippingDate = explode(" ",$palletsFileData_value->ShipmentDate);
                           @endphp
                           <tr>
                              <td style="width: 5%">{{ $palletsFileData_key+1 }}</td>
                              <td style="width: 15%">{{ $palletsFileData_value->PalletNumber }}</td>
                              <td style="width: 15%">{{ $palletsFileData_value->ReleaseNumber }}</td>
                              <td style="width: 20%">{{-- $palletsFileData_value->StagingDate --}}{{ $stageData[0] }}

                              </td>
                              <td style="width: 15%">{{ $shippingDate[0] }}</td>
                              <td style="width: 30%">{{ $palletsFileData_value->finalStoneData }}</td>
                           </tr>
                        @empty
                          <!--  <td class="text-danger" colspan="6">No Data found</td> -->
                        @endforelse

                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
