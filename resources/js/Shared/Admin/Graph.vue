<template>
    <div class="card chart">
        <div class="chart__header">
            <h3 class="chart__title">{{ title }}</h3>
            <div class="chart__controls">
                <button class="chart-control" type="text" @click="prevPeriod">
                    <svg
                        width="21"
                        height="20"
                        viewBox="0 0 21 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M13.342 6.175L12.167 5L7.16699 10L12.167 15L13.342 13.825L9.52533 10L13.342 6.175Z"
                            fill="#979797"
                        />
                    </svg>
                </button>
                {{ rangeDisplay }}
                <button class="chart-control" type="text" @click="nextPeriod">
                    <svg
                        width="21"
                        height="20"
                        viewBox="0 0 21 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M7.65801 13.825L8.83301 15L13.833 10L8.83301 5L7.65801 6.175L11.4747 10L7.65801 13.825Z"
                            fill="#979797"
                        />
                    </svg>
                </button>
            </div>
            <div class="chart__range">
                <select class="range-select" v-model="range">
                    <option class="range-option" value="week">Week</option>
                    <option selected class="range-option" value="month">
                        Month
                    </option>
                    <option class="range-option" value="quarter">
                        Quarter
                    </option>
                </select>
            </div>
        </div>
        <div class="chart__body">
            <Line
                v-if="type == 'line'"
                :options="lineOptions"
                :data="lineData"
                ref="line"
            />

            <Bar
                v-if="type == 'bar'"
                :options="barOptions"
                :data="barData"
                ref="bar"
            />
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Admin.vue';
import moment from 'moment';
import { Line, Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Tooltip,
    Legend,
} from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Tooltip,
    Legend
);

