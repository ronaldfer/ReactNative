@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h5>{{ __('Edit Project Manager Detail') }}</h5> </div>

        <div class="card-body">
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
          <form method="POST" action="{{ route('admin.update-project-manager') }}" id="update-project-manager">
            @csrf
            <input type="hidden" name="pm_id" value="{{$pm_data->id}}">
            <div class="form-group row">
                <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                <div class="col-md-6">
                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $pm_data->first_name }}"  autocomplete="first_name">
                    <span class="text-danger" id="first-name-msg"></span>
                    @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                <div class="col-md-6">
                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $pm_data->last_name }}"  autocomplete="last_name">
                    <span class="text-danger" id="last-name-msg"></span>
                    @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
              <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company') }}</label>

              <div class="col-md-6">
                <select disabled="" id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company">
                  <option value="">Select Company</option>
                  @forelse($data as $k_data => $v_data)
                    <option value="{{ $v_data['id'] }}" {{$v_data["id"] == $pm_data->company_id  ? 'selected = "selected"' : ''}}>{{ $v_data['company_name'] }}</option>
                  @empty
                    <option>No Company exits</option>
                  @endforelse
                </select>
                <span class="text-danger" id="company-msg"></span>
                @error('company')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email Address') }}</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$pm_data->email}}" disabled="">
                <span class="text-danger" id="email-msg"></span>
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="contact" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

              <div class="col-md-6">
                <input id="contact" type="text" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{$pm_data['contact']}}">
                <span class="text-danger" id="phone-msg"></span>
                @error('contact')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-sm btn-primary" id="admin-update-pm">
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
