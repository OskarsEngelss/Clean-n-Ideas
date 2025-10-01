<x-auth-layout>
    <div class="login-register-form-container">
        <h1>Clean n Ideas</h1>
        <form class="login-register-form" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Session Status -->
            @if (session('status'))
                <span class="alert-inline alert-success">
                    {{ session('status') }}
                </span>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <span class="alert-inline alert-error">
                    {{ $errors->first() }}
                </span>
            @endif

            <h2>Sign in</h2>

            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email.." required autofocus>
            <input id="password" type="password" name="password" placeholder="Password.." required>

            <div class="redirect-container">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('register') }}">Register instead</a>
            </div>

            <button class="submit-button" type="submit">Sign in</button>

            <!-- Remember Me -->
            <!-- <div>
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div> -->
            
            <!-- Forgot Password Link -->
            <!-- @if (Route::has('password.request'))
                <div>
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            @endif -->
        </form>
    </div>
</x-auth-layout>