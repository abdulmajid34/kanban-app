<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Props dari WorkspaceController
const props = defineProps({
    workspaces: Array,
});

// Form untuk membuat workspace baru
const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('workspaces.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Workspaces</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                    <h3 class="text-lg font-medium mb-4">Create New Workspace</h3>
                    <form @submit.prevent="submit" class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Workspace Name</label>
                            <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="e.g. Kantor Pusat">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                            <input v-model="form.description" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                            Create
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="workspace in workspaces" :key="workspace.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ workspace.name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ workspace.description || 'No description' }}</p>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ workspace.pivot.role }}
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <Link :href="route('workspaces.show', workspace.slug)" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Open Workspace &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="workspaces.length === 0" class="col-span-3 text-center py-10 text-gray-500">
                        You don't have any workspaces yet. Create one above!
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>