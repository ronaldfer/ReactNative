@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h5>{{ __('Edit Customer Details') }}</h5> </div>

        <div class="card-body">
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
          <form method="POST" action="{{ route('admin.update-company') }}" enctype="multipart/form-data" id="update-company-form">
            @csrf
            <input type="hidden" name="company_id" value="{{$company_data->id}}">
            <p class="text-center">
              @if(!$company_data->image == '')
                @if (File::exists(public_path("assets/company_logo/".$company_data->image)))
                  <img src="{{ asset('public/assets/company_logo') }}/{{$company_data->image}}" class="imgNew rounded-circle" height="127">
                @else
                  <img src="{{ asset('public/images/empty_image.jpeg') }}" class="imgNew rounded-circle" height="127">
                @endif
              @else
                  <img src="{{ asset('public/images/empty_image.jpeg') }}" class="imgNew rounded-circle" height="127">
              @endif
            </p>
            <div class="form-group row">
              <label for="company_name" class="col-md-4 col-form-label text-md-right">{{ __('Company Name') }}</label>

              <div class="col-md-6">
                <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" readonly="" value="{{$company_data->company_name}}">
                <span class="text-danger" id="company-name-msg"></span>
                @error('company_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            
            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Contact Name') }}</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$company_data->owner_name}}">

                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email Address') }}</label>

              <div class="col-md-6">
                @if($company_data->email == '')
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$company_data->email}}">
                @else
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$company_data->email}}">
                @endif
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="company_logo" class="col-md-4 col-form-label text-md-right">{{ __('Company Logo') }}</label>

              <div class="col-md-6">
                <input id="company_logo" type="file" class="form-control @error('company_logo') is-invalid @enderror" name="company_logo">

                @error('company_logo')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

              <div class="col-md-6">
                <textarea id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address">{{$company_data['address']}}</textarea>
                
                @error('address')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="contact" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

              <div class="col-md-6">
                <input id="contact" type="text" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{$company_data['contact'] == '' ?'' : $company_data['contact'] }}">
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
                <button type="submit" class="btn btn-sm btn-primary" id="update-customer-details">
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
