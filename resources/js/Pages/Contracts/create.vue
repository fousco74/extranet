<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import FrontendContent from '../dashboard/frontendContent.vue';
import { CheckCircle, XCircle, Plus } from 'lucide-vue-next';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

const props = defineProps({
    users: Array,
});


const currentStep = ref(1);
const totalSteps = 3;
const formErrors = ref({});
let processedData;
let employee;

const contractTemplates = {
    'CONTRAT DE STAGE DE QUALIFICATION PROFESSIONNELLE': [
        {
            title: 'OBJET DU CONTRAT',
            contents: 'La société [NOM_ENTREPRISE] engage ce jour Mr [NOM] en qualité de [POSTE].\n\nIl s\'engage à exécuter tous les travaux que nécessite sa formation et se conformer au règlement intérieur de la société, aux consignes internes du service. Le stagiaire pourra servir sur tous les sites où l\'employeur l\'affectera dans le cadre de sa formation.',
        },
        {
            title: 'DUREE DU CONTRAT',
            contents: 'Ce présent contrat de stage est conclu pour une durée de [DUREE], du [DATE_DEBUT] au [DATE_FIN]. Il est renouvelable à l\'appréciation de l\'employeur dans la limite de 12 mois sauf dénonciation d\'une des parties.',
        },
        {
            title: 'FONCTIONS',
            contents: 'Les activités de stage de la fiche de poste sont annexées au présent contrat.\n\nLe stagiaire aura pour maître de stage [SUPERVISEUR] occupant la fonction de [POSTE_SUPERVISEUR].\n\nIl devra se mettre à la disposition de celui-ci.\n\nLes responsables peuvent infliger des sanctions au stagiaire qui iront jusqu\'à l\'exclusion en cas de faute lourde.',
        },
        {
            title: 'CLAUSE DE MORALITÉ',
            contents: 'Le stagiaire affirme sur l\'honneur n\'être pas connu au fichier central de la Police et n\'avoir jamais fait l\'objet de condamnation pénale. La découverte de toute fausse déclaration même ultérieurement à la signature du présent contrat entraînera la rupture dudit contrat sans préavis ni indemnité.',
        },
        {
            title: 'REMUNERATION',
            contents: 'Mr [NOM] percevra une prime de stage mensuelle de [SALAIRE] FCFA.',
        },
        {
            title: 'LIEU D\'EXERCICE',
            contents: 'Le stagiaire exercera ses fonctions sur le site de [LIEU]. Cependant, en raison des nécessités du service, la stagiaire exercera ses fonctions en tout lieu du territoire national où ses compétences seront requises par l\'employeur.',
        },
        {
            title: 'EVALUATION',
            contents: 'Le stagiaire fera l\'objet d\'une évaluation à travers des tests, examens et les rapports de son responsable.',
        },
        {
            title: 'RUPTURE DU STAGE',
            contents: 'Ce présent contrat prendra fin en cas:\n\n. De manquement grave aux différentes obligations de celui-ci notamment le non-respect de la discipline de l\'entreprise\n. De retards répétés, d\'absences répétées et/ou injustifiées ou encore de maladies non justifiées.\n\nLa non-poursuite de la formation quel que soit la raison ne donne pas droit au paiement par la société de quelconque indemnité ni préavis au stagiaire.',
        },
        {
            title: 'CLAUSE DE COMPÉTENCE',
            contents: 'Les parties contractantes élisent domicile à Abidjan et conviennent que tout conflit sera porté devant les juridictions compétentes.',
        }
    ]
};

const form = ref({
    contract_type: '',
    title: '',
    description: '',
    effective_date: '',
    expiration_date: '',
    parties: [],
    assigned_to: '',
    articles: [],
    company_name: 'AMOAMAN & ASSOCIÉS',
    company_address: 'Cocody Riviera 3, Abidjan',
    company_rcs: 'CI-ABJ-03-2018-B12-33468',
    legal_representative: 'Monsieur AMOAKON EL HADJI DIHYE YORO en qualité de fondateur directeur',
    contract_duration: '3',
    internship_supervisor: '',
    salary: '150000',
    work_location: 'Cocody Riviera 3-Abidjan',
    hr_representative: 'Natacha KAKOU',
    hr_position: 'Responsable Ressources Humaines',
    hr_contact: 'hello@amoaman.com | www.amoaman.com',
    signature: null,
    signature_date : null,
    signatureRH : null,
    signatureRH_date : null,
});

