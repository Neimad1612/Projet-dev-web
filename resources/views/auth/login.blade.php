{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Connexion')

@push('styles')
<style>
    .auth-page {
        min-height: calc(100vh - 68px - 60px);
        display: flex;
        align-items: center;
        background: var(--leon-surface);
        position: relative;
        overflow: hidden;
    }
    .auth-card {
        background: #fff;
        border: 1px solid var(--leon-border);
        border-radius: 16px;
        box-shadow: 0 4px 40px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .auth-header {
        background: var(--leon-dark);
        padding: 2rem 2.5rem 1.5rem;
        text-align: center;
        position: relative;
    }
    .auth-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        color: #fff;
        margin: 0;
    }
    .auth-body { padding: 2rem 2.5rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-label {
        display: block; font-size: 0.78rem; font-weight: 600;
        text-transform: uppercase; color: var(--leon-muted); margin-bottom: 6px;
    }
    .form-input {
        width: 100%; padding: 10px 14px; border: 1.5px solid var(--leon-border);
        border-radius: 8px; font-family: 'DM Sans', sans-serif;
    }
    .btn-leon {
        width: 100%; padding: 12px; background: var(--leon-dark);
        color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1 class="auth-title">Connexion</h1>
                        <p style="color:rgba(255,255,255,0.5);font-size:0.8rem;margin-top:5px;">Chez Léon</p>
                    </div>
                    <div class="auth-body">
                        <form method="POST" action="{{ route('public.login.post') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Adresse e-mail</label>
                                <input type="email" name="email" class="form-input" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-input" required>
                            </div>
                            <button type="submit" class="btn-leon mt-3">Se connecter</button>
                        </form>
                        <div class="mt-4 text-center">
                            <a href="{{ route('public.register') }}" style="color:var(--leon-dark);font-size:0.85rem;text-decoration:none;">
                                Pas encore de compte ? <strong>S'inscrire</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection