@extends('layouts.app')

@section("content")
<!-- <?php //dd($member_data); ?> -->
<section class="container-fluid">
	<div class="row">
		<div class="col-md-10 mx-1 mx-auto">
			<div class="card">
				<div class="card-header"><h5 class="mt-2 float-left">{{ __('All Customers') }}</h5>
					<a href="{{ route('admin.create-company') }}" class="btn btn-sm btn-light float-right" title="Add Customer"><i class="fa fa-user-plus fa-2x" aria-hidden="true"></i></a>
				</div>
				<div class="card-body table-responsive">
					<table class="table table-hover table-bordered">
						<thead class="table-dark">
							<tr>
								<th>No</th>
								<th> {{	__('Customer Name') }}</th>
								<th> {{ __('View') }}</th>
								<th> {{ __('Edit') }}</th>
								<th> {{ __('Delete') }}</th>
							</tr>
						</thead>
						<tbody>
							@if(session()->has('success'))
					            <div class="alert alert-success alert-block">
					                <button type="button" class="close" data-dismiss="alert">×</button>
					                  <strong>{{ Session::get('success') }}</strong>
					            </div>
					        @endif
					        @if(session()->has('status'))
					            <div class="alert alert-success alert-block">
					                <button type="button" class="close" data-dismiss="alert">×</button>
					                  <strong>{{ Session::get('status') }}</strong>
					            </div>
				    	    @endif
							@php $i = 1;$j=0;  @endphp

							@forelse ($data as $pm_data =>$company_value)
								<tr>
									<td>{{ $data->firstItem() + $pm_data  }}</td>
									<!-- <td>{{-- $company_value['first_name'] --}}</td>
									<td>{{-- $company_value['email']  --}}</td> -->
									<td>{{ $company_value['company_name']  }}</td>
									<td><button type="button" class="btn btn-light company_data" data-id="{{ $company_value['id'] }}" ><i class="fa fa-eye"></i></button></td>
									<td><a href="{{ route('admin.edit-company',['id' => $company_value['id']])}}" class="btn btn-light"><i class="fa fa-edit"></i></a></td>
									<td><a href="{{ route('admin.delete-company',['id' => $company_value['id']])}}" class="btn btn-danger delete-company" title="Customer"><i class="fa fa-trash-o"></i></a></td>

								</tr>
							@php $i++@endphp
							@empty
								<td colspan="7" class="text-danger"><p class="text-center m-0 p-0"><b>No Customer exists.</b></p></td>
							@endforelse
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5">
									{!! $data->render() !!}
								</td>
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
	    <div class="modal-body">
		    	<div class=" table-responsive">
			    	<table class="table text-center table-bordered">
			    		<p class="text-center">
						<img src="" id="company_image" class="imgNew rounded-circle" alt="Customer logo" height="124">
			    		</p>
			    		<tr>
			    			<td><b>Customer Name</b></td>
			    			<td id="company_name"></td>
			    		</tr>
			    		<tr>
			    			<td><b>Contact Name</b></td>
			    			<td id="owner_name"></td>
			    		</tr>
			    		<tr>
			    			<td><b>Email</b></td>
			    			<td id="company_email"></td>
			    		</tr>
			    		<tr>
			    			<td><b>Address</b></td>
			    			<td id="company_address"></td>
			    		</tr>
			    		<tr>
			    			<td><b>Contact</b></td>
			    			<td id="company_contact"></td>
			    		</tr>
			    	</table>
		    	</div>
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
 <!-- data-backdrop="static" data-keyboard="false" data-target="#myModal" -->