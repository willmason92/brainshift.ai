<template>
    <div>
        <Popup :active="contactPopupActive" @active-state="closePopup">
            <template #backButton v-show="initialStep">
                <p class="h4">
                    {{ $t(`Contact ${expertType}`) }}
                </p>
            </template>

            <template #backButton v-if="step > 0">
                <button
                    type="button"
                    class="popup__back button-reset"
                    @click="back()"
                >
                    {{ $t('Back') }}
                </button>
            </template>

            <form>
                <ProjectOption
                    v-show="initialStep"
                    :expert="expert"
                    @closePopup="closePopup"
                    @valueChanged="changeProjectOption"
                />

                <AttachAProject
                    v-show="attachAProjectStep_1"
                    :expert="expert"
                    :projects="currentProjects"
                    @closePopup="closePopup"
                    @continueToContact="continueToContact"
                />

                <div v-show="noAttachAProjectStep_1">
                    <p class="h4">{{ $t(`Contact ${expertType}`) }}</p>
                    <h1 class="h2 m-top--1 expert-contact__title">
                        {{ $t('We need some information about your project') }}
                    </h1>
                    <p class="m-top--4 f-body-2 expert-contact__explanation">
                        {{
                            $t(
                                'To better inform the installer on why you would like to contact him we ask you to fill in a few questions'
                            )
                        }}
                    </p>
                    <br />
                    <p class="f-body-2 expert-contact__explanation">
                        {{ $t("Don't worry it won't take long!") }}
                    </p>

                    <ContinueButton
                        :isFixed="true"
                        :isEnabled="true"
                        :text="$t('Ok, got it!')"
                        @continue="continueToContact"
                    />
                </div>

                <div v-show="noAttachAProjectStep_2">
                    <p class="h4 expert-contact__subtitle">
                        {{ $t(`Contact ${expertType}`) }}
                    </p>
                    <h2 class="h2 expert-contact__title">
                        {{ $t("What's your project about?") }}
                    </h2>

                    <SelectSingle
                        :options="[
                            {
                                id: 0,
                                name: $t(
                                    'I am looking for a new  agricultural building'
                                ),
                            },
                            {
                                id: 1,
                                name: $t(
                                    'I am refurbishing an existing building'
                                ),
                            },
                        ]"
                        inputName="project_type"
                        :error="error"
                        @valueChanged="valueChanged"
                    />

                    <ContinueButton
                        :isEnabled="this.form.project_type != null"
                        :text="$t('Continue')"
                        @continue="continueToContact"
                    />
                </div>

                <div v-show="noAttachAProjectStep_3">
                    <p class="h4 expert-contact__subtitle">
                        {{ $t(`Contact ${expertType}`) }}
                    </p>
                    <h2 class="h2 expert-contact__title">
                        {{ $t("What's your sector?") }}
                    </h2>

                    <SelectSingle
                        :options="sectors"
                        inputName="sector"
                        :error="error"
                        @valueChanged="changeSector"
                    />

                    <ContinueButton
                        :isEnabled="this.form.sector"
                        :text="$t('Continue')"
                        @continue="continueToContact"
                    />
                </div>

                <SubmitProjectInfo
                    v-if="attachAProjectStep_2 || noAttachAProjectStep_4"
                    :expert="expert"
                    :projectText="projectText"
                    :sectorText="sectorText"
                    :isAttached="isAttached"
                    :form="form"
                    @editProjectType="editProjectType"
                    @editSector="editSector"
                    @valueChanged="valueChanged"
                    @submitRequest="submitRequest"
                />
            </form>
        </Popup>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import Popup from '../../../Shared/Popup.vue';
import ProjectOption from './ProjectOption.vue';
import AttachAProject from './AttachAProject.vue';
import SubmitProjectInfo from './SubmitProjectInfo.vue';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import SelectSingle from '../../../Shared/Inputs/SelectSingle.vue';
import SelectSector from '../../../Shared/Inputs/SelectSector.vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
    setup(props) {
        const form = useForm({
            project: null,
            project_type: null,
            sector: null,
            message: null,
            phone: usePage().props.value.defaultPhone,
            email: usePage().props.value.defaultEmail,
            permission: null,
        });
        return { form };
    },
    emits: ['closePopup'],
    beforeMount() {
        this.sectors.forEach((sector, i) => {
            sector.image =
                require(`../../../../images/sector-icons/icon-${sector.name.toLowerCase()}.svg`).default;
        });
    },
    components: {
        Link,
        Popup,
        ProjectOption,
        AttachAProject,
        SubmitProjectInfo,
        ContinueButton,
        SelectSingle,
        SelectSector,
    },
    props: {
        expert: Object,
        contactPopupActive: Boolean,
        sectors: Array,
        currentProjects: Array,
    },
    data() {
        return {
            isAttached: null,
            step: 0,
            project: null,
            sectorText: null,
            projectTitle: null,
        };
    },
    methods: {
        valueChanged(change) {
            this.form[change.input] = change.value;
        },
        changeProjectOption(change) {
            this.isAttached = change.value;
            if (!this.isAttached) this.project = null;
            this.step++;
        },
        closePopup() {
            this.isAttached = false;
            this.step = 0;
            this.$emit('closePopup');
        },

        openPopup(event) {
            event.preventDefault();
            this.contactPopupActive = true;
        },

        contactOption(option) {
            this.isAttached = option.value;
            this.step += 1;
        },

        continueToContact(data) {
            this.step += 1;
            if (data && this.isAttached === true) {
                this.project = data;
                this.projectTitle = data.title;
            }
        },

        back() {
            this.step -= 1;
            if (this.step === 0) {
                this.isAttached = null;
                this.project = null;
            }
        },

        changeSector(selected) {
            // get the text from the selected option
            const selectedOption = this.sectors.find(
                (option) => option.id === selected.value
            );
            this.form.sector = selectedOption?.id;
            this.sectorText = selectedOption?.name;
        },

        editProjectType() {
            this.step = 2;
        },

        editSector() {
            this.step = 3;
        },

        submitRequest() {
            if (this.isAttached) {
                this.form.project = this.project.id;
            }

            Inertia.post(
                `/find-an-expert/${this.expert.id}/send-contact-requests`,
                this.form,
                {
                    onSuccess: () => {
                        this.closePopup();
                    },
                }
            );
        },
    },

    computed: {
        expertType() {
            return this.expert.expert_type.name || '';
        },

        initialStep() {
            return this.isAttached === null;
        },

        attachAProjectStep_1() {
            return this.isAttached && this.step === 1;
        },

        attachAProjectStep_2() {
            return this.isAttached && this.step === 2;
        },

        noAttachAProjectStep_1() {
            return !this.isAttached && this.step === 1;
        },

        noAttachAProjectStep_2() {
            return !this.isAttached && this.step === 2;
        },

        noAttachAProjectStep_3() {
            return !this.isAttached && this.step === 3;
        },

        noAttachAProjectStep_4() {
            return !this.isAttached && this.step === 4;
        },

        projectText() {
            if (this.isAttached) {
                return this.projectTitle || 'Unknown project';
            } else {
                return this.form.project_type == 0
                    ? 'New build'
                    : 'Refurbishment';
            }
        },
    },
};
</script>
