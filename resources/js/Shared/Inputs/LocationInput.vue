<template>
    <p class="error-message" v-if="error">
        {{ error }}
    </p>

    <button
        class="location-popup input-element text-input"
        @click.prevent="openPopup"
    >
        <span>{{ locationText }}</span>
        <svg
            class="location-popup__icon"
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
    </button>

    <component
        :is="isAdmin ? 'AlertBox' : 'Popup'"
        :title="title"
        :minWidth="50"
        :minHeight="70"
        :active="isPopupActive"
        @active-state="closePopup(true)"
    >
        <template #backButton>
            <h4 class="h4">{{ title || $t('Choose a location') }}</h4>
        </template>
        <template #default>
            <div class="location-picker__map">
                <GMapMap
                    :center="center"
                    :zoom="zoom"
                    :options="mapOptions"
                    ref="map"
                    map-type-id="terrain"
                    @click="updateMarker(index, m, $event)"
                    style="width: 100%; height: 100%"
                >
                    <GMapMarker
                        :key="index"
                        v-if="location.latitude && location.longitude"
                        v-for="(m, index) in markers"
                        :draggable="true"
                        :clickable="true"
                        :position="m.position"
                        :icon="markerIcon"
                        @dragend="updateMarker(index, m, $event)"
                    />
                    <p class="error-message" v-if="localError">
                        {{
                            $t(
                                "The address you've chosen doesn't have a postcode. Please try again or type an address above."
                            )
                        }}
                    </p>
                </GMapMap>

                <GMapAutocomplete
                    ref="autocomplete"
                    :placeholder="
                        oldLocation?.street
                            ? `${oldLocation?.street} ${
                                  oldLocation?.postcode
                                      ? ', ' + oldLocation?.postcode
                                      : ''
                              }`
                            : oldLocation?.city
                            ? oldLocation?.city
                            : $t('Enter an address...')
                    "
                    @place_changed="setPlace"
                    :options="{
                        fields: ['address_components', 'geometry', 'name'],
                    }"
                >
                </GMapAutocomplete>

                <button
                    class="button-reset location-picker__my-location"
                    @click="getUserPosition"
                    type="button"
                >
                    <svg
                        width="25"
                        height="25"
                        viewBox="0 0 25 25"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M11.9411 12.9262L12.3574 23.8167C12.3645 24.0331 12.4445 24.2407 12.5845 24.4058C12.7245 24.571 12.9162 24.684 13.1284 24.7265C13.19 24.7322 13.2519 24.7322 13.3135 24.7265C13.499 24.7266 13.6806 24.6732 13.8365 24.5727C13.9924 24.4722 14.116 24.3288 14.1924 24.1598L24.47 1.61931C24.5523 1.43903 24.577 1.23776 24.5408 1.04293C24.5045 0.848092 24.4091 0.669164 24.2675 0.530516C24.1259 0.391868 23.945 0.300242 23.7495 0.268118C23.5539 0.235994 23.3532 0.264936 23.1747 0.350999L0.981169 11.095C0.784827 11.1901 0.626556 11.349 0.532235 11.5457C0.437913 11.7424 0.413122 11.9653 0.461916 12.178C0.510709 12.3906 0.6302 12.5804 0.80085 12.7163C0.971499 12.8522 1.18321 12.9262 1.40137 12.9262H11.9411ZM21.6095 3.25L14.123 19.6841L13.8339 11.9316C13.8239 11.6827 13.718 11.4474 13.5384 11.2749C13.3587 11.1024 13.1192 11.0062 12.8701 11.0064H5.60723L21.6095 3.25Z"
                            fill="#E60000"
                        />
                    </svg>
                </button>

                <ContinueButton
                    text="Confirm"
                    :isFixed="true"
                    :isAdmin="true"
                    :isEnabled="
                        this.location.latitude &&
                        this.location.longitude &&
                        !localError
                    "
                    @continue="emitLocation"
                ></ContinueButton>
            </div>
        </template>
    </component>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3';
import Popup from '../Popup.vue';
import AlertBox from '../AlertBox.vue';
import ContinueButton from './ContinueButton.vue';
import TextInput from './TextInput.vue';
import _ from 'lodash';

