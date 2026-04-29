{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Inscription')

@push('styles')
<style>
    .auth-page { min-height: calc(100vh - 128px); display: flex; align-items: center; background: var(--leon-surface); padding: 3rem 0; }
    .auth-card { background: #fff; border: 1px solid var(--leon-border); border-radius: 16px; box-shadow: 0 4px 40px rgba(0,0,0,0.07); overflow: hidden; }
    .auth-header { background: var(--leon-dark); padding: 2rem; text-align: center; border-bottom: 2px solid var(--leon-gold); color: #fff; }
    .auth-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0; }
    .auth-body { padding: 2rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--leon-muted); margin-bottom: 5px; }
    .form-input { width: 100%; padding: 10px; border: 1.5px solid var(--leon-border); border-radius: 8px; font-family: 'DM Sans'; outline: none; }
    .btn-leon { width: 100%; padding: 12px; background: var(--leon-dark); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1 class="auth-title">Créer un compte</h1>
                        <p style="font-size:0.85rem; opacity:0.8; margin-top:5px; margin-bottom:0;">Rejoignez la plateforme Chez Léon</p>
                    </div>
                    <div class="auth-body">
                        <form method="POST" action="{{ route('public.register.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" name="name" class="form-input" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Pseudo</label>
                                    <input type="text" name="pseudo" class="form-input" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-input" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Mot de passe</label>
                                    <input type="password" name="password" class="form-input" required minlength="8">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Confirmer</label>
                                    <input type="password" name="password_confirmation" class="form-input" required minlength="8">
                                </div>
                            </div>
                            <button type="submit" class="btn-leon mt-3">S'inscrire</button>
                        </form>
                        <div class="text-center mt-4">
                            <a href="{{ route('public.login') }}" style="color:var(--leon-dark); font-size:0.85rem; text-decoration:none;">
                                Déjà un compte ? <strong>Connectez-vous</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection