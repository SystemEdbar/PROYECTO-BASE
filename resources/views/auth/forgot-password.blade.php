<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <div class="text-center p-2">
                <span class="brand-text font-weight-light text-white">
                    <img src="{{asset('asset/img/logo.png')}}" alt="Logo" style="opacity: .8; height:12vh; border-radius:30px"></span>
            </div>
        </x-slot>

        <div class="card-body">

            <div class="mb-3">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <x-jet-validation-errors class="mb-3" />

            <form method="POST" action="/forgot-password">
                @csrf

                <div class="form-group">
                    <x-jet-label value="Email" />
                    <x-jet-input type="email" name="email" :value="old('email')" required autofocus />
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <x-jet-button>
                        {{ __('Email Password Reset Link') }}
                    </x-jet-button>
                </div>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>
