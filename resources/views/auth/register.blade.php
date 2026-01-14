<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" onsubmit="showLoading(this)">
        @csrf

        <div style="font-size: 30px; font-weight: bold">
            Create Account
        </div>
        <div style="color: #6b6b6bff">
            Create an account to start managing content.
        </div>

        @if ($errors->has('email'))
            <div class="error-box">
                <div class="error-icon">!</div>
                <div class="error-text">
                    <strong>We couldn't create your account. Please check your details and try again.</strong>
                    <!-- <p>If you forgot your password, reset it below.</p> -->
                </div>
            </div>
        @endif

        @if ($errors->has('password'))
            <div class="error-box">
                <div class="error-icon">!</div>
                <div class="error-text">
                    <strong>We couldn't create your account. Please check your details and try again.</strong>
                    <!-- <p>If you forgot your password, reset it below.</p> -->
                </div>
            </div>
        @endif

        <!-- Name -->
        <div class="mt-5">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required 
                            autocomplete="new-password"
                            placeholder="At least 8 characters" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" 
                          class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" 
                          required 
                          autocomplete="new-password"
                          placeholder="Re-enter your password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-primary-button id="saveBtn" class="pt-4 pb-4 primary_bgcolor" style="width: 100%">
                <div id="btnContent" class="btn-center">
                    Create Account
                </div>
            </x-primary-button>
        </div>

        

        <br>
        <hr>

        <div class="mt-4" style="text-align: center">
            <div>
                Already have an account? <a href="{{ route('login') }}"class="primary_color">Sign in</a>
            </div>

            <div class="mt-2" style="color: #6E6E73; font-size: 12px">
                By creating an account, you agree to basic usage terms.
            </div>
        </div>

    </form>
</x-guest-layout>

<script>
function showLoading() {
    const btn = document.getElementById('saveBtn');
    const content = document.getElementById('btnContent');

    btn.disabled = true;
    content.innerHTML = `<span class="spinner"></span><span>Creating Account...</span>`;
}
</script>