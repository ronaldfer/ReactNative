@extends('layouts.app')

@section("content")
<section class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h5>{{ __('Edit My Profile') }}</h5> </div>

        <div class="card-body">
          @if(session()->has('status'))
            <p class="alert alert-success">
              {{session()->get('status')}}
            </p>
          @endif
          @if(session()->has('error'))
            <p class="alert alert-danger">
              {{session()->get('error')}}
            </p>
          @endif
          <form method="POST" action="{{ route('update-my-profile') }}" enctype="multipart/form-data">
            @csrf
            @if(Auth::user()->hasRole('Admin'))
              <p class="text-center" >
              @if(empty($data->image))
              	 <img src="{{ asset('public/images/pic-not-available.png') }}" class="imgNew rounded-circle">
              @else
              	@if (File::exists(public_path("assets/admin_logo/".$data->image)))
  	              <img src="{{ asset('public/assets/admin_logo') }}/{{$data->image}}" class="imgNew rounded-circle" height="160">
  	            @else
  	              <img src="{{ asset('public/images/empty_image.jpeg') }}" class="imgNew rounded-circle" height="160">
  	            @endif
              @endif
              </p>
            @elseif(Auth::user()->hasRole('Company'))
              @if(empty($data->image))
                 <img src="{{ asset('public/images/pic-not-available.png') }}" class="imgNew rounded-circle">
              @else
                @if (File::exists(public_path("assets/admin_logo/".$data->image)))
                  <img src="{{ asset('public/assets/admin_logo') }}/{{$data->image}}" class="imgNew rounded-circle">
                @else
                  <img src="{{ asset('public/images/empty_image.jpeg') }}" class="imgNew rounded-circle">
                @endif
              @endif
            @endif
            <div class="form-group row">
              <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

              <div class="col-md-6">
                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{$data->first_name}}">
                <span class="text-danger" id="first_name_msg"></span>
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
                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{$data->last_name}}">
                <span class="text-danger" id="last_name_msg"></span>
                @error('last_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail address') }}</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$data->email}}" disabled="">

                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            @if(Auth::user()->hasRole('Admin'))
            <div class="form-group row">
              <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Profile Image') }}</label>

              <div class="col-md-6">
                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">

                @error('image')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            @endif
            <div class="form-group row">
              <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

              <div class="col-md-6">
                <textarea id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address">{{$data['address']}}</textarea>

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
                <input id="contact" type="text" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{$data['contact']}}">
                <span class="text-danger" id="contact_msg"></span>
                @error('contact')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-sm btn-primary" id="my-profile-update">
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
</section>
@endsection