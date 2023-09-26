<template>
    <td class="admin-table-cell" scope="col">
        <!-- if cell is an edit or delete control -->
        <div v-if="type === 'control'" class="cell-inner cell-inner--control">
            <svg
                v-if="showEdit"
                @click="editRow"
                width="21"
                height="22"
                viewBox="0 0 21 22"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <rect
                    x="-0.00012207"
                    y="0.5"
                    width="21"
                    height="21"
                    rx="3"
                    fill="#00AA4E"
                />
                <path
                    d="M16.1395 5.3602C15.2591 4.47983 13.8316 4.47987 12.9512 5.3603L5.42268 12.8892C5.16669 13.1452 4.98676 13.4672 4.90291 13.8194L4.21269 16.718C4.17469 16.8777 4.22221 17.0455 4.33823 17.1616C4.45425 17.2776 4.62216 17.3251 4.78178 17.2871L7.68057 16.597C8.03287 16.513 8.35498 16.3331 8.61104 16.077L16.1396 8.5481C17.0199 7.66775 17.0199 6.24051 16.1395 5.3602ZM13.6195 6.02843C14.1308 5.51706 14.9599 5.51703 15.4713 6.02837C15.9826 6.53967 15.9827 7.36865 15.4714 7.87997L14.9098 8.44154L13.058 6.58989L13.6195 6.02843ZM12.3898 7.2581L14.2417 9.10975L7.94279 15.4089C7.81039 15.5413 7.64384 15.6343 7.46169 15.6777L5.30983 16.19L5.82221 14.0382C5.86556 13.8561 5.95859 13.6897 6.09093 13.5573L12.3898 7.2581Z"
                    fill="white"
                />
            </svg>
            <svg
                v-if="showDelete"
                @click="deleteRow"
                width="21"
                height="22"
                viewBox="0 0 21 22"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <rect
                    x="-0.00012207"
                    y="0.5"
                    width="21"
                    height="21"
                    rx="3"
                    fill="#E60000"
                />
                <path
                    d="M10.4999 4.5C11.7177 4.5 12.7128 5.37081 12.7801 6.46803L12.7837 6.58642H16.4729C16.7639 6.58642 16.9999 6.80199 16.9999 7.0679C16.9999 7.31166 16.8016 7.5131 16.5444 7.54499L16.4729 7.54938H15.9135L15.014 15.9074C14.9207 16.7735 14.1528 17.4422 13.2141 17.4965L13.0908 17.5H7.90896C6.95639 17.5 6.15271 16.8673 6.00176 16.0192L5.98581 15.9074L5.08555 7.54938H4.5269C4.26009 7.54938 4.03959 7.36825 4.00469 7.13323L3.99988 7.0679C3.99988 6.82414 4.19815 6.6227 4.45539 6.59082L4.5269 6.58642H8.21609C8.21609 5.43412 9.2386 4.5 10.4999 4.5ZM14.8545 7.54938H6.14453L7.0348 15.8131C7.07609 16.1965 7.40822 16.4949 7.81966 16.5329L7.90896 16.537H13.0908C13.5125 16.537 13.87 16.2643 13.9518 15.8939L13.965 15.8131L14.8545 7.54938ZM11.7296 9.31481C11.9964 9.31481 12.2169 9.49595 12.2519 9.73096L12.2566 9.7963V14.2901C12.2566 14.556 12.0207 14.7716 11.7296 14.7716C11.4628 14.7716 11.2423 14.5904 11.2074 14.3555L11.2026 14.2901V9.7963C11.2026 9.53038 11.4385 9.31481 11.7296 9.31481ZM9.27015 9.31481C9.53696 9.31481 9.75747 9.49595 9.7924 9.73096L9.79717 9.7963V14.2901C9.79717 14.556 9.56121 14.7716 9.27015 14.7716C9.00333 14.7716 8.78283 14.5904 8.74793 14.3555L8.74312 14.2901V9.7963C8.74312 9.53038 8.97908 9.31481 9.27015 9.31481ZM10.4999 5.46296C9.85466 5.46296 9.32552 5.91691 9.27422 6.49428L9.27015 6.58642H11.7296C11.7296 5.96595 11.179 5.46296 10.4999 5.46296Z"
                    fill="white"
                />
            </svg>
        </div>
        <div v-else :class="`cell-inner cell-inner--${type}`">
            <!-- if cell is a dropdown -->
            <div v-if="type === 'dropdown'" class="table-filter-wrapper">
                <StatusDropdown
                    inputName="dropdown"
                    :options="filteredDropdownOptions"
                    :defaultOption="{ id: currentStatus }"
                    @valueChanged="valueChanged"
                />
            </div>

            <!-- if cell is an image -->
            <img
                class="cell-inner-image"
                v-else-if="type === 'image'"
                :src="value"
            />

            <!-- if cell is a date -->
            <p
                v-else-if="type === 'date'"
                class="cell-inner-value h7"
                :title="value"
            >
                {{ formatDate(value) }}
            </p>

            <!-- if plain text-->
            <p
                v-else-if="!Array.isArray(value) && type !== 'tag'"
                class="cell-inner-value h7"
                :title="value"
            >
                {{ value }}
            </p>

            <!-- if single tag -->
            <p
                v-else-if="type == 'tag' && !Array.isArray(value)"
                class="cell-inner-value cell-inner-value--tag"
                :class="getTagColor(value)"
                :title="value"
            >
                {{ value }}
            </p>

            <!-- if array, output as tags -->
            <span
                v-else
                v-for="subvalue in value"
                class="cell-inner-value cell-inner-value--tag"
                :title="subvalue"
            >
                {{ subvalue }}
            </span>
        </div>
    </td>
