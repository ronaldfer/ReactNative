@extends('layouts.app')

@section("content")
<section class="container-fluid">
	<div class="row">
		<div class="col-md-10 mx-1 mx-auto">
			<div class="card">
				<div class="card-header">
				    <div class="row">
				    	<div class="col-md-10">
							<h5 class="mb-0 mt-2">{{ $project_data->job_name }} : {{ $projectsPlansData->project_plan_ver }}</h5>
				    	</div>
				    	<div class="col-md-2">
				    		<!-- <p class="mb-0 float-right"><a href="{{-- url('download-file') --}}" class="btn btn-success">Download</a></p> -->
				    	</div>
				    </div>
			    </div>
				<div class="card-body row">
					<div class="col-md-6">
							<!-- <img src="{{-- asset('public/images/pillar.jpg') --}}" class="img-fluid w-100"> -->
							<a href="{{  route('pm.download-project-release-pdf',[
							'id'=>$projectsPlansData->one_drive_projects_plan_id]) }}{{-- $projectsPlansData->release_url_link --}}">{{ $projectsPlansData->project_plan_ver }}</a>
					</div>

					<!-- <div class="col-md-6">
						<div class="mt-4 mb-4 pl-5 pr-5">
						{{-- @forelse($notes_data as $notes_data_key => $notes_data_value) --}}
							<p class="d-inline">{{-- $notes_data_value['projects_release_notes'] --}}</p>
							{{-- @php --}}
								$created_data = $notes_data_value['created_at'];
								$splitTimeStamp = explode(" ",$created_data);
								$date = date("d-m-Y", strtotime($splitTimeStamp[0]));
							{{-- @endphp --}}
							<span class="float-right">{{-- $date --}}</span><br>
						{{-- @empty --}}
							<br>
							<p class="text-danger">No Notes are available.</p>
						{{-- @endforelse --}}
						</div>
						<form method="post">
							<div class="form-group">
								<input type="hidden" id="projects_release_id" value="{{-- $projectsPlansData['id'] --}}">
								<input type="hidden" id="projects_release_pm_id" value="{{-- $projectsPlansData['pm_id'] --}}">
								<input type="hidden" id="projects_release_company_id" value="{{-- $projectsPlansData['company_id'] --}}">
								<input type="hidden" id="projects_release_projects_id" value="{{-- $projectsPlansData['projects_id'] --}}">
								<textarea rows="5" placeholder="Add Notes" id="notes-data" class="form-control"></textarea>
								<span class="text-danger" id="notes_msg"></span>
							</div>
							<button type="button" id="add-notes" class="btn btn-success btn-sm">Add notes</button>
						</form>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</section>
@endsection