@extends('frontend.layouts.app')

@section('title', 'Login - OurKitchen')

@section('content')
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-800">Login</h2>
                        <p class="text-gray-600 mt-2">Masuk ke akun OurKitchen Anda</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="mt-1">
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <div class="mt-1">
                                    <input id="password" name="password" type="password" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember_me" name="remember" type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                                </div>

                                {{-- <div class="text-sm">
                                    <a href="{{ route('password.request') }}"
                                        class="font-medium text-blue-600 hover:text-blue-500">
                                        Lupa password?
                                    </a>
                                </div> --}}
                            </div>

                            <div>
                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
