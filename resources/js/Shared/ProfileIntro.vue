<template>
    <div class="expert-row expert-detail__title">
        <div class="expert-detail__image" v-if="profile.image">
            <img :src="profile.image" />
        </div>
        <div class="expert-detail__name">
            <h1 class="expert-name h2" v-if="profile.company_name">
                {{ profile.company_name }}
            </h1>
            <p v-if="profile.expert_type.name" class="expert-tagline">
                {{ profile.expert_type.name }}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            profile: Object
        }
    }
</script>
