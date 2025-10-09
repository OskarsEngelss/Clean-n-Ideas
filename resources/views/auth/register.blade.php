<x-auth-layout title="Register">
    <div class="login-register-form-container">
        <h1>Clean n Ideas</h1>
        <form class="login-register-form" method="POST" action="{{ route('register') }}">
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
        
            <h2>Register</h2>

            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Name.." required autofocus>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email.." required>
            <input id="password" type="password" name="password" placeholder="Password.." required>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password.." required>

            <div class="redirect-container">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('login') }}">Already registered? Sign in</a>
            </div>

            <button class="submit-button" type="submit">Register</button>
        </form>
    </div>
</x-auth-layout>