</template>

<script>
import moment from 'moment';
import { Link } from '@inertiajs/inertia-vue3';
import SingleCheckbox from '../Shared/Inputs/SingleCheckbox.vue';
import StatusDropdown from '../Shared/Inputs/StatusDropdown.vue';

export default {
    name: 'AdminTableCell',
    props: {
        type: String,
        value: [String, Number, Array, Object],
        column: Object,
        id: Number,
        showDelete: Boolean,
        showEdit: Boolean,
    },
    components: {
        Link,
        SingleCheckbox,
        StatusDropdown,
    },
    emits: ['edit', 'delete', 'dropdownChanged'],
    mounted() {
        if (this.type == 'dropdown') {
            if (this.value) {
                this.localSelection.push(this.value);
                this.currentStatus = this.value;
            }
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.table-filter-wrapper')) {
                this.dropdownOpen = false;
            }
        });
    },
    data() {
        return {
            localSelection: [],
            dropdownOpen: false,
            currentStatus: 0,
        };
    },
    methods: {
        editRow() {
            this.$emit('edit', this.id);
        },
        deleteRow() {
            this.$emit('delete', this.id);
        },
        formatDate(date) {
            return moment(date).format('DD/MM/YYYY');
        },
        getTagColor(value) {
            if (this.column.tagColors) {
                return this.column.tagColors[value] || 'grey';
            }
        },
        toggleFilter() {
            this.dropdownOpen = !this.dropdownOpen;
        },
        isChecked(value) {
            return this.localSelection.filter((f) => f == value).length;
        },
        valueChanged(change) {
            this.currentStatus = change.value.id;

            this.$emit('dropdownChanged', {
                column: this.column,
                value: change.value.id,
            });
        },
    },
    computed: {
        filteredDropdownOptions() {
            if (this.column?.dropdownTree) {
                return this.column.dropdownOptions.filter((option) => {
                    let treeKey = this?.currentStatus || this?.value || 0;
                    if (treeKey == 'Unknown') {
                        treeKey = 0;
                    }

                    return this.column.dropdownTree[treeKey].includes(
                        option.id
                    );
                });
            } else {
                return this.column.dropdownOptions;
            }
        },
        selectedOption() {
            if (this.type === 'dropdown') {
                let optionText = this.column.dropdownOptions.filter(
                    (dropdownOption) => {
                        if (this.localSelection.length) {
                            return (
                                dropdownOption.id == this.localSelection?.[0]
                            );
                        } else {
                            return dropdownOption.id == this.value;
                        }
                    }
                )?.[0]?.text;

                if (this.localSelection.length > 1) {
                    return `${optionText}... (+${
                        this.localSelection.length - 1
                    })`;
                } else {
                    return optionText;
                }
            }
        },
    },
};
</script>
