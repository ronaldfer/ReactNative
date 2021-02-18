@extends('layouts.app')

@section("content")
<!-- <?php //dd($member_data); ?> -->
<section class="container-fluid">
	<div class="row">
		<div class="col-md-10 mx-1 mx-auto">
			<div class="card">
				<div class="card-header"><h5 class="mt-2 float-left">{{ __('All Projects') }}</h5></div>
				<div class="card-body table-responsive">
					@if(session()->has('status'))
			            <p class="alert alert-success">
			              {{session()->get('status')}}
			            </p>
		            @endif
					<table class="table table-hover table-head-border">
						<thead class="">
							<tr>
								<th>No</th>
								<th> {{	__('Project Name') }}</th>
								<!-- <th> {{--	__('Projects pcs ')-- }}</th>
								<th> {{--	__('Made') --}}</th>
								<th> {{--	__('Staged') --}}</th> -->
								<th> {{	__('Project Manager') }}</th>
								<th> {{	__('Company Name') }}</th><!--
								<th> {{	__('Shipped') }}</th>
								<th> {{	__('AAS-complete-date') }}</th>
								<th> {{	__('Location') }}</th> -->
								<!-- <th> {{ __('View') }}</th>
								<th> {{ __('Edit') }}</th>
								<th> {{ __('Delete') }}</th> -->
							</tr>
						</thead>
						<tbody>
							@php $i = 1;$j=0;  @endphp

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
									<!-- <td><button type="button" class="btn btn-light project_data" data-id="{{ $projects_value['id'] }}" ><i class="fa fa-eye"></i></button></td>
									<td><a href="{{ route('admin.edit-projects',['id' => $projects_value['id']])}}" class="btn btn-light"><i class="fa fa-edit"></i></a></td>
									<td><a href="{{ route('admin.delete-projects',['id' => $projects_value['id']])}}" class="btn btn-light delete-confirm"><i class="fa fa-trash"></i></a></td> -->

								</tr>
							@php $i++@endphp
							@empty
								<td colspan="11" class="text-danger"><p class="text-center m-0 p-0"><b>No project exists.</b></p></td>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
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
<script type="text/javascript">

</script>
@endsection
