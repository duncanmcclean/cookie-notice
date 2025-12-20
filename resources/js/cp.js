import ScriptsEdit from './pages/Scripts/Edit.vue';

Statamic.booting(() => {
    Statamic.$inertia.register('cookie-notice::Scripts/Edit', ScriptsEdit);
});