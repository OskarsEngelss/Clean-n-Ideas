<x-main-layout title="Settings">
    <div class="one-grid">

        @if ($errors->any())
            <div class="floating-error-toast show" id="server-error-toast">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($errors->userDeletion->any())
            <div class="floating-error-toast show" id="server-error-toast">
                {{ $errors->userDeletion->first() }}
            </div>
        @endif
        
        <section class="settings-container">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="settings-form">
                @csrf
                @method('PATCH')
                <input type="file" name="profile_picture" id="cropped-image-input" style="display:none;">

                <div class="settings-extras-options">
                    <input id="settings-profile-picture-input" type="file" accept="image/*" hidden>
                    <div class="settings-prolfile-picture-container">
                        <img id="settings-profile-picture" class="settings-profile-picture" alt="Profile icon" src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}">
                        <svg id="settings-profile-picture-svg" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--text-color)">
                            <path d="M480-260q75 0 127.5-52.5T660-440q0-75-52.5-127.5T480-620q-75 0-127.5 52.5T300-440q0 75 52.5 127.5T480-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM160-120q-33 0-56.5-23.5T80-200v-480q0-33 23.5-56.5T160-760h126l74-80h240l74 80h126q33 0 56.5 23.5T880-680v480q0 33-23.5 56.5T800-120H160Zm0-80h640v-480H638l-73-80H395l-73 80H160v480Zm320-240Z"/>
                        </svg>
                    </div>

                    <textarea name="description" class="default-input-style settings-about-textarea settings-input" placeholder="Write a little about yourself!">{{ old('about', $user->description) }}</textarea>
                    
                    <div class="settings-light-dark-mode-container">
                        <button type="button" class="settings-light-dark-mode" id="toggle-light-dark-mode">
                            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--text-color)"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q10 0 20.5.67 10.5.66 24.17 2-37.67 31-59.17 77.83T444-660q0 90 63 153t153 63q53 0 99.67-20.5 46.66-20.5 77.66-56.17 1.34 12.34 2 21.84.67 9.5.67 18.83 0 150-105 255T480-120Z"/></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--text-color)"><path d="M480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-446.67H40v-66.66h160v66.66Zm720 0H760v-66.66h160v66.66ZM446.67-760v-160h66.66v160h-66.66Zm0 720v-160h66.66v160h-66.66ZM260-655.33l-100.33-97 47.66-49 96 100-43.33 46Zm493.33 496-97.66-100.34 45-45.66 99.66 97.66-47 48.34Zm-98.66-541.34 97.66-99.66 49 47L702-656l-47.33-44.67ZM159.33-207.33 259-305l46.33 45.67-97.66 99.66-48.34-47.66Z"/></svg>
                        </button>
                    </div>
                </div>
                <div class="settings-main-options">
                    <input class="settings-input default-input-style" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus>
                    <input class="settings-input default-input-style" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                    <p class="settings-password-change-title">Change password:</p>
                    <input class="settings-input default-input-style" id="current_password" placeholder="Current password" name="current_password" type="password" autocomplete="current-password">
                    <input class="settings-input default-input-style" id="password" placeholder="New password" name="password" type="password" autocomplete="new-password">
                    <input class="settings-input default-input-style" id="password_confirmation" placeholder="Repeat new password" name="password_confirmation" type="password" autocomplete="new-password">
                    <div class="settings-save-cancel-button-container">
                        <div>
                            <button type="button" id="settings-cancel-button">Cancel</button>
                            <button id="settings-save-button" type="submit">Save</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="settings-delete-profile-fake-button-container">
                <div>
                    <button type="button" id="settings-delete-profile-fake-button" data-popup-target="settings-delete-profile-popup">
                        Delete Profile
                    </button>
                </div>
            </div>
        </section>
    </div>

    @push('popup')
        <div id="settings-delete-profile-popup">
            <div class="settings-popups-close-container">
                <button class="settings-popups-off-button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}" class="settings-delete-profile-form">
                @csrf
                @method('DELETE')

                <div class="settings-profile-delete-password-confirm-container">
                    <p>Enter your password to confirm account deletion:</p>
                    <input class="default-input-style" placeholder="Current password" name="password" type="password" autocomplete="current-password" required>
                    <!-- @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror -->
                </div>

                <div class="settings-delete-profile-button-container">
                    <button type="submit" class="settings-delete-profile-button" 
                        onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                        Delete Profile
                    </button>
                </div>
            </form>
        </div>
    @endpush
    @push('popup')
        <div id="settings-profile-picture-cropper-popup">
            <div class="settings-popups-close-container">
                <button class="settings-popups-off-button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                    </svg>
                </button>
            </div>
            <div>
                <div class="cropper-wrapper" style="position: relative; display: inline-block;">
                    <img id="cropper-image" src="" />
                    <div id="crop-box">
                        <div class="resize-handle top-left" style="..."></div>
                        <div class="resize-handle top-right" style="..."></div>
                        <div class="resize-handle bottom-left" style="..."></div>
                        <div class="resize-handle bottom-right" style="..."></div>
                    </div>
                </div>
            </div>
            <button type="button" id="crop-button">Confirm</button>
        </div>
    @endpush
</x-main-layout>