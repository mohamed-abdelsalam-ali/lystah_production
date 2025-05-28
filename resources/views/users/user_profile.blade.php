@extends('layouts.master')
@section('css')
    <style>
        .font1 {
            font-family: 'sans-serif';
            font-size: 17px;


        }

        table.dataTable td {
            /* font-size: 3em!important; */
            font-family: sans-serif !important;
            font-size: 16px !important;
        }
    </style>
@endsection
@section('title')
تعديل البيانات الشخصية
@stop
@section('Toolbar container')
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                تعديل البيانات الشخصية
            </h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="index.html" class="text-muted text-hover-primary">
                        البيانات </a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted"> الشخصية</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
      
            <!--begin::Secondary button-->
            {{-- <a href="#"
            class="btn btn-sm fw-bold bg-body btn-color-gray-700 btn-active-color-primary"
            data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Rollover</a> --}}
            <!--end::Secondary button-->
            <!--begin::Primary button-->
            <!--end::Primary button-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
@endsection


@section('content')

    <!--begin::Content-->
    <div class="container rounded bg-white mt-5 mb-5">
        <form action="/users/update" method="post" enctype="multipart/form-data">
            {{ method_field('patch') }}
            {{ csrf_field() }}
            <div class="row">
                @if($user->profile_img != null )
                    <div class="col-md-3 border-right">
                        <div class="d-flex flex-column align-items-center text-center p-3 py-5 ">
                            <img class="rounded-circle mt-5 displayImagee" width="150px"
                                src="{{ asset('users_images/' . $user->profile_img) }}">
                            <span class="font-weight-bold">{{ $user->username }}</span>
                            <span>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="col-md-3 border-right">
                        <div class="d-flex flex-column align-items-center text-center p-3 py-5 "><img
                                class="rounded-circle mt-5 displayImagee" width="150px"
                                src="{{ asset('users_images/default.png') }}"><span
                                class="font-weight-bold">{{ $user->username }}</span><span>
                            </span></div>
                    </div>
                @endif

                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right"> بيانات المستخدم</h4>
                        </div>
                        <div class="row mt-2">
                            <input type="text" class="d-none" name="user_id" value="{{ $user->id }}">
                            <div class="col-md-12"><label class="labels"> اسم المستخدم</label><input type="text"
                                    class="form-control" name="username" value="{{ $user->username }}">
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label class="labels">رقم التليفون </label><input type="text"
                                    class="form-control" name="telephone" value="{{ $user->telephone }}">
                                @error('telephone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6"><label class="labels">رقم تليفون اخر</label><input type="text"
                                    class="form-control" value="{{ $user->mobile }}" name="mobile">

                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">تعديل الصورة الشخصية</label>
                                <input type="file" class="form-control" name="img_path" id="logo_user">

                            </div>
                        </div>
                         <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">تعديل صورة الرقم القومي </label>
                                <input type="file" class="form-control" name="national_img" id="">

                            </div>
                        </div>
                        <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Save
                                Profile</button></div>
                    </div>
                </div>
        </form>

        <div class="col-md-4">
            <form action="/update_password" method="post" enctype="multipart/form-data">
                {{ method_field('GET') }}
                {{ csrf_field() }}
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><h4>تعديل كلمة المرور
                </h4></div><br>
                <div class="col-md-12"><label class="labels">كلمة المرور الحالية</label>
                    <input type="text" class="d-none" name="user_id" value="{{ $user->id }}">

                    <input id="current_password" name="current_password" type="password" class="form-control"
                        autocomplete="off">
                    @error('current_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div> <br>
                <div class="col-md-12"><label class="labels">كلمة المرور الجديدة</label><input type="password"
                        class="form-control" id="password" name="password" value="">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12"><label class="labels">تأكيد كلمة المرور الجديدة</label><input type="password"
                        class="form-control" id="password_confirmation" name="password_confirmation" value="">
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">تعديل
                    كلمة المرور</button></div>
        </div>
            </div>
        </div>
    </div>
    </form>

    <!--end::Content-->

    
@endsection
@section('js')


@endsection
