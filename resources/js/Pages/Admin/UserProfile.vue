<template>
    <div class="card profile-details-wrapper">
        <h2 class="admin-subtitle">
            {{ userType.charAt(0).toUpperCase() + userType.slice(1) }}
            {{ $t('info') }}
        </h2>
        <div
            class="user-profile__details"
            :class="{
                'no-profile-pic':
                    !conditions[userType].includes('profilePicture'),
            }"
        >
            <div class="col admin-label user-profile__image">
                <template v-if="edit">
                    <FileUpload
                        to="/admin/profile/upload-logo"
                        :limit="1"
                        inputName="company_logo"
                        accept="image/*"
                        :error="galleryErrors"
                        :small="false"
                        :files="
                            user?.expert?.expert_logo || user?.expert_logo
                                ? [
                                      user?.expert?.expert_logo ||
                                          user?.expert_logo,
                                  ]
                                : null
                        "
                        @returnValue="valueChanged"
                    />
                </template>
                <template v-else>
                    <img
                        :src="
                            user?.expert?.expert_logo?.url ||
                            user?.expert_logo?.url
                        "
                        :alt="user?.name"
                        class="user-profile__pic"
                    />
                </template>

                <template
                    v-if="
                        conditions[userType].includes('specialities') &&
                        edit != true
                    "
                >
                    <h3 class="admin-label__text">{{ $t('Speciality') }}</h3>
                    <div class="user-profile__specialities">
                        <span
                            class="tag"
                            v-for="tag in user?.expert?.specialities"
                        >
                            {{ tag.name }}
                        </span>
                    </div>
                </template>

                <template
                    v-if="
                        conditions[userType].includes('sectors') &&
                        edit != true &&
                        (user?.expert?.sectors || user?.sectors)
                    "
                >
                    <h3 class="admin-label__text">{{ $t('Sectors') }}</h3>
                    <div class="user-profile__sectors">
                        <span
                            class="tag"
                            v-for="tag in user.expert?.sectors || user?.sectors"
                        >
                            {{ tag.name }}
                        </span>
                    </div>
                </template>
            </div>

            <div class="col">
                <div class="admin-detail user-profile__name">
                    <div
                        class="col admin-label"
                        v-if="conditions[userType].includes('name')"
                    >
                        <h3 class="admin-label__text">
                            {{ $t('Name') }}
                        </h3>
                        <template v-if="edit && userType == 'admin'">
                            <TextInput
                                inputName="name"
                                placeholder="Enter a name..."
                                :error="errors?.name"
                                :value="user?.name || user?.company_name"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{
                                user?.name ||
                                user?.company_name ||
                                'Unknown name'
                            }}
                        </template>
                    </div>

                    <div
                        v-if="conditions[userType].includes('farmName')"
                        class="col"
                    >
                        <h3 class="admin-label__text">
                            {{ $t('Farm name') }}
                        </h3>
                        <template v-if="edit">
                            <TextInput
                                inputName="farmName"
                                placeholder="Enter a farm name..."
                                :value="user?.location?.name || ''"
                                :error="errors?.farmName"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{
                                user?.location?.name || 'Farm name not provided'
                            }}
                        </template>
                    </div>

                    <div
                        v-if="conditions[userType].includes('companyName')"
                        class="col admin-label"
                    >
                        <h3 class="admin-label__text">
                            {{ $t('Company name') }}
                        </h3>
                        <template v-if="edit">
                            <TextInput
                                :inputName="'company_name'"
                                placeholder="Enter a name..."
                                :value="
                                    user?.company_name ||
                                    user?.expert?.company_name
                                "
                                :error="errors?.company_name"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{
                                user?.expert?.company_name ||
                                user?.company_name ||
                                'Company name not provided'
                            }}
                        </template>
                    </div>

                    <div
                        v-if="
                            conditions[userType].includes('contactUrl') && !edit
                        "
                        class="col admin-label"
                    >
                        <h3 class="admin-label__text">
                            {{ $t('Website') }}
                        </h3>

                        <a
                            target="_blank"
                            :href="
                                user?.website ||
                                user?.expert_url ||
                                user?.expert?.expert_url
                            "
                        >
                            {{
                                user?.website ||
                                user?.expert_url ||
                                'Click here to contact'
                            }}
                        </a>
                    </div>
                </div>

                <div
                    class="m-bot--3"
                    v-if="userType === 'farmer' && edit != true"
                >
                    <h3 class="admin-label__text">{{ $t('Sectors') }}</h3>
                    <div class="user-profile__sectors">
                        <span
                            v-if="user?.sectors && user?.sectors.length"
                            class="tag"
                            v-for="tag in user?.sectors"
                        >
                            {{ tag.name }}
                        </span>
                        <span v-else>{{ $t('No sectors specified') }}</span>
                    </div>
                </div>

                <div
                    class="admin-detail"
                    v-if="
                        user?.expert_type_id &&
                        user.expert_type_id !== 1 &&
                        !edit
                    "
                >
                    <div class="col admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Expert type') }}
                        </h3>
                        {{ user?.expert_type.name }}
                    </div>
                </div>

                <div
                    class="m-bot--3 user-profile__company"
                    v-if="conditions[userType].includes('contactUrl') && edit"
                >
                    <h3 class="admin-label__text">
                        {{ $t('Website') }}
                    </h3>

                    <TextInput
                        inputName="expert_url"
                        placeholder="Enter a URL..."
                        :value="
                            user?.website ||
                            user?.expert_url ||
                            user?.expert?.expert_url
                        "
                        :error="errors?.website || errors?.expert_url"
                        format="url"
                        @valueChanged="valueChanged"
                    />
                </div>

                <div
                    class="m-bot--3"
                    v-if="edit && userType != 'admin' && userType != 'farmer'"
                >
                    <h3 class="admin-label__text">{{ $t('Description') }}</h3>
                    <TextAreaInput
                        inputName="description"
                        placeholder="Enter a description..."
                        :value="user?.expert?.description || user?.description"
                        :error="errors?.description"
                        @valueChanged="valueChanged"
                    />
                </div>
                <div
                    class="admin-detail"
                    v-else-if="conditions[userType].includes('about')"
                >
                    <div class="col admin-label">
                        <h3 class="admin-label__text">{{ $t('About') }}</h3>
                        {{ user?.expert?.description || user?.description }}
                    </div>
                </div>

                <div
                    class="m-bot--3"
                    v-if="conditions[userType].includes('sectors') && edit"
                >
                    <h3 class="admin-label__text">{{ $t('Sectors') }}</h3>
                    <TagInput
                        inputName="sectors"
                        :availableTags="
                            sectors.map((sector) => {
                                return { id: sector.id, text: sector.name };
                            })
                        "
                        :limitTags="true"
                        :defaultTags="
                            userType == 'installer'
                                ? user?.expert?.sectors.map((sector) => {
                                      return {
                                          id: sector.id,
                                          text: sector.name,
                                      };
                                  })
                                : user?.sectors?.map((sector) => {
                                      return {
                                          id: sector.id,
                                          text: sector.name,
                                      };
                                  })
                        "
                        :error="errors?.sectors"
                        placeholder="Start typing sectors"
                        @valueChanged="valueChanged"
                    />
                </div>

                <div
                    v-if="conditions[userType].includes('specialities') && edit"
                >
                    <div class="m-bot--3">
                        <h3 class="admin-label__text">
                            {{ $t('Specialities') }}
                        </h3>
                        <!-- @todo handle specialities as available tags -->
                        <TagInput
                            inputName="specialities"
                            :availableTags="
                                specialities.map((spec) => {
                                    return { id: spec.id, text: spec.name };
                                })
                            "
                            :limitTags="true"
                            :defaultTags="
                                user?.expert?.specialities.map((spec) => {
                                    return { id: spec.id, text: spec.name };
                                })
                            "
                            :error="errors?.specialities"
                            placeholder="Start typing specialities"
                            @valueChanged="valueChanged"
                        />
                    </div>
                </div>

                <template
                    v-if="edit && conditions[userType].includes('location')"
                >
                    <div class="m-bot--3">
                        <h3 class="admin-label__text">{{ $t('Location') }}</h3>
                        <LocationInput
                            inputName="location"
                            title="Pick a location"
                            :isAdmin="true"
                            :useStreet="
                                (user?.location?.street || user?.user?.street) +
                                (user?.location?.postcode ||
                                    user?.user?.location?.postcode)
                            "
                            :error="errors?.location"
                            @valueChanged="valueChanged"
                        />
                    </div>
                </template>
                <template v-else>
                    <div
                        class="admin-detail"
                        v-if="
                            conditions[userType].includes('location') &&
                            user?.location
                        "
                    >
                        <div class="col admin-label">
                            <h3 class="admin-label__text">
                                {{ $t('Street') }}
                            </h3>
                            {{ user?.location?.street }}
                        </div>
                        <div class="col admin-label">
                            <h3 class="admin-label__text">
                                {{ $t('Postcode') }}
                            </h3>
                            {{ user?.location?.postcode }}
                        </div>
                    </div>
                    <div
                        class="admin-detail"
                        v-if="
                            conditions[userType].includes('location') &&
                            (user?.location || user?.user?.location)
                        "
                    >
                        <div class="col admin-subdetail">
                            <div class="col admin-label">
                                <h3 class="admin-label__text">
                                    {{ $t('City') }}
                                </h3>
                                {{
                                    user?.location?.city ||
                                    user?.user?.location?.city
                                }}
                            </div>
                        </div>
                        <div class="col admin-label">
                            <h3 class="admin-label__text">
                                {{ $t('Country') }}
                            </h3>
                            {{
                                user?.location?.country ||
                                user?.user?.location?.country
                            }}
                        </div>
                    </div>
                </template>

                <template
                    v-if="edit && conditions[userType].includes('radius')"
                >
                    <h3 class="admin-label__text">{{ $t('Radius') }}</h3>
                    <RadiusInput
                        inputName="radius"
                        :defaultAmount="
                            user?.location?.sizeradius ||
                            user?.user?.location?.sizeradius
                        "
                        :defaultUnit="
                            user?.location?.sizeradiustype ||
                            user?.user?.location?.sizeradiustype ||
                            0
                        "
                        :error="errors?.radius"
                        placeholder="0.00"
                        @valueChanged="valueChanged"
                    ></RadiusInput>

                    <div class="m-bot--3">
                        <Switch
                            inputName="nationwide"
                            :label="$t('I work nationwide')"
                            :defaultValue="
                                user?.nationwide || user?.expert?.nationwide
                            "
                            :error="errors?.nationwide"
                            @valueChanged="valueChanged"
                        ></Switch>
                    </div>
                </template>
                <template v-else>
                    <div
                        class="admin-detail"
                        v-if="
                            conditions[userType].includes('nationwide') ||
                            conditions[userType].includes('radius')
                        "
                    >
                        <div
                            class="col admin-label"
                            v-if="conditions[userType].includes('nationwide')"
                        >
                            <h3 class="admin-label__text">
                                {{ $t('Nationwide') }}
                            </h3>
                            {{
                                user?.expert?.nationwide || user?.nationwide
                                    ? $t('Yes')
                                    : $t('No')
                            }}
                        </div>
                        <div
                            class="col admin-label"
                            v-if="
                                conditions[userType].includes('radius') &&
                                (user?.location?.radius ||
                                    user?.location?.sizeradius ||
                                    user?.user?.location?.sizeradius)
                            "
                        >
                            <h3 class="admin-label__text">
                                {{ $t('Radius') }}
                            </h3>
                            {{ user?.location?.sizeradius }}
                            {{
                                user?.location?.sizeradiustype == 0
                                    ? 'km'
                                    : 'miles'
                            }}
                        </div>
                    </div>
                </template>

                <!-- <div
                    class="admin-detail"
                    v-if="conditions[userType].includes('farmSize')"
                >
                    <div class="col admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Farm size') }}
                        </h3>
                        <template v-if="edit"></template>
                        <template v-else> 30m x 34m </template>
                    </div>
                </div> -->

                <div class="admin-detail" v-if="userType == 'admin'">
                    <div class="col admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Region') }}
                        </h3>
                        <template v-if="edit">
                            <DropdownInput
                                inputName="region"
                                :options="regions"
                                :defaultOption="user?.regions?.id"
                                :error="errors?.region"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{ user?.region || 'No region' }}
                        </template>
                    </div>
                </div>

                <div
                    class="admin-detail"
                    v-if="conditions[userType].includes('email')"
                >
                    <div class="col admin-label">
                        <h3 class="admin-label__text">
                            {{
                                userType == 'expert'
                                    ? $t('Company email')
                                    : $t('Email')
                            }}
                        </h3>
                        <template
                            v-if="
                                edit &&
                                (userType == 'admin' || userType == 'expert')
                            "
                        >
                            <EmailInput
                                inputName="email"
                                placeholder="Enter an email address"
                                :value="user?.email || user?.company_email"
                                :error="errors?.email || errors?.company_email"
                                @valueChanged="valueChanged"
                            />
                        </template>
                        <template v-else>
                            {{
                                user?.email ||
                                user?.company_email ||
                                'No email provided'
                            }}
                        </template>
                    </div>
                    <div
                        class="col admin-label"
                        v-if="conditions[userType].includes('phone')"
                    >
                        <h3 class="admin-label__text">
                            {{ $t('Telephone') }}
                        </h3>
                        {{ user?.phone || 'No phone provided' }}
                    </div>
                </div>
                <div class="admin-detail" v-if="edit && userType == 'admin'">
                    <div class="col admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Phone number') }}
                        </h3>
                        <IntlPhoneInput
                            inputName="phone"
                            placeholder="Enter an international phone number"
                            :value="user?.phone"
                            :error="errors?.phone"
                            @valueChanged="valueChanged"
                        />
                    </div>
                </div>
                <div class="admin-detail">
                    <div
                        class="col admin-label"
                        v-if="
                            conditions[userType].includes('contactPreference')
                        "
                    >
                        <h3 class="admin-label__text">
                            {{ $t('Allows contact') }}
                        </h3>
                        {{ contactAllowed }}
                    </div>
                </div>
                <div
                    class="admin-detail"
                    v-if="conditions[userType].includes('contactLink')"
                >
                    <div class="col admin-label">
                        <h3 class="admin-label__text">
                            {{ $t('Contact url') }}
                        </h3>
                        <Link
                            :href="`/find-an-expert/view/${
                                user?.expert?.id || user?.id
                            }?contact=true`"
                            >{{ $t('Click here') }}</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import EmailInput from '../../Shared/Inputs/EmailInput.vue';
import IntlPhoneInput from '../../Shared/Inputs/IntlPhoneInput.vue';
import TagInput from '../../Shared/Inputs/TagInput.vue';
import TextInput from '../../Shared/Inputs/TextInput.vue';
import TextAreaInput from '../../Shared/Inputs/TextAreaInput.vue';
import RadiusInput from '../../Shared/Inputs/RadiusInput.vue';
import LocationInput from '../../Shared/Inputs/LocationInput.vue';
import Switch from '../../Shared/Inputs/Switch.vue';
import DropdownInput from '../../Shared/Inputs/DropdownInput.vue';
import FileUpload from '../../Shared/Inputs/FileUpload.vue';
import SizeInput from '../../Shared/Inputs/SizeInput.vue';

export default {
    emits: ['valueChanged'],
    components: {
        Link,
        EmailInput,
        IntlPhoneInput,
        TagInput,
        TextInput,
        TextAreaInput,
        RadiusInput,
        LocationInput,
        Switch,
        DropdownInput,
        FileUpload,
        SizeInput,
    },
    props: {
        userType: {
            type: String,
            default: 'installer',
        },
        user: Object,
        edit: Boolean,
        errors: Object,
    },
    setup(props, context) {
        const profileOptions = {};
        if (props.userType == 'installer') {
            profileOptions.company_name = props.user?.expert?.company_name;
            profileOptions.expert_url = props.user?.expert?.expert_url;
            profileOptions.description = props.user?.expert?.description;
            profileOptions.location = props.user.location.asJson;
            profileOptions.nationwide = props.user.expert.nationwide;
            profileOptions.radius = props.user.location.sizeradius;
            profileOptions.radiusType = props.user?.location?.sizeradiustype;
            profileOptions.sectors = props.user.expert.sectors.map(
                (sector) => sector.id
            );
            profileOptions.specialities = props.user.expert.specialities.map(
                (speciality) => speciality.id
            );
            profileOptions.company_logo =
                props.user?.expert?.expert_logo?.path +
                '/' +
                props.user.expert.expert_logo.filename;
        }

        if (props.userType == 'expert') {
            profileOptions.company_name = props.user?.company_name;
            profileOptions.description = props.user?.description;
            profileOptions.location = props?.user?.location?.asJson;
            profileOptions.nationwide = props?.user?.nationwide;
            profileOptions.radius = props?.user?.location?.sizeradius;
            profileOptions.radiusType = props.user?.location?.sizeradiustype;
            profileOptions.sectors = props.user?.sectors.map(
                (sector) => sector.id
            );
            profileOptions.company_logo =
                props.user?.expert_logo?.path +
                '/' +
                props.user?.expert_logo?.filename;
            profileOptions.email = props.user?.company_email;
            profileOptions.expert_url = props.user?.expert_url;
        }

        if (props.userType == 'admin') {
            profileOptions.name = props?.user?.name;
            profileOptions.region = props?.user?.regions.id;
            profileOptions.email = props?.user?.email;
        }

        if (props.userType == 'farmer') {
            profileOptions.farmName = props.user?.location?.name || '';
            profileOptions.sectors = props?.user?.sectors?.map((sector) => {
                return sector.id;
            });
            profileOptions.location = props?.user?.location?.asJson;
        }

        const form = useForm(profileOptions);

        return { form };
    },
    mounted() {
        if (this.$page.props.sectors) {
            this.sectors = this.$page.props.sectors.map((sector) => {
                return { id: sector.id, text: sector.name };
            });
        }

        if (this.$page.props.specialities) {
            this.specialities = this.$page.props.specialities.map(
                (speciality) => {
                    return { id: speciality.id, text: speciality.name };
                }
            );
        }
    },
    data() {
        return {
            conditions: {
                installer: [
                    'profilePicture',
                    'name',
                    'email',
                    'phone',
                    'about',
                    'contactPreference',
                    'radius',
                    'nationwide',
                    'sectors',
                    'specialities',
                    'contactLink',
                    'companyName',
                    'location',
                ],
                expert: [
                    'profilePicture',
                    'companyName',
                    'contactUrl',
                    'about',
                    'location',
                    'radius',
                    'nationwide',
                    'sectors',
                ],
                farmer: [
                    'name',
                    'email',
                    'phone',
                    'contactPreference',
                    'farmName',
                    'farmSize',
                    'location',
                    'sectors',
                ],
                admin: ['name', 'email', 'region'],
            },
            sectors: [],
            specialities: [],
        };
    },
    methods: {
        valueChanged(change) {
            if (change.input == 'specialities') {
                let specialities = [];
                change.value.forEach((speciality) =>
                    specialities.push(speciality.id)
                );
                this.form[change.input] = specialities;
            } else if (change.input == 'sectors') {
                let sectors = [];
                change.value.forEach((sector) => sectors.push(sector.id));
                this.form[change.input] = sectors;
            } else if (change.input == 'radius') {
                this.form.radius = change.value.amount;
                this.form.radiusType = change.value.unit;
            } else {
                this.form[change.input] = change.value;
            }

            this.$emit('valueChanged', this.form);
        },
    },
    computed: {
        sectors() {
            return usePage().props.value.sectors;
        },
        specialities() {
            return usePage().props.value.specialities;
        },
        regions() {
            return usePage().props.value.regions.map((region) => {
                return {
                    id: region.id,
                    text: region.region_name,
                };
            });
        },
        contactAllowed() {
            return usePage().props.value.contactAllowed == 1
                ? this.$t('Yes')
                : this.$t('No');
        },
    },
};
</script>
