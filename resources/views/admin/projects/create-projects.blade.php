@extends('layouts.app')

@section("content")
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h5>{{ __('Add Projects') }}</h5> </div>

        <div class="card-body">
          @if(session()->has('status'))
            <p class="alert alert-success">
              {{session()->get('status')}}
            </p>
          @endif
          <form method="POST" action="{{ route('admin.save-projects') }}" id="create-projects-form">
            @csrf
            <div class="form-group row">
              <label for="job_number" class="col-md-4 col-form-label text-md-right">{{ __('Job Number') }}</label>

              <div class="col-md-6">
                <input id="job-number" type="text" class="form-control @error('job_number') is-invalid @enderror" name="job_number" value="{{ old('job_number') }}">
                <span class="text-danger" id="job-number-msg"></span>
                @error('job_number')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project_name" class="col-md-4 col-form-label text-md-right">{{ __('Project Name') }}</label>

              <div class="col-md-6">
                <input id="project-name" type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" value="{{ old('project_name') }}">
                <span class="text-danger" id="project-name-msg"></span>
                @error('project_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="company_name" class="col-md-4 col-form-label text-md-right">{{ __('Company Name') }}</label>

              <div class="col-md-6">
                @php
                    $getCompanyData = (new \App\Helpers\CompanyHelper)->getCompany();
                    $data = json_decode($getCompanyData);
                @endphp
                <select id="company_name-1" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" autocomplete="company_name" autofocus >
                    <option value="">Select Company</option>
                @forelse($data as $key_data =>$value_data)
                    <option value="{{$value_data->id}}">{{$value_data->company_name}}</option>
                @empty
                    <option>No company here..</option>
                @endforelse
                    <!-- <option value="0">Other</option> -->
                </select>
                <span class="text-danger" id="company_name_msg"></span>
                @error('company_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="project-manager" class="col-md-4 col-form-label text-md-right">{{ __('Project Manager') }}</label>

              <div class="col-md-6">
                <!-- @php
                    $getPmData = (new \App\Helpers\ProjectManagerHelper)->getProjectManager();
                    $data = json_decode($getPmData);
                @endphp -->
                <select id="pm_name" type="text" class="form-control @error('pm_name') is-invalid @enderror js-example-basic-multiple" name="pm_name[]" multiple="" autocomplete="pm`_name" autofocus >
                    <option value="">Select Project Manager</option>
               <!--  @forelse($data as $key_data =>$value_data)
                    <option value="{{--$value_data->id--}}">{{--$value_data->first_name--}} {{--$value_data->last_name--}}</option>
                @empty
                    <option>No company here..</option>
                @endforelse -->
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

                <input id="project_state" type="text" class="form-control @error('project_state') is-invalid @enderror" name="project_state" value="{{ old('project_state') }}">
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
                <input id="project_city" type="text" class="form-control @error('project_city') is-invalid @enderror" name="project_city" value="{{ old('project_city') }}">
                <span class="text-danger" id="project-city-msg"></span>
                @error('project_city')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-sm btn-primary" id="create-projects">
                  {{ __('Create') }}
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