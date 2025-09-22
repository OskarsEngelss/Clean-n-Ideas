<x-auth-layout>
    <div class="form-container">
        <form method="POST" action="{{ route('login') }}">
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

            <div>
                <h2>Login</h2>
            </div>

            <!-- Email -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <!-- Password -->
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <!-- Remember Me -->
            <div>
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <div class="redirect-container">
                <a href="{{ route('register') }}">Register instead</a>
                <a href="{{ route('home') }}">Home</a>
            </div>

            <!-- Forgot Password Link -->
            <!-- @if (Route::has('password.request'))
                <div>
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            @endif -->

            <!-- Submit Button -->
            <div>
                <button class="submit-button" type="submit">Log in</button>
            </div>
        </form>
    </div>
</x-auth-layout>