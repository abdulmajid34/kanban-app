<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    workspace: Object,
    projects: Array,
});

const form = useForm({
    name: '',
});

const submitProject = () => {
    // Post ke route project store dengan parameter slug workspace
    form.post(route('projects.store', props.workspace.slug), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head :title="workspace.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <span class="text-gray-400">Workspace /</span> {{ workspace.name }}
                </h2>
                <Link :href="route('dashboard')" class="text-sm text-gray-600 hover:text-gray-900">Back to Dashboard</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white p-6 rounded-lg shadow-sm mb-8 border border-gray-100">
                    <h3 class="text-lg font-medium mb-4">Create New Project</h3>
                    <form @submit.prevent="submitProject" class="flex gap-4">
                        <input v-model="form.name" type="text" placeholder="Project Name (e.g. Mobile App Redesign)" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                            Create Project
                        </button>
                    </form>
                </div>

                <h3 class="text-xl font-bold mb-4 text-gray-800">Your Projects</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link 
                        v-for="project in projects" 
                        :key="project.id"
                        :href="route('projects.show', [workspace.slug, project.slug])"
                        class="block bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition duration-200 group"
                    >
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-indigo-100 p-3 rounded-lg group-hover:bg-indigo-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">{{ project.name }}</h4>
                        <p class="text-sm text-gray-500">Click to open Kanban Board</p>
                    </Link>

                    <div v-if="projects.length === 0" class="col-span-full text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-gray-500">No projects yet. Start by creating one above!</p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>