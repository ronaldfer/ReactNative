@extends('layouts.app')

@section("content")
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h5>{{ __('Edit Projects Details') }}</h5> </div>

        <div class="card-body">
          @if(session()->has('status'))
            <p class="alert alert-success">
              {{session()->get('status')}}
            </p>
          @endif
          <form method="POST" action="{{ route('admin.update-projects') }}" id="update-projects-form">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project_data->id }}">

            <div class="form-group row">
              <label for="job-number" class="col-md-4 col-form-label text-md-right">{{ __('Job Number') }}</label>

              <div class="col-md-6">
                <input id="job-number" type="text" class="form-control @error('job_number') is-invalid @enderror" name="job_number" value="{{ $project_data->job_number }}">
                <span class="text-danger" id="job-number-msg"></span>
                @error('job_number')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row ">
              <label for="project_name" class="col-md-4 col-form-label text-md-right">{{ __('Project Name') }}</label>

              <div class="col-md-6">
                <input id="project_name" type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" value="{{ $project_data->job_name }}">
                <span class="text-danger" id="project-name-msg"></span>
                @error('project_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_pcs" class="col-md-4 col-form-label text-md-right">{{ __('Company Name') }}</label>

              <div class="col-md-6">
                @php
                    $getCompanyData = (new \App\Helpers\CompanyHelper)->getCompany();
                    $data = json_decode($getCompanyData);
                @endphp
                <select id="edit-company-name" type="text" class="form-control othersCompany @error('company_name') is-invalid @enderror" name="company_name" autocomplete="company_name" >
                    <option value="">Select Company</option>
                @forelse($data as $key_data =>$value_data)
                    <option value="{{$value_data->id}}" {{ $value_data->id == $project_data->company_id ?'selected="selected"':'' }}>{{$value_data->company_name}}</option>
                @empty
                    <option>No company here..</option>
                @endforelse
                </select>
                <span class="text-danger" id="othersCompany-msg"></span>
                @error('company_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_manager" class="col-md-4 col-form-label text-md-right">{{ __('Project Manager') }}</label>

              <div class="col-md-6">
                @php
                    $getPmData = (new \App\Helpers\ProjectManagerHelper)->getProjectManager();
                    $data = json_decode($getPmData);

                    $pm_data = json_decode($project_data->pm_id);
                    $count_pm_data = count($pm_data);
                @endphp
                <select id="pm_name" type="text" class="form-control @error('pm_name') is-invalid @enderror js-example-basic-multiple" multiple="" name="pm_name[]" multiple="" autocomplete="pm`_name" autofocus >
                    <option value="">Select Project Manager</option>
                @forelse($data as $key_data =>$value_data)
                  @if(array_intersect($pm_data,(array)$value_data->id))
                    <option value="{{$value_data->id}}" selected="">{{$value_data->first_name}} {{$value_data->last_name}}</option>
                  @else
                    <option value="{{$value_data->id}}">{{$value_data->first_name}} {{$value_data->last_name}}</option>
                  @endif
                @empty
                    <option>No project manager here..</option>
                @endforelse
                </select>
                <span class="text-danger" id="pm-name-msg"></span>
                @error('pm_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="state" class="col-md-4 col-form-label text-md-right">{{ __('State') }}</label>

              <div class="col-md-6">

                <input id="project_state" type="text" class="form-control @error('project_state') is-invalid @enderror" name="project_state" value="{{ $project_data->state }}">
                <span class="text-danger" id="project-state-msg"></span>
                @error('project_state')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_city" class="col-md-4 col-form-label text-md-right">{{ __('City') }}</label>

              <div class="col-md-6">

                <input id="project_city" type="text" class="form-control @error('project_city') is-invalid @enderror" name="project_city" value="{{ $project_data->city }}">
                <span class="text-danger" id="project-city-msg"></span>
                @error('project_city')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <!-- <div class="form-group row">
              <label for="project_pcs" class="col-md-4 col-form-label text-md-right">{{ __('Projects Pcs') }}</label>

              <div class="col-md-6">
                <input id="project_pcs" type="text" class="form-control @error('project_pcs') is-invalid @enderror" name="project_pcs" value="{{ $project_data->total_pics }}">

                @error('project_pcs')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="made" class="col-md-4 col-form-label text-md-right">{{ __('Project Made') }}</label>

              <div class="col-md-6">

                <input id="project_made" type="text" class="form-control @error('project_made') is-invalid @enderror" name="project_made" value="{{ $project_data->made }}">

                @error('project_made')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_stage" class="col-md-4 col-form-label text-md-right">{{ __('Project Staged') }}</label>

              <div class="col-md-6">
                <input id="project_stage" type="text" class="form-control @error('project_stage') is-invalid @enderror" name="project_stage" value="{{ $project_data->staged }}">

                @error('project_stage')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_shipped" class="col-md-4 col-form-label text-md-right">{{ __('Project Shipped') }}</label>

              <div class="col-md-6">
                <input id="project_shipped" type="text" class="form-control @error('project_shipped') is-invalid @enderror" name="project_shipped" value="{{ $project_data->shipped }}">

                @error('project_shipped')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_complete_date" class="col-md-4 col-form-label text-md-right">{{ __('Project AAS Complete Date') }}</label>

              <div class="col-md-6">
                <input id="project_complete_date" type="text" class="form-control @error('project_complete_date') is-invalid @enderror" name="project_complete_date" value="{{ $project_data->AAS_complete_date	}}">

                @error('project_complete_date')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_location" class="col-md-4 col-form-label text-md-right">{{ __('Project Location') }}</label>

              <div class="col-md-6">
                <input id="project_location" type="text" class="form-control @error('project_location') is-invalid @enderror" name="project_location" value="{{ $project_data->location }}">

                @error('project_location')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
 -->
            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-sm btn-primary" id="admin-update-projects">
                  {{ __('Update') }}
                </button>
                  <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection