<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" onsubmit="showLoading(this)">
        @csrf

        <div style="font-size: 30px; font-weight: bold">
            Sign in
        </div>
        <div style="color: #6b6b6bff">
            Enter your credentials to continue.
        </div>
    
        @if ($errors->has('email'))
            <div class="error-box">
                <div class="error-icon">!</div>
                <div class="error-text">
                    <strong>Incorrect email or password. Please try again.</strong>
                    <p>If you forgot your password, reset it below.</p>
                </div>
            </div>
        @endif

        @if ($errors->get('password'))
                <div class="error-box">
                    <div class="error-icon">!</div>
                    <div class="error-text">
                        <strong>Incorrect email or password. Please try again.</strong>
                        <p>If you forgot your password, reset it below.</p>
                    </div>
                </div>
            @endif

        <!-- Email Address -->
        <div class="mt-5">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com"/>
            
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="Enter your password" />

            
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
    <div class="flex items-center justify-between">
        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox"
                class="rounded border-gray-300 primary_color shadow-sm focus:ring-indigo-500"
                name="remember">
            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>

        @if (Route::has('password.request'))
            <a class="text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('password.request') }}"
               style="color: #007AFF">
                {{ __('Forgot your password?') }}
            </a>
        @endif
    </div>
</div>


        <div class="mt-4">
            

            <x-primary-button id="saveBtn" class="pt-4 pb-4 primary_bgcolor" style="width: 100%">
                <div id="btnContent" class="btn-center">
                    {{ __('Log in') }}
                </div>
            </x-primary-button>
        </div>

        <br>
        <hr>

        <div class="mt-4" style="text-align: center">
            <div>
                Don't have an account? <a href="{{ route('register') }}"class="primary_color">Create one</a>
            </div>
        </div>
    </form>
</x-guest-layout>

<script>
function showLoading() {
    const btn = document.getElementById('saveBtn');
    const content = document.getElementById('btnContent');

    btn.disabled = true;
    content.innerHTML = `<span class="spinner"></span><span>Signing in...</span>`;
}
</script>
