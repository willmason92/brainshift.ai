<template>
    <div class="expert-column expert-detail__summary">
        <h3 class="expert-title h3">{{ $t('Summary') }}</h3>
        <div class="card">
            <div v-if="expertTags.length > 0">
                <h3 class="summary-subtitle f-title-2">{{ $t('Expertise') }}</h3>
                <div class="expert-detail__tags">
                    <span class="tag expert-tag" v-for="tag in expertTags">
                        {{ tag.name }}
                    </span>
                </div>
            </div>

            <div v-if="expertLocation">
                <h3 class="summary-subtitle f-title-2">{{ $t('Location') }}</h3>
                <div class="expert-detail__location">
                    <span class="location-icon">
                    <svg
                        width="17"
                        height="20"
                        viewBox="0 0 17 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M14.5318 2.24753C11.4076 -0.749177 6.34236 -0.749177 3.21819 2.24753C0.341753 5.0066 0.0818875 9.39694 2.61426 12.4506L8.875 20L15.1357 12.4506C17.6681 9.39694 17.4082 5.00661 14.5318 2.24753ZM8.875 10C10.3477 10 11.5417 8.80612 11.5417 7.33331C11.5417 5.86057 10.3477 4.66667 8.875 4.66667C7.40225 4.66667 6.20833 5.86057 6.20833 7.33331C6.20833 8.80612 7.40225 10 8.875 10Z"
                        fill="#E60000"
                        />
                    </svg>
                    </span>
                    <div class="expert-detail-location">
                    <p>
                        {{ expertLocation.name }}
                    </p>
                    <p>
                        {{ `${expertLocation.street}, ${expertLocation.city}, ${expertLocation.postcode}, ${expertLocation.country}` }}
                    </p>
                    </div>
                </div>
            </div>
            
            <div v-if="profile.description" class="expert-column expert-detail__about">
                <h2 class="expert-title">{{ $t('About') }}</h2>
                <p>
                    {{ profile.description }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            profile: Object,
            location: Object,
            tags: Array,
        },
        computed: {
            expertLocation() {
                return ( this.location ? this.location : this.profile.location );
            },
            expertTags() {
                const tags = []
                if (this.profile.sectors) {
                    this.profile.sectors.forEach(sector => {
                        tags.push(sector)
                    })
                }
                if (this.profile.specialities) {
                    this.profile.specialities.forEach(speciality => {
                        tags.push(speciality)
                    })
                }
                return tags
            }
        },
    }
</script>
