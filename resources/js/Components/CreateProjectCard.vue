<template>
    <Link href="/shed-solution" class="create-project-card" :class="{'is-red': noProjects}">
        <div v-if="noProjects">
            <p>
                {{ $t('Oops, it looks like you haven\'t created a project yet!') }}
            </p>
            <p class="create-project-card__link">
                {{ $t('Create a project') }}
            </p>
        </div>
        <div v-else>
            <p>
                {{ $t('Start new project') }}
            </p>
        </div>
    </Link>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link,
    },
    props: {
        noProjects: Boolean
    }
}
</script>
