@extends('layouts.app')

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 mx-1 mx-auto">
      <div class="card">
      	<div class="card-header">
      		@empty($project_data)
      			<p class=""> No release available</p>
		    @else
			    <h4 class="d-inline">{{$project_data->job_name}}</h4>
	  		@endempty
		  	</div>

        <div class="card-body">
        	@if(session()->has('status'))
            <p class="alert alert-success">
              {{session()->get('status')}}
            </p>
          @endif
          <table class="table table-hover table-bordered">
						<thead class="table-dark">
							<tr>
								<th>No</th>
								<th> {{	__('Project Release Name') }}</th>
								<th> {{ __('Modified') }} (dd/mm/yyyy)</th>
								<!-- <th> {{ __('View') }}</th> -->
								<!-- <th> {{ __('Delete') }}</th> -->
							</tr>
						</thead>
						<tbody>
							@php $i = 1;$j=0;  @endphp
							@forelse ($pr_data as $pr_data_data =>$pr_data_value)
								<tr>
									<td>{{$i}}</td>
									<td>
										<!-- <iframe src="https://onedrive.live.com/embed?resid=196797B1CBE6FB11%211655&authkey=!AN3-LW2FXZJE05g&em=2" width="476" height="288" frameborder="0" scrolling="no"></iframe> -->
										<a href="{{ route('admin.project-manager.projects-releases.project-release-note',['id' => $pr_data_value['id']]) }}" class="btn-link1">{{ $pr_data_value['project_release_ver'] }}</a>
										<a href="{{-- $pr_data_value['release_url_link'] --}}" class="btn-link" style="color: #343A40;">{{-- $pr_data_value['project_release_ver'] --}}</a>

									</td>
					                @php
				                    	$temp = explode(' ',$pr_data_value['created_at']);
				                    	$newDate = date("d-m-Y", strtotime($temp[0]));
					                @endphp
						            <td>{{ $newDate }}</td>
									<!-- <td><button type="button" id="project_release_data" class="btn btn-light" data-id="{{-- $pr_data_value['id'] --}}" ><i class="fa fa-eye"></i></button></td> -->
									<!-- <td><a href="{{-- route('admin.edit-projects',['id' => $pr_data_value['id']]) --}}" class="btn btn-light"><i class="fa fa-edit"></i></a></td> -->
									<!-- <td><a href="{{-- route('admin.delete-projects',['id' => $pr_data_value['id']]) --}}" class="btn btn-light delete-confirm"><i class="fa fa-trash"></i></a></td> -->

								</tr>
							@php $i++; @endphp
							@empty
								<td colspan="11" class="text-danger"><p class="text-center m-0 p-0"><b>No project releases exists.</b></p></td>
							@endforelse
						</tbody>
					</table>
        </div>
      </div>
  	</div>
	</div>
</div>
@endsection