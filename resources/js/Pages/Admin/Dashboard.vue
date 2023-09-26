<template>
    <layout-default>
        <h1 class="admin-title">Dashboard</h1>
        <h2 class="admin-subtitle">Monthly usage</h2>
        <div class="dashboard">
            <div class="dashboard__comparisons">
                <div
                    v-for="comparison in comparisons"
                    class="card dashboard-comparison"
                >
                    <div
                        class="comparison__value"
                        :class="{
                            increase:
                                comparison.newValue - comparison.oldValue >= 0,
                        }"
                    >
                        {{ comparison.newValue }}
                    </div>
                    <div class="comparison__label">{{ comparison.label }}</div>
                </div>
            </div>
            <div class="dashboard__graphs">
                <Graph
                    :id="0"
                    type="line"
                    :labels="labels"
                    :data="visitorData"
                    title="Visitors"
                    initialRange="week"
                    @updateData="updateData"
                />

                <Graph
                    :id="1"
                    type="line"
                    :labels="labels"
                    :data="newUserData"
                    title="New Users"
                    lineColor="#00B2D6"
                    initialRange="week"
                    @updateData="updateData"
                />

                <Graph
                    :id="2"
                    type="bar"
                    :labels="labels"
                    :data="conversionData"
                    title="Conversions"
                    initialRange="month"
                    @updateData="updateData"
                />
            </div>
        </div>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Admin.vue';
import Graph from '../../Shared/Admin/Graph.vue';

export default {
    components: {
        LayoutDefault,
        Link,
        Graph,
    },
    data() {
        return {
            visitorData: [40, 39, 10, 40, 39, 80, 40],
            newUserData: [23, 11, 10, 8, 14, 20, 17],
            conversionData: [
                {
                    label: 'Converted',
                    data: [4, 9, 12, 5, 0, 7, 10],
                    backgroundColor: '#7B61FF',
                },
                {
                    label: 'Not Converted',
                    data: [2, 3, 1, 4, 3, 3, 5],
                    backgroundColor: '#2BBAE8',
                },
            ],

            comparisons: [
                {
                    label: 'Platform Visitors',
                    oldValue: 120,
                    newValue: 212,
                },
                {
                    label: 'Projects Created',
                    oldValue: 34,
                    newValue: 12,
                },
                {
                    label: 'Requests',
                    oldValue: 12,
                    newValue: 26,
                },
                {
                    label: 'Converted Projects',
                    oldValue: 11,
                    newValue: 21,
                },
                {
                    label: 'Projects Finished',
                    oldValue: 19,
                    newValue: 10,
                },
            ],
        };
    },
    methods: {
        test(type) {
            this.visitorData = Array.from({ length: 7 }, () =>
                Math.floor(Math.random() * 20)
            );
            this.newUserData = Array.from({ length: 7 }, () =>
                Math.floor(Math.random() * 20)
            );
            let arr1 = Array.from({ length: 7 }, () =>
                Math.floor(Math.random() * 20)
            );
            let arr2 = Array.from({ length: 7 }, () =>
                Math.floor(Math.random() * 20)
            );
            this.conversionData = [
                {
                    label: 'Converted',
                    data: arr1,
                    backgroundColor: '#7B61FF',
                },
                {
                    label: 'Not Converted',
                    data: arr2,
                    backgroundColor: '#2BBAE8',
                },
            ];
        },
        updateData(change) {
            console.log('Data to be changed to: ', change);
            this.test();
        },
    },
};
</script>
