<template>
    <h3 class="h2">{{ $t('Project Summary') }}</h3>
    <div class="project-overview card">
        <div v-if="post.type_string" class="project-summary__row">
            <div class="project-summary__cell project-summary__cell--title">
                {{ $t('Project type') }}
            </div>
            <div class="project-summary__cell">
                {{ post.type_string }}
            </div>
        </div>
        <div v-if="post.sector" class="project-summary__row">
            <div class="project-summary__cell project-summary__cell--title">
                {{ $t('Sector') }}
            </div>
            <div class="project-summary__cell">
                {{ post.sector.name }}
            </div>
        </div>
        <div v-if="post.location" class="project-summary__row">
            <div class="project-summary__cell project-summary__cell--title">
                {{ $t('Location') }}
            </div>
            <div class="project-summary__cell">
                <p>{{ post?.location?.street }}</p>
                <p>{{ post?.location?.city }}</p>
                <p>{{ post?.location?.postcode }}</p>
                <p>{{ post?.location?.country }}</p>
            </div>
        </div>
        <div
            v-if="post.length && post.width && post.size_unit"
            class="project-summary__row"
        >
            <div class="project-summary__cell project-summary__cell--title">
                {{ $t('Size') }}
            </div>
            <div class="project-summary__cell">
                {{ post.length }} x {{ post.width }}
                {{ post.size_unit == 0 ? 'meters' : 'feet' }}
            </div>
        </div>
        <div v-if="post.completed_on" class="project-summary__row">
            <div class="project-summary__cell project-summary__cell--title">
                {{ $t('Project Completed') }}
            </div>
            <div class="project-summary__cell">
                {{ moment(post.completed_on).format('DD/MM/YYYY') }}
            </div>
        </div>
    </div>
    <Materials v-if="post.materials" :materials="post.materials" />
</template>

<script>
import Gallery from '../Components/Gallery';
import Materials from '../Components/Materials';
import moment from 'moment';

export default {
    components: {
        Materials,
    },
    props: {
        post: Object,
    },
    data() {
        return {
            moment: moment,
        };
    },
};
</script>