watch(() => form.value.contract_type, (newType) => {
    if (contractTemplates[newType]) {
        form.value.articles = JSON.parse(JSON.stringify(contractTemplates[newType]));
    }
});

const stepProgress = computed(() => ({
    width: `${(currentStep.value / totalSteps) * 100}%`,
}));

const validateStep = () => {
    formErrors.value = {};

    if (currentStep.value === 1) {
        if (!form.value.contract_type.trim()) formErrors.value.contract_type = 'Le type de contrat est requis';
        if (!form.value.effective_date) formErrors.value.effective_date = 'La date d\'effet est requise';
        if (!form.value.expiration_date) formErrors.value.expiration_date = 'La date d\'expiration est requise';
        if (new Date(form.value.expiration_date) <= new Date(form.value.effective_date)) {
            formErrors.value.dates = 'La date d\'expiration doit être postérieure à la date d\'effet';
        }

        if(form.value.contract_type =='CONTRAT DE STAGE DE QUALIFICATION PROFESSIONNELLE' && !form.value.internship_supervisor){
            formErrors.value.internship_supervisor = 'vous devez assigner un maitre de stage au  stagiaire';
        }
    }

    if (currentStep.value === 2) {
        form.value.articles.forEach((article, index) => {
            if (!article.title.trim()) formErrors.value[`article_${index}_title`] = 'Le titre de l\'article est requis';
            if(!article.contents.trim()) formErrors.value[`article_${index}_contents`] = 'Le contenu de l\'article est requis';
        });
    }

    if (currentStep.value === 3) {
        if (!form.value.assigned_to) formErrors.value.assigned_to = 'L\'employé assigné est requis';
    }

    return Object.keys(formErrors.value).length === 0;
};

const nextStep = () => {
    console.log('form.value ', form.value)
    if (validateStep() && currentStep.value === 2) {



        // Calcul de la durée du contrat
        const startDate = new Date(form.value.effective_date);
        const endDate = new Date(form.value.expiration_date);
        let months = (endDate.getFullYear() - startDate.getFullYear()) * 12 + (endDate.getMonth() - startDate.getMonth());

        if (endDate.getDate() < startDate.getDate()) {
            months -= 1;
        }

        let durationString = "";
        if (months >= 12 && months % 12 === 0) {
            durationString = `${months / 12} an(s)`;
        } else {
            durationString = `${months} mois`;
        }

        form.value.contract_duration = months;

        const supervisor = props.users.find((user)=> user.id == form.value.internship_supervisor)
        form.value.supervisor_position = supervisor.poste
        console.log(supervisor)
        employee = props.users.find((user)=> user.id == form.value.assigned_to)


        // Génération des données traitées avec HTML pour mise en gras
        processedData = {
            ...form.value,
            articles: form.value.articles.map(article => ({
                ...article,
                contents: article.contents
                    .replace('[NOM_ENTREPRISE]', `<span class="font-semibold opacity-90">${form.value.company_name}</span>`)
                    .replace('[NOM]', `<span class="font-semibold opacity-90">${employee.last_name} ${employee.first_name}</span>`)
                    .replace('[POSTE]', `<span class="font-semibold opacity-90">${employee.poste}</span>`)
                    .replace('[DUREE]', `<span class="font-semibold opacity-90">${durationString}</span>`)
                    .replace('[DATE_DEBUT]', `<span class="font-semibold opacity-90">${form.value.effective_date}</span>`)
                    .replace('[DATE_FIN]', `<span class="font-semibold opacity-90">${form.value.expiration_date}</span>`)
                    .replace('[SUPERVISEUR]', `<span class="font-semibold opacity-90">${supervisor.first_name} ${supervisor.last_name}</span>`)
                    .replace('[POSTE_SUPERVISEUR]', `<span class="font-semibold opacity-90">${form.value.supervisor_position}</span>`)
                    .replace('[SALAIRE]', `<span class="font-semibold opacity-90">${form.value.salary}</span>`)
                    .replace('[LIEU]', `<span class="font-semibold opacity-90">${form.value.work_location}</span>`)
            }))
        };

    }

    if (validateStep()) {
        currentStep.value = Math.min(currentStep.value + 1, totalSteps);
    }
};




