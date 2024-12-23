<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
    <style>
        body {
            background-image: url('fond/FondTrombinoscopeAMOAMAN.png');
            background-repeat: no-repeat;
            background-size: cover; 
            background-position: center; 
            margin: 0;
            height: 100vh;
            width: 100vw; 
            overflow: hidden;
        }

        @media (max-width: 1024px) { /* Tablette */
            body {
                background-image: url('fond/fondtablete.png');
            }
        }

        @media (max-width: 768px) { /* Téléphone */
            body {
                background-image: url('background/fondtelephone.png');
            }
        }
    </style>
</head>
<body>
<div class="flex">
    <!-- logo -->
    <div class="w-[8%] h-screen ml-16 max-sm:mt-10 max-sm:w-[14%] max-sm:ml-0 max-lg:ml-0 max-lg:w-[60%]">
        <img src="{{ asset('background/LogoAMOAMANnew.png') }}" alt="Logo">
    </div>

    <div class="w-full">
        <div class="w-full h-screen overflow-y-auto">
            <!-- Filtrage par type d'équipe -->
            <div class="flex justify-center float-end mr-20 max-sm:mr-8 mt-4">
                <form method="GET" action="{{ route('membersList') }}">
                    <select name="equipe" onchange="this.form.submit()" class="border rounded p-2">
                        <option value="">Tous</option>
                        <option value="interne" {{ request('equipe') === 'interne' ? 'selected' : '' }}>Internes</option>
                        <option value="externe" {{ request('equipe') === 'externe' ? 'selected' : '' }}>Externes</option>
                    </select>
                </form>
            </div>

            <!--first line -->
            <div class="pr-10 flex w-full justify-center">
                <div class="w-[100%] flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-6">
                    @php $count = 0 @endphp
                    @foreach ($membersFird as $member)
                        @php $count++ @endphp
            
                        <div class="w-[120px] max-sm:w-[90px] max-lg:w-[140px] relative group">
                            <!-- L'image reste visible et ne change pas -->
                            <img src="{{ asset('storage/' . $member->profile_link) }}" alt="image" class="size-full object-cover">
            
                            <a title="Cliquez ici pour consulter son profil LinkedIn" href="{{$member->linkedin_link}}">
                                <!-- Conteneur pour les deux divs, elles permutent sans changer la taille -->
                                <div class="relative h-auto cursor-pointer">
                                    <!-- div 1 (visible par défaut) -->
                                    <div @class(["absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px] border p-1 rounded-xl border-2", "bg-white border-[#223451]" => $count % 2 == 0, "bg-[#C9847C] text-white" => $count % 2 == 1])>
                                        <span @class(["text-nowrap font-bold text-[7px]", "text-[#223451]" => $count % 2 == 0, "text-white" => $count % 2 == 1])>{{ $member->first_name . " " . $member->last_name }}</span><br>
                                        <span class="text-nowrap">{{ $member->poste }}</span><br>
                                    </div>
                
                                    <!-- div 2 (visible au survol) -->
                                    <div @class(["absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px] border p-1 rounded-xl border-2", "bg-white border-[#223451]" => $count % 2 == 0, "bg-[#C9847C] text-white" => $count % 2 == 1])>
                                        <span class="text-nowrap">{{ $member->email }}</span><br>
                                        <span class="text-nowrap">{{ $member->phone_number }}</span>
                                    </div>
                                </div>
                            </a>

                        </div>
                       
                    @endforeach
                </div>
            </div>
            
            

            <!-- seconde line -->
            @if(isset($memberFour[0]))
            <div class="pr-10 py-7 flex w-full justify-center">
                <div class="w-[100%] flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-4">
                    <div class="w-[120px] max-sm:w-[90px] max-lg:w-[140px] relative group">
                        <!-- L'image reste visible et ne change pas -->
                        <img src="{{ asset('storage/' . $memberFour[0]->profile_link) }}" alt="image" class="size-full object-cover">
            
                        <a href="{{$member->linkedin_link}}">
                            <!-- Conteneur pour les deux divs, elles permutent sans changer la taille -->
                            <div title="Cliquez ici pour consulter son profil LinkedIn" class="relative h-auto cursor-pointer">
                                <!-- div 1 (visible par défaut) -->
                                <div @class(["absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px] border p-1 rounded-xl border-2 bg-[#223451] text-white"])>
                                    <span class="text-nowrap font-bold text-[7px]">{{ $memberFour[0]->first_name . " " . $memberFour[0]->last_name }}</span><br>
                                    <span class="text-nowrap">{{ $memberFour[0]->poste }}</span><br>
                                </div>
                
                                <!-- div 2 (visible au survol) -->
                                <div @class(["absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px] border p-1 rounded-xl border-2 bg-[#223451] text-white"])>
                                    <span class="text-nowrap">{{ $memberFour[0]->email }}</span><br>
                                    <span class="text-nowrap">{{ $member->phone_number }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            @endif

            <!-- externe leads -->
            @if($equipe == "externe")

            <div class="pr-10 py-7 flex w-full justify-center">
                <div class="w-[100%] flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-4">
                    @foreach ($membersLeads as $member)
            
                    <div class="w-[120px] max-sm:w-[90px] max-lg:w-[140px] relative group">
                        <!-- L'image reste visible et ne change pas -->
                        <img src="{{ asset('storage/' . $member->profile_link) }}" alt="image" class="size-full object-cover">
            
                        <a href="{{$member->linkedin_link}}">
                            <!-- Conteneur pour les deux divs, elles permutent sans changer la taille -->
                            <div title="Cliquez ici pour consulter son profil LinkedIn" class="relative h-auto cursor-pointer">
                                <!-- div 1 (visible par défaut) -->
                                <div @class(["absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px] border p-1 rounded-xl border-2 bg-[#223451] text-white"])>
                                    <span class="text-nowrap font-bold text-[7px]">{{ $member->first_name . " " . $member->last_name }}</span><br>
                                    <span class="text-nowrap">{{ $member->poste }}</span>
                                </div>
                
                                <!-- div 2 (visible au survol) -->
                                <div @class(["absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px] border p-1 rounded-xl border-2 bg-[#223451] text-white"])>
                                    <span class="text-nowrap">{{ $member->email }}</span><br>
                                    <span class="text-nowrap">{{ $member->phone_number }}</span>

                                </div>
                            </div>
                        </a>    
                    </div>
            
                    @endforeach
                </div>
            </div>
            
        @endif

            <!--rest line -->
            <div class="pr-10 py-4 flex w-full justify-center">
                <div class="w-[100%] flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-6">
                    @php $count = 0; @endphp
                    @foreach($membersRest as $member)
                        @php $count++; @endphp
            
                        <div class="w-[120px] max-sm:w-[90px] max-sm:my-6 my-4 max-lg:w-[140px] relative group">
                            <!-- Image qui reste toujours visible -->
                            <img src="{{ asset('storage/' . $member->profile_link) }}" alt="image" class="size-full object-cover">
            
                            @php
                                // Déterminer la classe de fond
                                if ($count <= 6) {
                                    $colorIndex = floor(($count - 1) / 2) % 2; // 0 ou 1
                                    $bgClass = ($colorIndex === 0) ? 'bg-[#223451]' : 'bg-[#C9847C]';
                                } else {
                                    // Couleurs pour les membres après le 6ème
                                    $bgClass = ($count % 2 == 0) ? 'bg-[#C9847C]' : 'bg-[#223451]';
                                }
                            @endphp
            
                            <a href="{{$member->linkedin_link}}">
                                <!-- Conteneur pour les deux divs permutables -->
                                <div title="Cliquez ici pour consulter son profil LinkedIn" class="relative h-auto cursor-pointer">
                                    <!-- div 1 (visible par défaut) -->
                                    <div @class([
                                        "absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px] p-1 rounded-xl border-2",
                                        $bgClass,
                                        "text-white"
                                    ])>
                                        <span class="text-nowrap font-bold text-[7px]">{{ $member->first_name . ' ' . $member->last_name }}</span><br>
                                        <span class="text-nowrap">{{ $member->poste }}</span>

                                    </div>
                
                                    <!-- div 2 (visible au survol) -->
                                    <div @class([
                                        "absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px] p-1 rounded-xl border-2",
                                        $bgClass,
                                        "text-white"
                                    ])>
                                        <span class="text-nowrap">{{ $member->email }}</span><br>
                                        <span class="text-nowrap">{{ $member->phone_number }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            


        </div>
    </div>
</div>

</body>
</html>
