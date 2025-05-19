<script setup>
import FrontendContent from '../dashboard/frontendContent.vue';

</script>

<template>
     <FrontendContent>
        <div class="container mx-auto px-4 py-8 max-w-3xl">

        <!-- Edit Task Form (initially hidden) -->
     <div id="edit-form" class="form-section">
            <div class="form-card bg-white rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-edit text-blue_white mr-3"></i>
                    Modifier la Tâche
                </h2>
                
                <form id="task-edit-form" class="space-y-5">
                    <input type="hidden" id="edit-task-id" name="id">
                    
                    <div>
                        <label for="edit-task-title" class="block text-sm font-medium text-gray-700 mb-1 required-field">Titre</label>
                        <input type="text" id="edit-task-title" name="title" class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <div id="edit-title-error" class="error-message text-sm text-red-600 mt-1">Le titre est requis</div>
                    </div>
                    
                    <div>
                        <label for="edit-task-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit-task-description" name="description" rows="3" class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-task-priority" class="block text-sm font-medium text-gray-700 mb-1 required-field">Priorité</label>
                            <select id="edit-task-priority" name="priority" class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Sélectionner...</option>
                                <option value="high">Haute</option>
                                <option value="medium">Moyenne</option>
                                <option value="low">Basse</option>
                            </select>
                            <div id="edit-priority-error" class="error-message text-sm text-red-600 mt-1">La priorité est requise</div>
                        </div>
                        
                        <div>
                            <label for="edit-task-status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select id="edit-task-status" name="status" class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="todo">À faire</option>
                                <option value="inprogress">En cours</option>
                                <option value="done">Terminée</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-task-start-date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                            <input type="date" id="edit-task-start-date" name="startDate" class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="edit-task-due-date" class="block text-sm font-medium text-gray-700 mb-1 required-field">Date limite</label>
                            <input type="date" id="edit-task-due-date" name="dueDate" class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <div id="edit-due-date-error" class="error-message text-sm text-red-600 mt-1">La date limite est requise</div>
                        </div>
                    </div>
                    
                    <div id="edit-date-validation-error" class="error-message text-sm text-red-600 mt-1">
                        La date limite doit être postérieure à la date de début
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div>
                            <button type="button" id="delete-task" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                                <i class="fas fa-trash mr-1"></i> Supprimer la tâche
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" id="cancel-edit" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit" id="submit-edit" class="px-4 py-2 bg-blue_white text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="notification-area" class="fixed bottom-4 right-4 space-y-2"></div>

    </div>

     </FrontendContent>
</template>

<style>
 /* Custom styles */
 .form-card {
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        .form-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .error-message {
            opacity: 0;
            max-height: 0;
            transition: all 0.3s ease;
        }
        .error-message.show {
            opacity: 1;
            max-height: 20px;
        }
        .slide-fade-enter-active {
            transition: all 0.3s ease-out;
        }
        .slide-fade-leave-active {
            transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
        }
        .slide-fade-enter-from,
        .slide-fade-leave-to {
            transform: translateY(-10px);
            opacity: 0;
        }
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
</style>