const prevStep = () => {
    currentStep.value = Math.max(currentStep.value - 1, 1);
};

const addArticle = () => {
    form.value.articles.push({ title: '', contents: '' });
};

const removeArticle = (index) => {
    form.value.articles.splice(index, 1);
};



const submit = () => {
    if (!validateStep()) return;





    router.post('/contracts', processedData, {
        onSuccess: () => {
            form.value = {
                contract_type: '',
                title: '',
                description: '',
                effective_date: '',
                expiration_date: '',
                contract_date: '',
                assigned_to: null,
                company_name: 'AMOAMAN & ASSOCIÉS',
                company_address: 'Cocody Riviera 3, Abidjan',
                company_rcs: 'CI-ABJ-03-2018-B12-33468',
                legal_representative: 'Monsieur AMOAKON EL HADJI DIHYE YORO en qualité de fondateur directeur',
                contract_duration: '3',
                internship_supervisor: '',
                salary: '150000',
                work_location: 'Cocody Riviera 3-Abidjan',
                hr_representative: 'Natacha KAKOU',
                hr_position: 'Responsable Ressources Humaines',
                hr_contact: 'hello@amoaman.com | www.amoaman.com'
            };
            currentStep.value = 1;
        },
    });
};

const today = computed(() => {
    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    return `${day}/${month}/${year}`;
});
</script>

