<script setup>
import KpiCard from '../Components/Stats/KpiCard.vue';
import BarChart from '../Components/Stats/charts/BarChart.vue';
import DoughnutChart from '../Components/Stats/charts/DoughnutChart.vue';
import PieChart from '../Components/Stats/charts/PieChart.vue';
import FrontendContent from '../dashboard/FrontendContent.vue';

defineProps({
    stats: Object,
    charts: Object,
});

// Fonction utilitaire pour le formatage des pourcentages
const formatProgress = (value, total) => {
    return total > 0 ? Math.round((value / total) * 100) : 0;
};
</script>

<template>
    <FrontendContent>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <h1 class="text-2xl font-bold text-gray-800">Tableau de bord global</h1>
                <div class="flex items-center gap-4 mt-4 md:mt-0">
                    <div class="text-sm text-gray-500">
                        Dernière mise à jour : {{ new Date().toLocaleDateString('fr-FR') }}
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6 px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Grille des indicateurs clés -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <KpiCard
                    title="Projets actifs"
                    :value="stats.totalProjects"
                    :subvalue="`${stats.completedProjects} terminés`"
                    icon="folder"
                    color="blue"
                    :progress="formatProgress(stats.completedProjects, stats.totalProjects)"
                />

                <KpiCard
                    title="Tâches totales"
                    :value="stats.totalTasks"
                    :subvalue="`${stats.completedTasks} terminées`"
                    icon="check-square"
                    color="green"
                    :progress="stats.taskCompletionRate"
                />

                <KpiCard
                    title="Utilisateurs actifs"
                    :value="stats.usersHasProjects"
                    :subvalue="`sur ${stats.totalUsers} total`"
                    icon="users"
                    color="purple"
                    :progress="formatProgress(stats.usersHasProjects, stats.totalUsers)"
                />

                <KpiCard
                    title="Taux de complétion"
                    :value="`${stats.taskCompletionRate}%`"
                    subvalue="Tâches terminées"
                    icon="percent"
                    color="teal"
                    :progress="stats.taskCompletionRate"
                />
            </div>

            <!-- Visualisations de données -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Répartition des projets</h2>
                    <PieChart
                        :labels="charts.projectStatus.labels"
                        :data="charts.projectStatus.data"
                        :colors="charts.projectStatus.colors"
                    />
                </div>

                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Statut des tâches</h2>
                    <DoughnutChart
                        :labels="charts.taskStatus.labels"
                        :data="charts.taskStatus.data"
                        :colors="charts.taskStatus.colors"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="bg-white p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Progression des tâches par projet (%)
                    </h2>
                    <BarChart
                        :labels="charts.taskProgressPerProject.labels"
                        :data="charts.taskProgressPerProject.data"
                        :colors="charts.taskProgressPerProject.colors"
                        :options="{
                            scales: {
                                y: {
                                    ticks: {
                                        callback: (value) => value + '%'
                                    }
                                }
                            }
                        }"
                    />
                </div>


            </div>
        </div>
    </FrontendContent>
</template>
