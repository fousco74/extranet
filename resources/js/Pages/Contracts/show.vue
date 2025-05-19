<template>
    <frentendContent>
        <div class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Contrat RH</h1>
       <!-- Actions -->
       <div class="mt-8 pt-6  mb-2 border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-history mr-1"></i>
                    Dernière mise à jour: {{ contract.updated_at }}
                </div>
                <div class="flex space-x-3">
                    <button
                        @click="downloadPDF"
                        class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-download mr-2"></i>
                        Télécharger PDF
                    </button>
                    <button
                        @click="printContract"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 text-sm">
                        <i class="fas fa-print mr-1"></i>
                        Imprimer le contrat
                    </button>
                    <button class="px-4 py-2 border border-transparent rounded-md text-white bg-red-600 hover:bg-red-700 text-sm">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Archiver le contrat
                    </button>
                </div>
            </div>

            <!-- Carte du contrat -->
            <div
                ref="contractContent"
                class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <contractComponent :contract="contract" />
            </div>



        <!-- Zone de signature (uniquement si le contrat n'est pas signé) -->
        <div v-if="!contract.signe" class="bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Signature électronique</h2>

                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Canvas de signature -->
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-2">Dessinez votre signature</p>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg">
                            <canvas
                                ref="signaturePad"
                                class="w-full h-48 bg-gray-50"
                                @mousedown="startDrawing"
                                @mousemove="draw"
                                @mouseup="stopDrawing"
                                @mouseleave="stopDrawing"
                                @touchstart="startDrawing"
                                @touchmove="draw"
                                @touchend="stopDrawing">
                            </canvas>
                        </div>
                        <button
                            @click="clearSignature"
                            class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                            Effacer la signature
                        </button>
                    </div>

                    <!-- OU upload d'image -->
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-2">OU importez une image de signature</p>
                        <div class="flex items-center justify-center w-full">
                            <label for="file-upload" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">Cliquez pour téléverser</p>
                                    <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB)</p>
                                </div>
                                <input id="file-upload" type="file" class="hidden" accept="image/*" @change="handleFileUpload" />
                            </label>
                        </div>
                        <div v-if="signatureImage" class="mt-4 flex items-center">
                            <img :src="signatureImage" alt="Signature importée" class="h-16 border border-gray-200 rounded" />
                            <button @click="removeUploadedSignature" class="ml-2 text-sm text-red-600 hover:text-red-800">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="mt-6 flex justify-end">
                    <button
                        @click="submitSignature"
                        :disabled="!hasSignature"
                        :class="{'bg-blue-600 hover:bg-blue-700': hasSignature, 'bg-gray-400 cursor-not-allowed': !hasSignature}"
                        class="px-6 py-2 rounded-md text-white font-medium transition-colors">
                        Signer le contrat
                    </button>
                </div>
            </div>

            <!-- Message de confirmation après signature -->
            <div v-if="showSuccessMessage" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <div class="flex justify-center text-green-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-center text-gray-800 mb-2">Contrat signé avec succès!</h3>
                    <p class="text-gray-600 text-center mb-6">Votre signature a été enregistrée. Vous recevrez une copie par email.</p>
                    <div class="flex justify-center">
                        <button @click="showSuccessMessage = false" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
    </div>
    </frentendContent>
    </template>

    <script setup>
    import { ref, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
    import SignaturePad from 'signature_pad';
    import html2pdf from 'html2pdf.js';
    import frentendContent from '../dashboard/FrontendContent.vue'
    import contractComponent from '../components/contracts/contractComponent.vue';

    const props = defineProps(['contract']);

    const contractContent = ref(null);


    const signaturePad = ref(null);
    let signaturePadInstance = null;
    const signatureImage = ref(null);
    const hasSignature = ref(false);
    const showSuccessMessage = ref(false);
    const messageTitle = ref('Contrat signé avec succès!');
    const messageContent = ref('Votre signature a été enregistrée. Vous recevrez une copie par email.');

    onMounted(() => {
        const canvas = signaturePad.value;
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        signaturePadInstance = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 1,
            maxWidth: 3,
            throttle: 16
        });
    });

    const startDrawing = (event) => {
        if (event.type === 'touchstart') {
            event.preventDefault();
        }
    };

    const clearSignature = () => {
        signaturePadInstance.clear();
        signatureImage.value = null;
        hasSignature.value = false;
    };

    const stopDrawing = () => {
        if (signaturePadInstance.isEmpty()) {
            hasSignature.value = false;
        } else {
            hasSignature.value = true;
        }
    };



    const handleFileUpload = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert("Le fichier est trop volumineux (max 2MB)");
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            signatureImage.value = e.target.result;
            hasSignature.value = true;

            console.log(signatureImage.value)
        };
        reader.readAsDataURL(file);
    };

    const removeUploadedSignature = () => {
        signatureImage.value = null;
        hasSignature.value = false;
        document.getElementById('file-upload').value = '';
    };

    const submitSignature = async () => {
        if (!signaturePadInstance.isEmpty() || signatureImage.value) {
            const signatureData = signatureImage.value || signaturePadInstance.toDataURL();

            props.contract.value.signature = signatureData;
            props.contract.value.signe = true;


            router.post(route('contracts.sign'), {
                signature: signatureData,
                contract_id: props.contract.value.id
            },
            {
                onSuccess: () => {
                    clearSignature();
                    signatureImage.value = null;
                    showSuccessMessage.value = true;
                },
                onError: (error) => {
                    messageTitle.value = 'Erreur';
                    messageContent.value = 'Une erreur est survenue lors de la signature du contrat.';
                    showSuccessMessage.value = true;
                    console.error(error);
                }
            });


        }
    };


    const pdfOptions = {
    margin: 0,
    filename: `contrat-${props.contract.assigned_user.first_name}.pdf`,
    image: { type: 'jpeg', quality: 0.95 },
    html2canvas: {
        scale: 2,
        useCORS: true,
        letterRendering: true,
        allowTaint: true
    },
    jsPDF: {
        unit: 'mm',
        format: 'a4',
        orientation: 'portrait'
    },
    pagebreak: {
        mode: 'avoid-all',
        before: '.page-break-before',
        after: '.page-break-after',
        avoid: '.avoid-break'
    }
};