<template>
    <FrontendContent>
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800">Créer un nouveau contrat</h1>
                    <div class="mt-6 relative pt-4">
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 transform-translate-y-1/2">
                            <div class="h-full bg-blue-600 transition-all duration-300" :style="stepProgress"></div>
                        </div>
                        <div class="flex justify-between">
                            <div v-for="step in totalSteps" :key="step" class="relative z-10 mb-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold"
                                    :class="currentStep >= step ? 'bg-blue-600' : 'bg-gray-300'">
                                    {{ step }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-6">
                    <!-- Étape 1 -->
                    <div v-show="currentStep === 1" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Type de contrat</label>
                                <select v-model="form.contract_type"
                                    :class="{ 'border-red-500': formErrors.contract_type }"
                                    class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    <option value="">Sélectionner un type</option>
                                    <option value="CONTRAT DE STAGE DE QUALIFICATION PROFESSIONNELLE">Contrat de Stage</option>
                                    <option value="CDI">CDI</option>
                                    <option value="CDD">CDD</option>
                                </select>
                                <p v-if="formErrors.contract_type" class="text-red-500 text-sm mt-1">{{ formErrors.contract_type }}</p>
                            </div>

                            <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-blue-800 mb-3">Informations entreprise</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'entreprise</label>
                                        <input v-model="form.company_name" type="text" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                        <input v-model="form.company_address" type="text" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">RCS</label>
                                        <input v-model="form.company_rcs" type="text" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Représentant légal</label>
                                        <input v-model="form.legal_representative" type="text" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-800 mb-3">Informations stagiaire</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50  rounded-lg">
                                            <h3 class="text-sm font-medium text-gray-700 mb-1">Assigner le contrat</h3>
                                            <Multiselect
                                                v-model="form.assigned_to"
                                                :options="users.map(e => ({
                                                    value: e.id,
                                                    label: `${e.first_name} ${e.last_name}`,
                                                    image: e.profile_link
                                                }))"
                                                placeholder="Sélectionner un employé"
                                                label="label"
                                                track-by="value"
                                                :classes="{
                                                    container: formErrors.assigned_to ? 'multiselect border border-red-500' : 'multiselect'
                                                }"
                                            >
                                                <template #option="{ option }">
                                                    <div class="flex items-center gap-3">
                                                        <img v-if="option.image" :src="`/storage/${option.image}`" class="w-8 h-8 rounded-full">
                                                        <div v-else class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                            {{ option.label.charAt(0) }}
                                                        </div>
                                                        <span>{{ option.label }}</span>
                                                    </div>
                                                </template>
                                            </Multiselect>
                                            <p v-if="formErrors.assigned_to" class="text-red-500 text-sm mt-2">{{ formErrors.assigned_to }}</p>
                                        </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Salaire</label>
                                        <input v-model="form.salary" type="text" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-lg" v-if="form.contract_type == 'CONTRAT DE STAGE DE QUALIFICATION PROFESSIONNELLE'">
                                        <h3 class="text-sm font-medium text-gray-700 mb-4">Assigner le  superviseur</h3>
                                        <Multiselect
                                            v-model="form.internship_supervisor"
                                            :options="users.map(e => ({
                                                value: e.id,
                                                label: `${e.first_name} ${e.last_name}`,
                                                image: e.profile_link
                                            }))"
                                            placeholder="Sélectionner un employé"
                                            label="label"
                                            track-by="value"
                                            :classes="{
                                                container: formErrors.internship_supervisor ? 'multiselect border border-red-500' : 'multiselect'
                                            }"
                                        >
                                            <template #option="{ option }">
                                                <div class="flex items-center gap-3">
                                                    <img v-if="option.image" :src="`/storage/${option.image}`" class="w-8 h-8 rounded-full">
                                                    <div v-else class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                        {{ option.label.charAt(0) }}
                                                    </div>
                                                    <span>{{ option.label }}</span>
                                                </div>
                                            </template>
                                        </Multiselect>
                                        <p v-if="formErrors.internship_supervisor" class="text-red-500 text-sm mt-2">{{ formErrors.internship_supervisor }}</p>
                                    </div>

                                </div>
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">Date d'effet</label>
                                    <input v-model="form.effective_date" type="date"
                                        :class="{ 'border-red-500': formErrors.effective_date }"
                                        class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    <p v-if="formErrors.effective_date" class="text-red-500 text-sm mt-1">{{ formErrors.effective_date }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">Date d'expiration</label>
                                    <input v-model="form.expiration_date" type="date"
                                        :class="{ 'border-red-500': formErrors.expiration_date }"
                                        class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                    <p v-if="formErrors.expiration_date" class="text-red-500 text-sm mt-1">{{ formErrors.expiration_date }}</p>
                                </div>
                            </div>
                            <p v-if="formErrors.dates" class="text-red-500 text-sm md:col-span-2">{{ formErrors.dates }}</p>
                        </div>
                    </div>

                    <!-- Étape 2 -->
                    <div v-show="currentStep === 2" class="space-y-6">
                        <div v-for="(article, articleIndex) in form.articles" :key="articleIndex" class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-medium">Article {{ articleIndex + 1 }}</h3>
                                <button @click="removeArticle(articleIndex)" type="button" class="text-red-500 hover:text-red-700">
                                    <XCircle class="w-5 h-5" />
                                </button>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Titre de l'article</label>
                                <input v-model="article.title" type="text"
                                    :class="{ 'border-red-500': formErrors[`article_${articleIndex}_title`] }"
                                    class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                <p v-if="formErrors[`article_${articleIndex}_title`]" class="text-red-500 text-sm mt-1">
                                    {{ formErrors[`article_${articleIndex}_title`] }}
                                </p>
                            </div>



                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Contenu</label>
                                <textarea v-model="article.contents" rows="4"
                                    :class="{ 'border-red-500': formErrors[`article_${articleIndex}_contents`] }"
                                    class="w-full rounded-md border-gray-300 shadow-sm p-2 border"></textarea>
                                <p v-if="formErrors[`article_${articleIndex}_contents`]" class="text-red-500 text-sm mt-1">
                                    {{ formErrors[`article_${articleIndex}_contents`] }}
                                </p>
                            </div>
                        </div>

                        <button @click="addArticle" type="button"
                            class="w-full py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg flex items-center justify-center gap-2">
                            <Plus class="w-5 h-5" /> Ajouter un article
                        </button>
                    </div>

                    <!-- Étape 3 -->
                    <div v-show="currentStep === 3" class="space-y-6">
                        <div class="grid grid-cols-1  gap-6">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div
                                        v-if="$page.props.flash.success"
                                        class="text-white bg-green-600 w-full text-center p-2 rounded mb-4"
                                    >
                                        {{ $page.props.flash.success }}
                                    </div>
                                    <div
                                        v-if="$page.props.flash.message"
                                        class="text-white bg-red-600 w-full text-center p-2 rounded mb-4"
                                    >
                                        {{ $page.props.flash.message }}
                                    </div>
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Récapitulatif</h3>
                                <div class="space-y-4 text-sm">
                                    <div class="flex justify-start items-start px-6">
                                        <div class="flex justify-center items-center">
                                            <img src="/public/logos/LogoAMOAMANnew.webp" alt="logo amoaman" class="size-20 object-cover">
                                        </div>
                                    </div>
                                    <div class="flex justify-center items-center px-16 text-center">
                                        <h1 class=" font-bold text-2xl opacity-90">{{ processedData?.contract_type }}</h1>
                                    </div>
                                    <div class="space-y-2">
                                        <p class=" opacity-90">Entre les soussignés,</p>
                                        <div class="ml-4 mt-2 space-y-2 opacity-90">
                                           <p>La société <span class=" font-semibold">AMOAMAN & ASSOCIES</span>, au capital de <span class="font-semibold">1.000.000 FCFA</span>  dont le siège social est à Cocody Riviera 3, immatriculée au Registre du commerce et des sociétés d'Abidjan, sous le numéro Cl-ABJ-03-2018-B12-33468. Le représentant légal est Monsieur <span class=" font-semibold">AMOAKON EL HADJI DIHYE YORO</span>  en qualité de fondateur directeur.</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2 flex flex-col opacity-90">
                                        <span class=" self-end font-semibold px-10">D&#39; une part</span>
                                        <div class="ml-4 mt-2 space-y-2 ">
                                           <table class="table w-full">
                                            <tbody>
                                                <tr>
                                                    <td>Nom</td>
                                                    <td>:{{ employee?.first_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Prénoms</td>
                                                    <td>:{{ employee?.last_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Date et lieu de naissance </td>
                                                    <td>:{{ employee?.birth_date }} à {{ employee?.birth_place }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Nationnalité</td>
                                                    <td>:{{ employee?.nationality }} </td>
                                                </tr>
                                                <tr>
                                                    <td>Situation matrimoniale</td>
                                                    <td>:{{ employee?.marital_status }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Lieu de résidence</td>
                                                    <td>:{{ employee?.address }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Contact téléphonique</td>
                                                    <td>:{{ employee?.phone_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Fonction</td>
                                                    <td>:{{ employee?.poste }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <span class=" self-end font-semibold px-10">D&#39; autre part</span>

                                    </div>
                                    <div class="space-y-2 flex justify-center items-center">
                                        <h4 class="font-semibold opacity-90 underline">IL A ETE CONVENU CE QUI SUIT :</h4>
                                    </div>
                                    <div class="space-y-2">
                                        <div v-for="(article, index) in processedData?.articles" :key="index" class="ml-4 mt-2 space-y-2 opacity-90">
                                            <p class="font-semibold underline py-2">Article {{ index + 1 }}: {{ article.title }}</p>
                                            <p class="whitespace-pre-line" v-html="article.contents"></p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end flex-col items-end p-4 space-y-2">
                                        <p>Fait en deux exemplaires, à Abidjan le {{ today }}</p>
                                        <p>(Signatures des parties précédées de la mention « lu et approuvé »)</p>
                                    </div>
                                    <div class="flex justify-between  items-center px-4">
                                       <div class="flex flex-col">
                                            <span class="font-semibold underline">STAGIAIRE</span>
                                            <span class="font-semibold text-xs">{{ employee?.first_name }} {{ employee?.last_name }}</span>
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="font-semibold underline">AMOAMAN & ASSOCIES</span>
                                            <span class="font-semibold text-xs">Natacha KAKOU</span>
                                            <span class="font-semibold text-xs">Responsable Ressources Humaines</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between  items-center px-4">
                                       <div class="flex flex-col">

                                        </div>

                                        <div class="flex flex-col justify-center items-center">
                                           <img src="/public/icons/tampom.svg" alt="tampom" class="size-28 object-cover">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-6 border-t border-gray-200">
                        <button @click="prevStep" type="button" v-show="currentStep > 1"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                            ← Précédent
                        </button>
                        <button @click="nextStep" type="button" v-show="currentStep < totalSteps"
                                class="ml-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Suivant →
                        </button>
                        <button type="submit" v-show="currentStep === totalSteps"
                                class="ml-auto px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium">
                            Générer le contrat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </FrontendContent>
</template>

<style scoped>
.multiselect-container {
    min-height: 44px;
}

.required:after {
    content: " *";
    color: red;
}

.bg-blue-50 {
    background-color: #eff6ff;
}

.bg-gray-50 {
    background-color: #f9fafb;
}
</style>
