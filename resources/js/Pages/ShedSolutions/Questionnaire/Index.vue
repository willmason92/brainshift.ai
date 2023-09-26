<template>
    <layout-default>
        <form>
            <div v-show="!hasStarted && !hasFinished">
                <button
                    class="popup__back popup__back--margin button-reset"
                    type="button"
                    @click="goBack()"
                >
                    {{ $t('Back') }}
                </button>
                <h2 class="h4">
                    {{ $t('Your new agricultural building') }}
                </h2>
                <h1 class="shed-solutions-initial-title h2">
                    {{ $t('What is your project about?') }}
                </h1>

                <SelectSingle
                    inputName="formPath"
                    :options="formPaths"
                    @valueChanged="formPathChanged"
                ></SelectSingle>

                <ContinueButton
                    :text="$t('Continue')"
                    :isEnabled="this.isFormPathSelected"
                    @continue="initForm"
                />
            </div>

            <div v-show="hasStarted">
                <button
                    type="button"
                    class="popup__back button-reset m-bot--2"
                    @click="
                        this.formValues.formPath = null;
                        this.hasStarted = false;
                    "
                >
                    {{ $t('Back') }}
                </button>

                <h1 class="page-title h1">
                    {{ $t('Your new agricultural building') }}
                </h1>
                <p class="form-description m-bot--2 f-body-1">
                    {{ $t('Bring your project to life in a few simple steps') }}
                </p>

                <div class="shed-solutions" ref="shed-solutions">
                    <div class="shed-solutions__path">
                        <svg class="shed-solutions-path">
                            <line
                                stroke-width="1px"
                                stroke="#CCC5B8;"
                                x1="50%"
                                y1="0"
                                x2="50%"
                                y2="0"
                                ref="shed-solutions-path"
                            />
                        </svg>
                    </div>

                    <div class="shed-solutions__sections">
                        <div class="shed-solution">
                            <div class="shed-solution__stage">
                                <div
                                    class="shed-solution__dot active"
                                    ref="shed-solutions-stage-1"
                                ></div>
                                <div class="shed-solution__line start"></div>
                            </div>

                            <div class="shed-solution__section card">
                                <p
                                    class="shed-solution-card__subtitle f-title-2"
                                >
                                    {{ $t('YOUR PROJECT') }}
                                </p>
                                <h2 class="f-body-2">
                                    {{ $t('Tell us about your project') }}
                                </h2>
                            </div>
                        </div>

                        <div
                            class="shed-solution"
                            v-for="(step, index) in steps[
                                formValues.formPath == 0
                                    ? 'newBuild'
                                    : 'refurbishment'
                            ]"
                        >
                            <div class="shed-solution__stage">
                                <div
                                    class="shed-solution__dot"
                                    :class="isPositionActive(index + 1)"
                                    :ref="'shed-solutions-stage-' + (index + 2)"
                                ></div>
                                <div class="shed-solution__line"></div>
                            </div>

                            <div class="shed-solution__section card">
                                <p
                                    class="shed-solution-card__subtitle f-title-2"
                                >
                                    {{ step.subtitle }}
                                </p>
                                <h2 class="f-body-2">
                                    {{ step.title }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <button
                    type="button"
                    class="button button--fill button--span"
                    @click="openPopup()"
                >
                    {{ $t('Start') }}
                </button>

                <Popup :active="popupActive" @active-state="closePopup">
                    <template #default>
                        <div v-show="stage == 0"></div>
                        <div v-show="stage != 0">
                            <div v-if="formValues.formPath == 0">
                                <Sector
                                    v-show="stage == 1"
                                    :errors="errorSections.sector"
                                    @clicked="clicked"
                                    :sectors="sectors"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></Sector>

                                <Size
                                    :errors="errorSections.size"
                                    v-show="stage == 2"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></Size>

                                <Goals
                                    :errors="errorSections.goals"
                                    v-show="stage == 3"
                                    :goals="goals"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></Goals>
                            </div>
                            <div v-if="formValues.formPath == 1">
                                <Sector
                                    :errors="errorSections.sector"
                                    v-show="stage == 1"
                                    @clicked="clicked"
                                    :filterTitle="
                                        $t('Choose the sector you\'re in')
                                    "
                                    :sectors="sectors"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></Sector>

                                <BuildingType
                                    :errors="errorSections.typeOfBuilding"
                                    v-show="stage == 2"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></BuildingType>

                                <BuildingAge
                                    :errors="errorSections.buildingAge"
                                    v-show="stage == 3"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></BuildingAge>

                                <Size
                                    :errors="errorSections.size"
                                    v-show="stage == 4"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></Size>

                                <Reasons
                                    :errors="errorSections.reasons"
                                    v-show="stage == 5"
                                    :reasons="reasons"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></Reasons>

                                <Responsible
                                    :errors="errorSections.responsibility"
                                    v-show="stage == 6"
                                    @valueChanged="valueChanged"
                                    @continue="nextStage"
                                ></Responsible>
                            </div>
                        </div>
                    </template>

                    <template #backButton v-if="stage != 0">
                        <button
                            type="button"
                            class="popup__back button-reset"
                            @click="previousStage"
                        >
                            {{ $t('Step') }} {{ stage }} / {{ numberOfStages }}
                        </button>
                    </template>
                </Popup>
            </div>
        </form>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../../Layout/Base.vue';
import Popup from '../../../Shared/Popup.vue';
import Sector from './Shared/Sector.vue';
import Size from './Shared/Size.vue';
import Goals from './NewBuildSteps/Goals.vue';
import BuildingAge from './RefurbishmentSteps/BuildingAge.vue';
import BuildingType from './RefurbishmentSteps/BuildingType.vue';
import Reasons from './RefurbishmentSteps/Reasons.vue';
import Responsible from './RefurbishmentSteps/Responsible.vue';
import SelectSingle from '../../../Shared/Inputs/SelectSingle.vue';
import AlertBox from '../../../Shared/AlertBox.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ContinueButton from '../../../Shared/Inputs/ContinueButton.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    watch: {
        errors: {
            deep: true,
            handler: function () {
                this.checkErrors();
            },
        },
    },
    mounted() {
        this.checkErrors();
    },
    setup(props) {
        const formValues = useForm({
            formPath: null,
            goals: null,
            buildingAge: null,
            unknownAge: null,
            typeOfBuilding: null,
            length: null,
            width: null,
            unit: 'Feet',
            sector: null,
            responsibility: null,
            reasons: null,
        });

        return { formValues };
    },
    components: {
        LayoutDefault,
        Link,
        Popup,
        SelectSingle,
        Sector,
        Size,
        Goals,
        BuildingAge,
        BuildingType,
        Reasons,
        Responsible,
        AlertBox,
        ContinueButton,
    },
    data() {
        return {
            errorSections: {},
            localErrors: {},
            activeErrorSections: [],
            popupActive: false,
            stage: 0,
            hasStarted: false,
            hasFinished: false,
            isFormPathSelected: false,
            numberOfStages: 0,

            steps: {
                newBuild: [
                    {
                        id: 'sector',
                        title: this.$t('Choose your sector'),
                        subtitle: this.$t('YOUR SECTOR'),
                    },
                    {
                        id: 'size',
                        title: this.$t('Define the size of your project'),
                        subtitle: this.$t('VOLUME'),
                    },
                    {
                        id: 'goals',
                        title: this.$t('Tell us about your goals'),
                        subtitle: this.$t('GOALS'),
                    },
                    {
                        id: 'visualise',
                        title: this.$t(
                            'Bring your new agricultural building to life'
                        ),
                        subtitle: this.$t('VISUALISE'),
                    },
                ],
                refurbishment: [
                    {
                        id: 'sector',
                        title: this.$t('Choose your sector'),
                        subtitle: this.$t('YOUR SECTOR'),
                    },
                    {
                        id: 'typeOfBuilding',
                        title: this.$t('Choose the current build frame type'),
                        subtitle: this.$t('BUILDING FRAME'),
                    },
                    {
                        id: 'buildingAge',
                        title: this.$t('How old is your building?'),
                        subtitle: this.$t('BUILDING AGE'),
                    },
                    {
                        id: 'size',
                        title: this.$t('Define the size of your project'),
                        subtitle: this.$t('SIZE'),
                    },
                    {
                        id: 'reasons',
                        title: this.$t('Tell us your reasons'),
                        subtitle: this.$t('REASONS'),
                    },
                    {
                        id: 'responsibility',
                        title: this.$t('Who will be responsible'),
                        subtitle: this.$t('RESPONSIBILITY'),
                    },
                ],
            },
            formPaths: [
                { id: 0, name: this.$t('I am planning a new build') },
                {
                    id: 1,
                    name: this.$t('I am refurbishing an existing building'),
                },
            ],
        };
    },
    props: {
        sectors: Array,
        errors: Object,
        goals: Array,
        reasons: Array,
    },
    methods: {
        openPopup() {
            this.popupActive = true;
            if (this.stage == 0) {
                this.setStage(1);
            }
        },
        closePopup() {
            this.popupActive = false;
        },
        initForm() {
            // if form path set, reset values and move on
            if (this.isFormPathSelected) {
                this.stage = 1;
                this.hasFinished = false;
                this.hasStarted = true;
                this.formValues = { formPath: this.formValues.formPath };

                if (this.formValues.formPath == 0) {
                    this.numberOfStages = 3;
                } else {
                    this.numberOfStages = 6;
                }
            }
        },
        setStage(idx) {
            // if stage is the last one in the path
            if (this.formValues.formPath != null) {
                if (idx - 1 == this.numberOfStages) {
                    this.hasFinished = true;
                    this.hasStarted = false;
                } else if (idx == 0) {
                    this.stage = 0;
                    this.popupActive = false;
                } else {
                    this.stage = idx;
                }
                window.scrollTo(0, 0);
            }
        },
        previousStage() {
            this.setStage(this.stage - 1);
        },
        nextStage() {
            if (this.stage == this.numberOfStages) {
                this.submitForm();
            } else {
                this.setStage(this.stage + 1);
            }
        },
        getStage(title) {
            let activePath =
                this.steps[
                    this.formValues.formPath == 0 ? 'newBuild' : 'refurbishment'
                ];
            let activeIdx = null;

            activePath.forEach((path, idx) => {
                if (path.id == title) {
                    activeIdx = idx;
                }
            });

            return activeIdx;
        },
        isPositionActive(idx) {
            if (this.stage > idx) {
                return 'active';
            }
        },
        formPathChanged(change) {
            // this.isFormPathSelected = true;
            this.isFormPathSelected = change.value != null;
            this.valueChanged(change);
        },
        valueChanged(change) {
            if (change.input == 'size') {
                this.formValues.unit = change.value.unit;
                this.formValues.length = change.value.length;
                this.formValues.width = change.value.width;
            } else {
                this.formValues = {
                    ...this.formValues,
                    [change.input]: change.value,
                };
            }
        },
        submitForm() {
            if (this.formValues.unknownAge) this.formValues.buildingAge = 0;

            this.localErrors = {};
            // submit form here
            Inertia.post('shed-solution/store', this.formValues, {
                onError: (error) => {
                    this.checkErrors();
                },
            });
        },
        checkErrors() {
            this.activeErrorSections = [];
            this.localErrors = this.errors;

            // set error sections object to get values from prop
            this.errorSections = {
                sector: {
                    sector: this.localErrors.sector,
                },
                size: {
                    length: this.localErrors.length,
                    width: this.localErrors.width,
                    unit: this.localErrors.unit,
                },
                goals: {
                    goals: this.localErrors.goals,
                },
                buildingAge: {
                    buildingAge: this.localErrors.buildingAge,
                },
                typeOfBuilding: {
                    typeOfBuilding: this.localErrors.typeOfBuilding,
                },
                responsibility: {
                    responsibility: this.localErrors.responsibility,
                },
                reasons: {
                    reasons: this.localErrors.reasons,
                },
            };

            // make a list of all the errored form sections
            for (const [key, value] of Object.entries(this.errorSections)) {
                Object.values(value).forEach((val) => {
                    if (val != null) this.activeErrorSections.push(key);
                });
            }

            if (this.activeErrorSections.length !== 0) {
                // set the number of steps in the path
                if (this.formValues.formPath == 0) {
                    this.numberOfStages = 3;
                } else {
                    this.numberOfStages = 6;
                }

                // set the stage to the number with the main title of the error
                // add one to ignore initial screen
                this.setStage(this.getStage(this.activeErrorSections[0]) + 1);

                // activate popup
                this.popupActive = true;
                this.hasStarted = true;
                this.hasFinished = false;
            }
        },
        goBack() {
            window.history.back();
        },
    },
};
</script>
