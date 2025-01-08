<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez AMOAMAN et ASSOCIE</title>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg mt-10 p-6">
        {{-- En-tête avec le logo et le nom de l’entreprise --}}
        <div class="text-center mb-5">
            <img src="{{ asset('logos/amoamanBlack.png') }}" alt="AMOAMAN et ASSOCIE" class="mx-auto w-36">
            <h1 class="text-2xl font-bold text-gray-800 mt-2">AMOAMAN et ASSOCIE</h1>
        </div>

        {{-- Objet du mail --}}
        <h2 class="text-xl font-bold text-gray-600 mb-5">
            Bienvenue chez AMOAMAN et ASSOCIE !
        </h2>

        {{-- Contenu du message --}}
        <div class="text-base leading-7 text-gray-700 bg-gray-50 p-5 rounded-lg border border-gray-300">
            <p class="mb-4">
                Bonjour {{ $data['first_name'] }},
            </p>
            <p class="mb-4">
                Nous sommes ravis de vous accueillir parmi nous. Vous pouvez désormais accéder à votre espace utilisateur avec les identifiants suivants :
            </p>

            <div class="mb-4">
                <p><strong>Email :</strong> {{ $data['email'] }}</p>
                <p><strong>Mot de passe :</strong> <span class="text-red-600">11111111</span></p>
            </div>

            <p class="text-sm text-gray-500 mb-4">
                (Mot de passe par défaut, à modifier dès votre première connexion.)
            </p>

            <p class="mb-4">
                Pour vous connecter, veuillez cliquer sur le lien suivant : 
                <a href="http://127.0.0.1:8000/" class="text-blue-600 underline">Accéder à mon espace</a>.
            </p>

            <p class="mb-4">
                N'oubliez pas de modifier votre mot de passe pour des raisons de sécurité dès que possible !
            </p>
        </div>

        {{-- Lignes séparatrices --}}
        <hr class="border-t border-gray-300 my-5">

        {{-- Pied de page --}}
        <div class="text-center text-sm text-gray-600">
            Merci pour votre confiance,<br>
            <strong>AMOAMAN et ASSOCIE</strong>
        </div>

        {{-- Note de sécurité --}}
        <p class="text-xs text-gray-400 text-center mt-5">
            Cet email contient des informations sensibles. Veuillez ne pas les partager avec des tiers.
        </p>
    </div>
</body>
</html>