export default {
    emits: ['valueChanged'],
    components: {
        Popup,
        ContinueButton,
        TextInput,
        AlertBox,
    },
    props: {
        inputName: String,
        error: String,
        title: String,
        defaultLocation: Object,
        useStreet: String,
        isAdmin: Boolean,
    },
    data() {
        return {
            localError: false,
            defaultLocationJSON: this.defaultLocation,
            gMapsApiKey: usePage().props.value.gMapsApiKey,
            location: {
                latitude: null,
                longitude: null,
                street: null,
                city: null,
                country: null,
                postcode: null,
            },
            oldLocation: null,
            markers: [
                {
                    position: {
                        lat: 51.093048,
                        lng: 6.84212,
                    },
                },
            ],
            isPopupActive: false,
            text: null,

            center: { lat: 51.5072, lng: 0.1276 },
            zoom: 7,
            mapOptions: {
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                fullscreenControl: false,

                styles: [
                    {
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#f5f5f5',
                            },
                        ],
                    },
                    {
                        elementType: 'labels.icon',
                        stylers: [
                            {
                                visibility: 'off',
                            },
                        ],
                    },
                    {
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#616161',
                            },
                        ],
                    },
                    {
                        elementType: 'labels.text.stroke',
                        stylers: [
                            {
                                color: '#f5f5f5',
                            },
                        ],
                    },
                    {
                        featureType: 'administrative.land_parcel',
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#bdbdbd',
                            },
                        ],
                    },
                    {
                        featureType: 'poi',
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#eeeeee',
                            },
                        ],
                    },
                    {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#757575',
                            },
                        ],
                    },
                    {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#e5e5e5',
                            },
                        ],
                    },
                    {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#9e9e9e',
                            },
                        ],
                    },
                    {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#ffffff',
                            },
                        ],
                    },
                    {
                        featureType: 'road.arterial',
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#757575',
                            },
                        ],
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#dadada',
                            },
                        ],
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#616161',
                            },
                        ],
                    },
                    {
                        featureType: 'road.local',
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#9e9e9e',
                            },
                        ],
                    },
                    {
                        featureType: 'transit.line',
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#e5e5e5',
                            },
                        ],
                    },
                    {
                        featureType: 'transit.station',
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#eeeeee',
                            },
                        ],
                    },
                    {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [
                            {
                                color: '#c9c9c9',
                            },
                        ],
                    },
                    {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [
                            {
                                color: '#9e9e9e',
                            },
                        ],
                    },
                ],
            },
            markerIcon: `data:image/svg+xml,%3Csvg width='28' height='36' viewBox='0 0 21 25' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M17.4148 2.80941C13.5095 -0.936471 7.17795 -0.936471 3.27273 2.80941C-0.322809 6.25826 -0.647641 11.7462 2.51783 15.5632L10.3438 25L18.1697 15.5632C21.3352 11.7462 21.0103 6.25826 17.4148 2.80941ZM10.3438 12.5C12.1847 12.5 13.6771 11.0077 13.6771 9.16664C13.6771 7.32572 12.1847 5.83333 10.3438 5.83333C8.50281 5.83333 7.01041 7.32572 7.01041 9.16664C7.01041 11.0077 8.50281 12.5 10.3438 12.5Z' fill='%23E60000'/%3E%3C/svg%3E%0A`,
        };
    },
    mounted() {
        if (this.$props.defaultLocation) {
            try {
                this.location = JSON.parse(this.$props.defaultLocation);
            } catch (error) {
                console.error('Error parsing address!', error);
            }
            this.setCoords(
                this.location.latitude,
                this.location.longitude,
                true,
                false
            );
            this.zoom = 13;
        }
        if (this.$props.useStreet) {
            const API_KEY = this.gMapsApiKey;

            fetch(
                `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(
                    this.useStreet
                )}&key=${API_KEY}`
            )
                .then((response) => response.json())
                .then((data) => {
                    let address = data.results[0];
                    this.setLocation(address);
                    this.updateMarker(0, null, {
                        latLng: {
                            lat() {
                                return address.geometry.location.lat;
                            },
                            lng() {
                                return address.geometry.location.lng;
                            },
                        },
                    });
                    this.zoom = 13;
                    this.setCoords(
                        address.geometry.location.lat,
                        address.geometry.location.lng,
                        true,
                        true
                    );

                    this.oldLocation = _.cloneDeep(this.location);
                })
                .catch((error) => {
                    console.error('Error getting an address!', error);
                });
        }

        this.oldLocation = _.cloneDeep(this.location);
    },
    methods: {
        openPopup() {
            this.isPopupActive = true;
        },
        closePopup(resetLocation = false) {
            if (resetLocation) {
                // this.location = this.oldLocation;
            }
            this.isPopupActive = false;
        },
        // set coordinates of the map
        setCoords(latitude, longitude, moveMap = true, updateLocation = true) {
            this.location.latitude = latitude;
            this.location.longitude = longitude;

            if (moveMap) {
                this.center.lat = latitude;
                this.center.lng = longitude;
            }

            // update marker
            this.markers[0] = {
                position: {
                    lat: latitude,
                    lng: longitude,
                },
            };

            if (updateLocation) {
                this.getLocation(latitude, longitude);
            }
        },
        // get address from coordinates, then update location object
        getLocation(lat, lng) {
            const API_KEY = this.gMapsApiKey;

            fetch(
                `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${API_KEY}`
            )
                .then((response) => response.json())
                .then((data) => {
                    let address = data.results[0];
                    this.setLocation(address);
                })
                .catch((error) => {
                    console.error('Error getting an address!', error);
                });
        },
        // update location object
        setLocation(address) {
            this.location.street = '';
            this.location.city = '';
            this.location.country = '';
            this.location.postcode = '';
            if (address.address_components) {
                address?.address_components.forEach((comp) => {
                    if (comp.types.includes('street_number')) {
                        this.location.street = comp?.long_name;
                    }
                    if (comp.types.includes('route')) {
                        this.location.street = `${this.location.street} ${comp?.long_name}`;
                    }
                    if (comp.types.includes('postal_town')) {
                        this.location.city = comp?.long_name;
                    }
                    if (comp.types.includes('country')) {
                        this.location.country = comp?.long_name;
                    }
                    if (comp.types.includes('postal_code')) {
                        this.location.postcode = comp?.long_name;
                    }
                });
            }

            if (!this.location?.postcode) {
                this.localError = true;
            } else {
                this.localError = false;
            }

            // fill search bar with dragged location
            this.$refs.autocomplete.$el.value = this.location?.street
                ? `${this.location?.street} ${
                      this.location?.postcode
                          ? ', ' + this.location?.postcode
                          : ''
                  }`
                : this.location?.city
                ? this.location?.city
                : '';
        },
        // move the marker to the right place
        updateMarker(index, marker, position) {
            let lat = position.latLng.lat();
            let lng = position.latLng.lng();

            this.markers[index] = {
                position: {
                    lat,
                    lng,
                },
            };

            // update long and latitude
            this.setCoords(lat, lng, false, true);
        },
        // set place from autocomplete field
        setPlace(place) {
            if (place.geometry) {
                let lat = place.geometry.location.lat();
                let lng = place.geometry.location.lng();
                this.zoom = 13;

                // move markers
                this.markers[0].position.lat = lat;
                this.markers[0].position.lng = lng;

                // move map center
                this.setCoords(lat, lng, true, false);
            }

            // update location info
            this.setLocation(place);
        },
        // get users position from geolocation web api
        getUserPosition() {
            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition((position) => {
                    this.setCoords(
                        position.coords.latitude,
                        position.coords.longitude
                    );
                    this.markers[0].position = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    this.zoom = 13;
                });
            } else {
                console.error('geolocation web api not available!');
            }
        },
        emitLocation() {
            this.$emit('valueChanged', {
                input: this.inputName,
                value: JSON.stringify(this.location),
            });
            this.oldLocation = _.cloneDeep(this.location);
            this.closePopup();
        },
    },
    computed: {
        locationText() {
            let string = false;
            if (
                this.oldLocation?.street &&
                this.oldLocation.street != 'undefined'
            ) {
                string = this.oldLocation.street;
            }

            if (
                this.oldLocation?.city &&
                this.oldLocation?.city != 'undefined'
            ) {
                string = string + ', ' + this.oldLocation.city;
            }

            if (
                this.oldLocation?.postcode &&
                this.oldLocation?.postcode != 'undefined'
            ) {
                string = string + ', ' + this.oldLocation.postcode;
            }

            if (string === false) {
                string = this.$t('Choose a location');
            }

            if (string.startsWith(',')) {
                string.substring(1);
            }

            string = string.replaceAll('undefined,', '');
            string = string.replaceAll('false,', '');

            string = string.replaceAll('undefined', '');
            string = string.replaceAll('false', '');

            return string;
        },
    },
};
</script>
