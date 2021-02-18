
@extends('layouts.app')

@section("content")
<!-- <?php //dd($member_data); ?> -->
<section class="container-fluid">
	<!-- Material switch -->
<!-- Default switch -->

<style type="text/css">
</style>

	<div class="row">
		<div class="col-md-10 col-sm-12 mx-1 mx-auto">
			<div class="card">
				<div class="card-header"><h5 class="mt-2 float-left">{{ __('All Project Manager') }}</h5>
					<a href="{{ route('admin.create-project-manager') }}" class="btn btn-sm btn-light float-right" title="Add Project Manager"><i class="fa fa-user-plus fa-2x" aria-hidden="true"></i></a>
				</div>
				<div class="card-body table-responsive">
					@if(session()->has('status'))
			            <p class="alert alert-danger">
			              {{session()->get('status')}}
			            </p>
			        @endif
			        @if(session()->has('success'))
			            <p class="alert alert-success">
			              {{session()->get('success')}}
			            </p>
			        @endif
					<table class="table table-hover table-bordered">
						<thead class="table-dark">
							<tr>
								<th>No</th>
								<th> {{	__('Name') }}</th>
								<th> {{	__('Email Address') }}</th>
								<th> {{	__('Company Name') }}</th>
								<th> {{ __('View') }}</th>
								<th> {{ __('Edit') }}</th>
								<th> {{ __('Delete') }}</th>
								<th> {{ __('Account Status') }}</th>
							</tr>
						</thead>
						<tbody>
							@php $i = 1;$j=0; @endphp

							@forelse ($data as $pm_data =>$pm_value)
								<tr>
									<td>{{ $data->firstItem() + $pm_data  }}</td>
									<td>{{ $pm_value['first_name'] }} {{ $pm_value['last_name'] }}</td>
									<td>{{ $pm_value['email']  }}</td>
									<td>
										@foreach($company_data as $company_key => $company_value)
											{{ $pm_value['company_id'] == $company_value['id'] ? $company_value['company_name'] : ''  }}
										@endforeach
									</td>
									<td><button type="button" class="btn btn-light pm_data" data-id="{{ $pm_value['id'] }}" ><i class="fa fa-eye"></i></button></td>
									<td><a href="{{ route('admin.edit-project-manager',['id' => $pm_value['id']])}}" class="btn btn-light"><i class="fa fa-edit"></i></a></td>
									<td><a href="{{ route('admin.delete-project-manager',['id' => $pm_value['id']])}}" class="btn btn-light delete-confirm" title="Project Manager"><i class="fa fa-trash"></i></a></td>
									<td>
										<input type="checkbox" data-id="{{ $pm_value['id'] }}" name="status" class="js-switch change-status" {{ $pm_value['status'] == 1 ? 'checked' : '' }}>
									</td>
								</tr>
							@php $i++@endphp
							@empty
								<td colspan="8" class="text-danger"><p class="text-center m-0 p-0"><b>No project manager exits</b></p></td>
							@endforelse



						</tbody>
						<tfoot>
							<tr>
								<td colspan="8">{!! $data->render() !!}</td>
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
	    			<td><b>Name</b></td>
	    			<td id="pm_name"></td>
	    		</tr>
	    		<tr>
	    			<td><b>Company</b></td>
	    			<td id="pm_company_name"></td>
	    		</tr>
	    		<tr>
	    			<td><b>Email</b></td>
	    			<td id="pm_email"></td>
	    		</tr>
	    		<tr>
	    			<td><b>Contact</b></td>
	    			<td id="pm_contact"></td>
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
