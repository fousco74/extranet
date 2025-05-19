<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrat {{ $contract->id }}</title>
    <style>
        /* Styles de base */
        body {
            background-color: #ffffff;
            font-family: sans-serif;
            line-height: 1.5;
        }

        .border-gray-200 {
            border: 1px solid #e5e7eb;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .opacity-90 {
            opacity: 0.9;
        }

        /* Styles spécifiques */
        .signature-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        @media (min-width: 768px) {
            .signature-section {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .article-content {
            white-space: pre-line;
            margin-top: 0.5rem;
        }

        .article-content p {
            margin-bottom: 0.5rem;
        }

        .signature-image {
            height: 4rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            margin: 0 auto;
        }

        .info-table td {
            padding: 0.25rem 0;
            vertical-align: top;
        }
    </style>
</head>
<body style="background-color: #ffffff; padding: 1rem;">
    <div style="padding: 1rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
        <div style="display: flex; flex-direction: column; gap: 1.5rem; font-size: 0.875rem;">

            <!-- Logo -->
            <div style="display: flex; justify-content: center; align-items: flex-start; padding: 0 1rem;">
                <img src="{{ public_path('logos/LogoAMOAMANnew.webp') }}" alt="Logo AMOAMAN"
                     style="height: 4rem; object-fit: cover;">
            </div>

            <!-- Type de contrat -->
            <div style="display: flex; justify-content: center; align-items: center; text-align: center;">
                <h1 style="font-weight: 700; font-size: 1.25rem; opacity: 0.9; max-width: 40rem;">
                    {{ $contract->contract_type }}
                </h1>
            </div>

            <!-- Présentation de l'employeur -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <p style="opacity: 0.9; text-align: center;">Entre les soussignés,</p>
                <div style="margin-left: 0; margin-top: 0.5rem; opacity: 0.9; text-align: justify;">
                    <p>
                        La société <span style="font-weight: 600;">AMOAMAN & ASSOCIES</span>,
                        au capital de <span style="font-weight: 600;">1 000 000 FCFA</span>,
                        dont le siège social est à Cocody Riviera 3,
                        immatriculée au RCS d'Abidjan sous le n° Cl-ABJ-03-2018-B12-33468.
                        Le représentant légal est Monsieur
                        <span style="font-weight: 600;">AMOAKON EL HADJI DIHYE YORO</span>,
                        en qualité de fondateur-directeur.
                    </p>
                </div>
            </div>

            <!-- Informations du collaborateur -->
            <div style="display: flex; flex-direction: column; opacity: 0.9;">
                <span style="align-self: flex-end; font-weight: 600; padding: 0 1rem;">D'une part</span>
                <div style="margin-left: 0; margin-top: 0.5rem;">
                    <table class="info-table" style="width: 100%; font-size: 0.875rem;">
                        <!-- ... (le contenu du tableau reste identique) ... -->
                    </table>
                </div>
                <span style="align-self: flex-end; font-weight: 600; padding: 0 1rem;">D'autre part</span>
            </div>

            <!-- Clause introductive -->
            <div style="display: flex; justify-content: center; align-items: center;">
                <h4 style="font-weight: 600; opacity: 0.9; text-decoration: underline; text-align: center;">
                    IL A ÉTÉ CONVENU CE QUI SUIT :
                </h4>
            </div>

            <!-- Articles du contrat -->
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($contract->articles as $index => $article)
                <div style="margin-left: 0; margin-top: 0.5rem; opacity: 0.9;">
                    <p style="font-weight: 600; text-decoration: underline; padding: 0.5rem 0; text-align: center;">
                        Article {{ $index + 1 }} : {{ $article['title'] }}
                    </p>
                    <div class="article-content">
                        {!! $article['contents'] !!}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Date et lieu -->
            <div class="flex flex-col items-center md:items-end p-4 space-y-2">
                <p class="text-center md:text-right">
                    Fait en deux exemplaires, à Abidjan le
                    {{ $contract->date_signature ? $contract->date_signature : now()->format('d/m/Y') }}
                </p>
                <p class="text-center md:text-right">
                    (Signatures des parties précédées de la mention « lu et approuvé »)
                </p>
            </div>



            <!-- Signatures en pied -->
            <div class="flex flex-col md:flex-row justify-between gap-4 md:gap-8">
                <div class="flex-1 space-y-4 text-center md:text-left">
                    <p class="text-sm text-gray-600">
                        {{ str_contains(strtolower($contract->contract_type), 'stage') ? 'Stagiaire' : 'Collaborateur' }}
                    </p>
                    <span class="font-semibold text-xs">
                        {{ $contract->assignedUser->first_name }} {{ $contract->assignedUser->last_name }}
                    </span>
                    @if($contract->signature)
                    <img src="{{ public_path($contract->signature) }}" class="h-16 w-auto border rounded-lg mx-auto md:mx-0" />
                    @endif
                </div>
                <div class="flex-1 flex flex-col justify-center md:justify-end items-center md:items-end">
                    <div class="flex flex-col text-center md:text-right">
                        <p class="text-sm text-gray-600 mb-2">AMOAMAN & ASSOCIES</p>
                        <span class="font-semibold text-xs">Natacha KAKOU</span>
                        <span class="font-semibold text-xs">Responsable Ressources Humaines</span>
                    </div>
                    <div class="px-4 mt-4">
                        <img src="{{ public_path('icons/tampom.svg') }}" alt="Tampon" class="h-20 object-cover mx-auto md:mx-0" />
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