const downloadPDF = () => {
    const element = contractContent.value;

    // Configuration avancée pour le contenu
    const opt = {
        ...pdfOptions,
        html2canvas: {
            ...pdfOptions.html2canvas,
            onclone: (clonedDoc) => {
                // Ajoute des styles spécifiques pour l'impression
                clonedDoc.body.style.padding = '20px';
                clonedDoc.body.style.fontSize = '12pt';
            }
        }
    };

    html2pdf()
        .set(opt)
        .from(element)
        .save();
};

const printContract = () => {
    if (!contractContent.value) return;

    html2pdf()
        .set(pdfOptions)
        .from(contractContent.value)
        .toPdf()
        .get('pdf')
        .then(pdf => {
            const pdfWindow = window.open('');
            pdfWindow.document.write(`
                <html>
                    <head>
                        <title>${pdfOptions.filename}</title>
                    </head>
                    <body style="margin:0;">
                        <embed
                            width="100%"
                            height="100%"
                            src="${pdf.output('datauristring')}"
                            type="application/pdf"
                        >
                    </body>
                </html>
            `);
        });
};

    </script>

    <style>
    canvas {
        touch-action: none;
        background-color: #f8fafc;
    }

    .bg-blue-50 {
        background-color: #eff6ff;
    }

    .h-20 {
        height: 5rem;
    }

    @media print {
    /* Styles spécifiques pour l'impression PDF */
    .article-container {
        page-break-inside: avoid;
        break-inside: avoid-page;
        margin: 15px 0;
        padding: 10px;
    }

    .signature-section {
        page-break-before: always;
        margin-top: 20px;
    }

    .avoid-break {
        page-break-inside: avoid;
        break-inside: avoid-page;
    }
}

/* Styles généraux pour améliorer le flux de contenu */
.article-content {
    line-height: 1.6;
    text-align: justify;
    hyphens: auto;
}

.article-content p {
    margin-bottom: 12px;
}

.article-content ul,
.article-content ol {
    margin-left: 25px;
    margin-bottom: 15px;
}
    </style>
