<x-mail::message>
{{-- En-tête avec le logo et le nom de l’entreprise --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('logos/amoamanBlack.png') }}" alt="AMOAMAN et ASSOCIE" style="max-width: 150px;">
    <h1 style="font-size: 24px; font-weight: bold; color: #333; margin: 10px 0;">AMOAMAN et ASSOCIE</h1>
</div>

{{-- Objet du mail --}}
<h2 style="font-size: 20px; font-weight: bold; color: #555; margin-bottom: 20px;">
    {{ $data['objet'] }}
</h2>

{{-- Contenu du message --}}
<div style="font-size: 16px; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
    {{ $data['message'] }}
</div>

{{-- Lignes séparatrices --}}
<hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

{{-- Pied de page --}}
<div style="text-align: center; font-size: 14px; color: #555;">
    Merci pour votre contribution,<br>
    <strong>AMOAMAN et ASSOCIE</strong>
</div>

{{-- Optionnel : Inclure une note sur l'anonymat --}}
<p style="font-size: 12px; text-align: center; color: #999; margin-top: 20px;">
    Cet email a été envoyé de manière anonyme pour garantir votre confidentialité.
</p>
</x-mail::message>
