@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h5>{{ __('Change Password ') }}</h5> </div>

        <div class="card-body">
          @if(session()->has('status'))
            <p class="alert alert-success">
              {{session()->get('status')}}
            </p>
          @endif
        <form method="POST" action="{{ route('update-password') }}">
            @csrf 

             <!-- @foreach ($errors->all() as $error)
                <p class="text-danger">{{ $error }}</p>
             @endforeach --> 

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autocomplete="current-password" value="{{ old('current_password') }}">
                    @error('current_password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                <div class="col-md-6">
                    <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" autocomplete="current-password" value="{{ old('new_password') }}">

                     @error('new_password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">New Confirm Password</label>

                <div class="col-md-6">
                    <input id="new_confirm_password" type="password" class="form-control @error('new_confirm_password') is-invalid @enderror" name="new_confirm_password" autocomplete="current-password">

                     @error('new_confirm_password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Update Password
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div>
</div>

@endsection