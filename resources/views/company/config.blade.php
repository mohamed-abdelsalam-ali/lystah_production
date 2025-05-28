<x-guestRegester-layout>
    <!-- resources/views/company/config.blade.php -->

<div class="container">
<div class="row mb-4 justify-content-center">
        <div class="col-12">
 
            <div class="d-flex flex-wrap gap-2 ">
                <a href="/users" class="btn btn-primary">
                    <i class="ri-user-line me-1"></i> المستخدمين
                </a>
                <a href="/user_role" class="btn btn-info">
                    <i class="ri-user-settings-line me-1"></i> وظائف المستخدم
                </a>
                <a href="/role" class="btn btn-success">
                    <i class="ri-shield-user-line me-1"></i> الصلاحيات
                </a>
                <a href="/role_perm" class="btn btn-warning">
                    <i class="ri-lock-line me-1"></i> صلاحيات المستخدم
                </a>
                <a href="/pricing_type" class="btn btn-danger">
                    <i class="ri-money-dollar-circle-line me-1"></i> أنواع التسعيير
                </a>
                <a href="/taxes" class="btn btn-secondary">
                    <i class="ri-bank-card-line me-1"></i> الضرائب
                </a>
                <a href="/currency" class="btn btn-dark">
                    <i class="ri-exchange-dollar-line me-1"></i> العملات
                </a>
            </div>
               
        </div>
    </div>
    <div class="row justify-content-center">
        <!-- <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add New User</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('company.users.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                Confirm Password
                            </label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>
                            <div class="col-md-6">
                                <select id="role" class="form-control" name="role" required>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Add User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
    </div>
</div>
</x-guestRegester-layout>