@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('auth.pm_register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('save-project-manager') }}" id="pm-register-form" class="project-manager">
                        @csrf

                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}"  autocomplete="first_name">
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
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}"  autocomplete="last_name">
                                <span class="text-danger" id="last-name-msg"></span>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                           <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company Name') }}</label>

                           <div class="col-md-6">
                                 @php
                                    $getData = (new \App\Helpers\CompanyHelper)->getCompany();
                                    $data = json_decode($getData);
                                 @endphp
                                 <input id="othersCompany" type="text" class="othersCompany pm-register-company form-control @error('company') is-invalid @enderror" name="company"  autocomplete="company" autofocus >
                                 <!-- <select id="othersCompany" type="text" class="othersCompany pm-register-company form-control @error('company') is-invalid @enderror" name="company"  autocomplete="company" autofocus >
                                    <option value="">Select Company</option>
                                 @forelse($data as $key_data =>$value_data)
                                    <option value="{{$value_data->id}}">{{-- $value_data->company_name --}}</option>
                                 @empty
                                    <option>No company here..</option>
                                 @endforelse
                                    <option value="0">Other</option>
                                 </select>-->
                                 <span class="text-danger" id="othersCompany-msg"></span>
                                 @error('company')
                                    <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                    </span>
                                 @enderror
                           </div>
                       </div>

                       <div class="form-group row">
                            <label for="p_email" class="col-md-4 col-form-label text-md-right">{{ __('Email Address') }}</label>

                           <div class="col-md-6">
                              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">
                              <span class="text-danger" id="email-msg"></span>
                              @error('email')
                                 <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                 </span>
                              @enderror
                           </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}"  autocomplete="phone">
                                <span class="text-danger" id="phone-msg"></span>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                            <label for="captcha" class="col-md-4 col-form-label text-md-right">{{ __('Captcha') }}</label>
                            <div class="col-md-6">
                                <span id="captcha" class="@error('captcha') is-invalid @enderror" name="captcha">{!! app('captcha')->display() !!}</span>
                                <input type="hidden" class="hiddenRecaptcha required @error('recaptcha') is-invalid @enderror" name="recaptcha" id="hiddenRecaptcha">
                                <span id="captcha-msg" class="text-danger"></span>
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                                @error('captcha')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="pm-register">
                                    {{ __('Submit') }}
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
