<x-auth-layout>
    <div class="form-container">
        <form method="POST" action="{{ route('register') }}">
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
                <h2>Register</h2>
            </div>

            <!-- Name -->
            <div>
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <!-- Email Address -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <!-- Password -->
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <!-- Redirect Links -->
            <div class="redirect-container">
                <a href="{{ route('login') }}">Already registered? Log in</a>
                <a href="{{ route('home') }}">Home</a>
            </div>

            <!-- Submit Button -->
            <div>
                <button class="submit-button" type="submit">Register</button>
            </div>
        </form>
    </div>
</x-auth-layout>
