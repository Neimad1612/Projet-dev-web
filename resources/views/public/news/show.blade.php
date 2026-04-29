@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div class="container py-5">
    <a href="{{ route('public.news.index') }}" class="btn btn-outline-dark mb-4">
        ← Retour aux actualités
    </a>

    <article class="card shadow-sm" style="border-color:var(--leon-border);">
        <div class="card-body p-4">
            <span class="badge mb-3" style="background:var(--leon-gold); color:#000;">
                {{ ucfirst($news->category) }}
            </span>

            <h1 style="font-family:'Playfair Display', serif;">
                {{ $news->title }}
            </h1>

            <p class="text-muted">
                Publié le {{ optional($news->published_at)->format('d/m/Y') }}
            </p>

            <p class="lead">{{ $news->excerpt }}</p>

            <hr>

            <p>{{ $news->content }}</p>
        </div>
    </article>
</div>
@endsection