export default {
    components: {
        LayoutDefault,
        Link,
        Line,
        Bar,
    },
    props: {
        id: Number,
        type: String,
        data: Array,
        title: String,
        lineColor: String,
        initialRange: String,
    },
    emits: ['updateData'],
    mounted() {
        this.range = this.initialRange || 'week';
    },

    data() {
        return {
            initialised: false,
            period: {
                start: null,
                end: null,
            },
            range: null,
            rangeDisplay: 'Unknown',
            labels: ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],

            barOptions: {
                responsive: true,
                maintainAspectRatio: false,
                barThickness: 4,
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            maxTicksLimit: 20,
                            font: {
                                size: 9,
                                family: 'Montserrat',
                                weight: '500',
                            },
                        },
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            maxTicksLimit: 6,
                            font: {
                                size: 9,
                                family: 'Montserrat',
                                weight: '500',
                            },
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#f2f2f2',
                        titleFont: {
                            family: 'Montserrat, sans-serif',
                            size: 11,
                            weight: '500',
                        },
                        titleColor: '#BEBEBE',
                        bodyFont: {
                            family: 'Montserrat, sans-serif',
                            size: 11,
                            weight: '500',
                        },
                        bodyColor: '#979797',
                        cornerRadius: 4,
                        displayColors: false,
                    },
                },
            },

            lineOptions: {
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            color: '#979797',
                            beginAtZero: false,
                            maxTicksLimit: 20,
                            font: {
                                size: 9,
                                family: 'Montserrat',
                                weight: '500',
                            },
                        },
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        title: {
                            text: 'Total: ',
                            label: 'Total: ',
                        },
                        ticks: {
                            color: '#979797',
                            beginAtZero: true,
                            maxTicksLimit: 6,
                            font: {
                                size: 9,
                                family: 'Montserrat',
                                weight: '500',
                            },
                        },
                        grid: {
                            display: false,
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        backgroundColor: '#f2f2f2',
                        titleFont: {
                            family: 'Montserrat, sans-serif',
                            size: 11,
                            weight: '500',
                        },
                        titleColor: '#BEBEBE',
                        bodyFont: {
                            family: 'Montserrat, sans-serif',
                            size: 11,
                            weight: '500',
                        },
                        bodyColor: '#979797',
                        cornerRadius: 4,
                        displayColors: false,
                        callbacks: {
                            label(value) {
                                return 'total: ' + value.raw;
                            },
                        },
                    },
                    legend: {
                        display: false,
                    },
                },
                borderColor: this?.lineColor || '#7B61FF',
                borderWidth: 2,
                pointBackgroundColor: this?.lineColor || '#7B61FF',
                pointBorderColor: '#ffffff',
                pointRadius: 4.5,
            },
        };
    },
    methods: {
        nextPeriod() {
            if (this.range === 'month') {
                this.period.start.add(1, 'M').startOf('month');
                this.period.end = this.period.start.clone().endOf('month');
            } else if (this.range === 'week') {
                // add week to period
                this.period.start = this.period.start.add(7, 'days');
                this.period.end = this.period.start.clone().add(6, 'days');
            } else {
                this.period.start = this.period.start
                    .add(1, 'Q')
                    .startOf('quarter');
                this.period.end = this.period.start.clone().endOf('quarter');
            }

            this.getDateRange();
        },
        prevPeriod() {
            if (this.range === 'month') {
                this.period.start.subtract(1, 'M').startOf('month');
                this.period.end = this.period.start.clone().endOf('month');
            } else if (this.range === 'week') {
                this.period.start = this.period.start.subtract(7, 'days');
                this.period.end = this.period.start.clone().add(6, 'days');
            } else {
                this.period.start = this.period.start
                    .subtract(1, 'Q')
                    .startOf('quarter');
                this.period.end = this.period.start.clone().endOf('quarter');
            }
            this.getDateRange();
        },
        getDateRange() {
            if (this.period.start && this.period.end) {
                if (this.range === 'month') {
                    this.rangeDisplay = `${this.period.start.format(
                        'MMMM YYYY'
                    )}`;
                } else if (this.range === 'week') {
                    this.rangeDisplay = `${this.period.start.format(
                        'DD MMM'
                    )} - ${this.period.end.format('DD MMM')}`;
                } else {
                    this.rangeDisplay = `${this.period.start.format(
                        'DD MMM'
                    )} - ${this.period.end.format('DD MMM')}`;
                }
            } else {
                this.rangeDisplay = 'Unknown range';
            }

            if (this.initialised) {
                this.$emit('updateData', {
                    id: this.id,
                    range: this.range,
                    start: moment.utc(this.period.start).format(),
                    end: moment.utc(this.period.end).format(),
                });
            }

            this.initialised = true;
            this.updateLabels();
        },
        updateLabels() {
            if (this.range == 'month') {
                this.labels = [];
                for (let i = 0; i < moment().daysInMonth(); i++) {
                    this.labels.push(i);
                }
            } else if (this.range == 'quarter') {
                this.labels = [];
                let date = this.period.start.clone();

                while (date < this.period.end.clone().endOf('month')) {
                    date.add(7, 'days');
                    this.labels.push(date.clone().format('W'));
                }
            } else {
                this.labels = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            }
        },
    },
    computed: {
        lineData() {
            return {
                labels: this.labels,
                datasets: [
                    {
                        backgroundColor: '#f87979',
                        data: this.data,
                    },
                ],
            };
        },

        barData() {
            return {
                labels: this.labels,
                datasets: this.data,
            };
        },
    },
    watch: {
        range() {
            if (this.range === 'month') {
                this.period.start = moment().startOf('month');
                this.period.end = moment().endOf('month');
            } else if (this.range === 'week') {
                this.period.start = moment().startOf('isoWeek');
                this.period.end = this.period.start.clone().add(6, 'days');
            } else if (this.range === 'quarter') {
                this.period.start = moment().startOf('quarter');
                this.period.end = moment().endOf('quarter');
            }
            this.getDateRange();
        },
    },
};
</script>
