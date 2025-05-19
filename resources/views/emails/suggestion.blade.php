@component('mail::message')
{{-- En-tête avec le logo et le nom de l’entreprise --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('logos/amoamanBlack.png') }}" alt="AMOAMAN et ASSOCIE" style="max-width: 150px;">
    <h1 style="font-size: 24px; font-weight: bold; color: #333; margin: 10px 0;">
        AMOAMAN et ASSOCIE
    </h1>
</div>

{{-- Objet du mail --}}
@component('mail::panel')
# {{ $data['objet'] }}
@endcomponent

{{-- Contenu du message --}}
<div style="font-size: 16px; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
    {!! nl2br(e($data['message'])) !!}
</div>

{{-- Séparateur --}}
@component('mail::separator')@endcomponent

{{-- Pied de page --}}
@component('mail::subcopy')
Merci pour votre contribution,<br>
**AMOAMAN et ASSOCIE**
@endcomponent

{{-- Note confidentielle --}}
@component('mail::subcopy')
<small style="color: #999;">
    Cet email a été envoyé de manière anonyme pour garantir votre confidentialité.
</small>
@endcomponent
@endcomponent
