@extends('layouts.app')

@section("content")
<!-- <?php //dd($member_data); ?> -->
<section class="container-fluid">
	<div class="row">
		<div class="col-md-10 mx-1 mx-auto">
			<div class="card">
				<div class="card-header"><h5 class="mt-2 float-left">{{ __('All Projects') }}</h5>
					<a href="{{ route('admin.create-projects') }}" class="btn btn-sm btn-light float-right" title="Add Projects Details"><i class="fa fa-user-plus fa-2x" aria-hidden="true"></i></a>
					<a href="{{ route('export-projects') }}" class="btn btn-sm btn-light py-2 mr-1 float-right" title="Download Projects Details"><i class="fa fa-download" aria-hidden="true"></i></a>
				</div>
				<div class="card-body table-responsive">
					@if(session()->has('success'))
			            <p class="alert alert-success">
			              {{session()->get('success')}}
			            </p>
		            @endif
		            @if(session()->has('status'))
			            <p class="alert alert-danger">
			              {{session()->get('status')}}
			            </p>
		            @endif
					<table class="table table-hover table-bordered">
						<thead class="table-dark">
							<tr>
								<th>No</th>
								<th> {{	__('Job Number') }}</th>
								<th> {{	__('Project Name') }}</th>
								<!-- <th> {{--	__('Project Manager') --}}</th> -->
								<th> {{	__('Company Name') }}</th>
								<th> {{ __('View') }}</th>
								<th> {{ __('Edit') }}</th>
								<th> {{ __('Delete') }}</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($projects_data as $project_data =>$projects_value)

								@php  $pm_name = json_decode($projects_value['pm_id']);

								 @endphp
								<tr>
									<td>{{ $projects_data->firstItem() + $project_data  }}</td>
									<td>{{ $projects_value['job_number'] }}</td>
									<td>
										<a class="" href="{{ route('admin.projectManager.projectsReleases',['id' => $projects_value['id']]) }}">{{ $projects_value['job_name'] }}</a>
									</td>
									<!-- <td>
										{{-- @foreach($pm_data as $pm_key => $pm_value) --}}
											{{-- @for($i = 0; $i < count($pm_name); $i++) --}}
												{{-- @if($pm_value->id == $pm_name[$i]) --}}
													{{-- $pm_value['first_name'] --}} {{-- $pm_value['last_name'] --}} ,
												{{-- @endif --}}
											{{-- @endfor --}}
										{{-- @endforeach --}}
									</td> -->
									<td>
										@foreach($company_data as $company_key => $company_value)
											@if($company_value['id'] == $projects_value['company_id'])
												{{ $company_value['company_name'] }}
											@endif
										@endforeach
									</td>
									<!-- <td>{{-- $projects_value['shipped']  --}}</td>
									<td class="text-center">
										@if(!empty($projects_value['AAS_complete_date']))
											{{-- $projects_value['AAS_complete_date']  --}}
										@else
											--
										@endif
										</td>
									<td>{{-- $projects_value['location']  --}}</td> -->
									<td><button type="button" class="btn btn-light project_data" data-id="{{ $projects_value['id'] }}" ><i class="fa fa-eye"></i></button></td>
									<td><a href="{{ route('admin.edit-projects',['id' => $projects_value['id']])}}" class="btn btn-light"><i class="fa fa-edit"></i></a></td>
									<td><a href="{{ route('admin.delete-projects',['id' => $projects_value['id']])}}" class="btn btn-light delete-confirm" title="Project"><i class="fa fa-trash"></i></a></td>

								</tr>
							@empty
								<td colspan="11" class="text-danger"><p class="text-center m-0 p-0"><b>No project exits</b></p></td>
							@endforelse
						</tbody>
						<tfoot>
							<tr>
								<td colspan="8">{!! $projects_data->render() !!}</td>
							</tr>
						</tfoot>
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
	    		<!-- <tr>
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
	    		</tr> -->
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
