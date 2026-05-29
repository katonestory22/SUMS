@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('users.index') }}">Users</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .form-card {
            max-width: 950px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .form-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .profile-preview {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
        }

        .profile-preview img {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e5e7eb;
        }

        .profile-placeholder {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background: #dbeafe;
        }

        .profile-info h3 {
            margin: 0;
            font-size: 18px;
            color: #111827;
        }

        .profile-info p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .full {
            grid-column: span 2;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
        }

        .password-box {
            margin-top: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
        }

        .password-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #111827;
        }

        .submit-btn {
            margin-top: 30px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 13px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .danger-zone {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }

        .danger-title {
            color: #dc2626;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .danger-text {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .delete-btn {
            background: #dc2626;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .delete-btn:hover {
            background: #b91c1c;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        @media(max-width:768px) {

            .grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: span 1;
            }
        }
    </style>

    <div class="form-card">

        <div class="form-header">
            <div class="form-title">Edit User</div>

            <div class="form-subtitle">
                Update identity, role access, password and account state
            </div>
        </div>

        <div class="profile-preview">

            @if ($user->passport_photo)
                <img src="{{ asset($user->passport_photo) }}">
            @else
                <div class="profile-placeholder"></div>
            @endif

            <div class="profile-info">
                <h3>
                    {{ $user->first_name }} {{ $user->last_name }}
                </h3>

                <p>
                    {{ ucfirst($user->role) }}
                </p>
            </div>

        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="grid">

                <div class="form-group">
                    <label>First Name</label>

                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}">

                    @error('first_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Middle Name</label>

                    <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                </div>

                <div class="form-group">
                    <label>Last Name</label>

                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}">

                    @error('last_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>

                    <input type="email" name="email" value="{{ old('email', $user->email) }}">

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Phone</label>

                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="form-group">
                    <label>National ID</label>

                    <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}">
                </div>

                <div class="form-group">
                    <label>Role</label>

                    <select name="role">

                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="director" {{ $user->role == 'director' ? 'selected' : '' }}>
                            Director
                        </option>

                        <option value="finance" {{ $user->role == 'finance' ? 'selected' : '' }}>
                            Finance
                        </option>

                        <option value="technical" {{ $user->role == 'technical' ? 'selected' : '' }}>
                            Technical
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>

                    <select name="status">

                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                <div class="form-group full">
                    <label>Address</label>

                    <textarea name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>

                    <input type="date" name="date_of_birth"
                        value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label>Passport Photo</label>

                    <input type="file" name="passport_photo">
                </div>

            </div>

            <div class="password-box">

                <div class="password-title">
                    Reset Password
                </div>

                <div class="grid">

                    <div style="position:relative;">
                        <input type="password" id="password" name="password">

                        <span onclick="togglePassword('password', this)"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#6b7280;">

                            <i class="fa-solid fa-eye"></i>

                        </span>

                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="position:relative;">
                        <input type="password" id="password_confirmation" name="password_confirmation">

                        <span onclick="togglePassword('password_confirmation', this)"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#6b7280;">

                            <i class="fa-solid fa-eye"></i>

                        </span>
                    </div>

                </div>

            </div>

            <button class="submit-btn">
                Update User
            </button>

        </form>

        <div class="danger-zone">

            <div class="danger-title">
                Danger Zone
            </div>

            <div class="danger-text">
                Deleting a user permanently removes their account from the system.
                Humans adore irreversible decisions for some reason.
            </div>

            @if (auth()->id() !== $user->id)
                <form action="{{ route('users.destroy', $user->id) }}" method="POST">

                    @csrf
                    @method('DELETE')

                    <button type="button" class="delete-btn" onclick="openDeleteModal()">
                        Delete User
                    </button>

                </form>
            @endif

        </div>

    </div>

    {{-- DELETE MODAL --}}
    <div id="deleteModal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">

        <div style="background:white; padding:25px; border-radius:12px; width:360px; text-align:center;">

            <h3 style="margin-bottom:10px;">Delete User?</h3>

            <p style="font-size:13px; color:#6b7280; margin-bottom:20px;">
                This action is permanent. No undo. Not even a “sorry boss” button.
            </p>

            <div style="display:flex; gap:10px; justify-content:center;">

                <button type="button" onclick="closeDeleteModal()"
                    style="padding:10px 14px; border:1px solid #ccc; background:white; border-radius:8px; cursor:pointer;">
                    Cancel
                </button>

                <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        style="padding:10px 14px; background:#dc2626; color:white; border:none; border-radius:8px; cursor:pointer;">
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>

    <script>
        function togglePassword(id, el) {

            const input = document.getElementById(id);
            const icon = el.querySelector('i');

            if (input.type === "password") {

                input.type = "text";

                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

            } else {

                input.type = "password";

                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

@endsection
