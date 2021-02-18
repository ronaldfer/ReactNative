@extends('layouts.app')

@section("content")
<section class="container-fluid">
	<div class="row">
		<div class="col-md-10 mx-1 mx-auto">
			<div class="card">
				<div class="card-header sticky-tabs">
				    <div class="row">
				    	<div class="col-md-12">
				    		<div class="header-with-back">
				    			<h5 class="mb-0">{{ $project_data[0]['job_name'] }} : {{ $release_number }}</h5>
			    				<a href="{{ url()->previous() }}" class="back-btn"><i class="fa fa-chevron-left"></i> Back</a>
				    		</div>
				    	</div>
				    	<!-- <div class="col-md-2">
				    		<p class="mb-0 float-right"><a href="{{-- url('download-file') --}}" class="btn btn-success">Download</a></p>
				    	</div> -->
				    </div>
			    </div>
    		<table class="table table-hover table-head-border sticky-head">
               	<thead class="">
                  <tr>
                     <th style="width: 8%">No</th>
                     <th style="width: 12%"> {{  __('Job Number') }}</th>
                     <th style="width: 12%"> {{  __('Release Number') }}</th>
                     <th style="width: 12%"> {{  __('Released') }}</th>
                     <th style="width: 12%"> {{  __('Produced') }}</th>
                     <th style="width: 12%"> {{  __('Staged') }}</th>
                     <th style="width: 12%"> {{  __('Shipped') }}</th>
                     <th style="width: 12%"> {{  __('Released Value') }}</th>
                  </tr>
               </thead>
            </table>
            <table class="table table-hover table-head-border">
               <tbody class="border-0">
                  @forelse ($projects_summary as $pr_data_key =>$pr_data_value)
                  <tr>
                     <td style="width: 8%">{{$pr_data_key+1}}</td>
							<td style="width: 12%"> {{ $pr_data_value->JobNumber }}</td>
							<td style="width: 12%"> {{ $pr_data_value->ReleaseNumber }} - {{ $pr_data_value->Suffix }} </td>
							<td style="width: 12%"> {{ $pr_data_value->Released }}</td>
							<td style="width: 12%"> {{ $pr_data_value->Produced }}</td>
							<td style="width: 12%"> {{ $pr_data_value->Staged }}</td>
							<td style="width: 12%"> {{ $pr_data_value->Shipped }}</td>
							<td style="width: 12%"> {{ $pr_data_value->ReleasedValue }}</td>
                  </tr>
                  @empty
                  <!-- <td colspan="11" class="text-danger">
                     <p class="text-center m-0 p-0">No project releases exits</p>
                  </td> -->
                  @endforelse
               </tbody>
            </table>
            	<div class="card-body row">
					<div class="col-md-6">

							<table class="table table-hover text-left">
								@forelse($project_release_data as $project_release_data_key => $project_release_data_value)
									<tr>
										<td>
											<a href="{{ route('pm.download-project-release-pdf',['id' => $project_release_data_value->one_drive_projects_release_id ]) }}" target="_blank">{{ $project_release_data_value->project_release_ver }}</a>
										</td>
									</tr>
								@empty
									<!-- <p class="text-danger">No file found</p> -->
								@endforelse
							</table>

					</div>
					<div class="col-md-6">
						<div class="mt-4 mb-4 pl-5 pr-5">
						@forelse($notes_data as $notes_data_key => $notes_data_value)
							<p class="d-inline">{{ $notes_data_value['projects_release_notes'] }}</p>
							@php
								$created_data = $notes_data_value['created_at'];
								$splitTimeStamp = explode(" ",$created_data);
								$date = date("d-m-Y", strtotime($splitTimeStamp[0]));
							@endphp
							<span class="float-right">{{ $date }}</span><br>
						@empty
							<!-- <br>
							<p class="text-danger">No Notes are available.</p> -->
						@endforelse
						</div>
						<form method="post">
						<div class="form-group">
							<input type="hidden" id="projects_release_id" value="{{ $id }}">
							<input type="hidden" id="projects_release_pm_id" value="{{-- $project_release_data['pm_id'] --}}">
							<input type="hidden" id="projects_release_company_id" value="{{-- $project_release_data['company_id'] --}}">
							<input type="hidden" id="projects_release_projects_id" value="{{-- $project_release_data['projects_id'] --}}">
							<textarea rows="5" placeholder="Add Notes" id="notes-data" class="form-control"></textarea>
							<span class="text-danger" id="notes_msg"></span>
						</div>
						<button type="button" id="add-notes" class="btn btn-success btn-sm">Add notes</button>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection