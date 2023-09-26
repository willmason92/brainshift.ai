<template>
    <h3 class="h2">
        {{ $t('Materials') }}
    </h3>

    <div class="project-overview card">
        <div v-for="material in materials" :key="material" class="material">
            <div v-if="material.file.url" class="material__image">
                <img :src="material.file.url" :alt="material.name" />
            </div>
            <div class="material__name">
                {{ material.name }}<br />
                {{ material.colour.name }}
            </div>
            <a
                :href="material.shop_url"
                class="material__button button button--thin button-reset"
                target="_blank"
            >
                {{ $t('Request a Sample') }}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        materials: Array,
    },
};
</script>
