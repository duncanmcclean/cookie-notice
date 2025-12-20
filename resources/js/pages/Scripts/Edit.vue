<script setup>
import { Header, PublishContainer, Button } from '@statamic/cms/ui';
import { Pipeline, Request } from '@statamic/cms/save-pipeline';
import { ref, useTemplateRef, computed, nextTick, getCurrentInstance, onMounted, onBeforeUnmount } from 'vue';
import SiteSelector from "../../components/SiteSelector.vue";

const instance = getCurrentInstance();
const { $axios, $keys, $dirty, $toast } = instance.appContext.config.globalProperties;

const props = defineProps({
	icon: String,
	blueprint: Object,
	initialSite: String,
	initialValues: Object,
	initialMeta: Object,
	initialLocalizations: Array,
	action: String,
});

const site = ref(props.initialSite);
const values = ref(props.initialValues);
const meta = ref(props.initialMeta);
const localizations = ref(props.initialLocalizations);
const saving = ref(false);
const errors = ref({});
const localizing = ref(false);
const container = useTemplateRef('container');

let saveKeyBinding;

onMounted(() => {
	saveKeyBinding = $keys.bindGlobal(['mod+s'], (e) => {
		e.preventDefault();
		save();
	});
});

onBeforeUnmount(() => saveKeyBinding.destroy());

const isDirty = computed(() => $dirty.has('cookie-notice'));
const showLocalizationSelector = computed(() => localizations.value.length > 1);

function save() {
	new Pipeline()
        .provide({ container, errors, saving })
        .through([
	        new Request(props.action, 'PATCH', {
				site: site.value,
	        }),
        ])
        .then((response) => {
	        $toast.success(__('Saved'));
        });
}

function localizationSelected(localizationHandle) {
	let localization = localizations.value.find((localization) => localization.handle === localizationHandle);

	if (localization.active) return;

	if (isDirty.value) {
		pendingLocalization.value = localization;
		return;
	}

	switchToLocalization(localization);
}

function switchToLocalization(localization) {
	localizing.value = localization.handle;

	window.history.replaceState({}, '', localization.url);

	$axios.get(localization.url).then((response) => {
		const data = response.data;
		values.value = data.initialValues;
		meta.value = data.initialMeta;
		localizations.value = data.initialLocalizations;
		site.value = localization.handle;
		localizing.value = false;
		nextTick(() => container.value.clearDirtyState());
	});
}
</script>

<template>
	<div class="max-w-5xl mx-auto">
		<Header :title="__('Cookie Notice')" :icon>
			<SiteSelector
				v-if="showLocalizationSelector"
				:sites="localizations"
				:model-value="site"
				@update:modelValue="localizationSelected"
			/>

			<Button :text="__('Save')" :disabled="saving" variant="primary" @click="save" />
		</Header>

		<PublishContainer
			ref="container"
			name="cookie-notice"
			as-config
			v-model="values"
			:blueprint
			:meta
			:errors
		/>
	</div>
</template>