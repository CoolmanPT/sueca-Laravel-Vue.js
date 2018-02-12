<template>
    <div>
        <div class="card-header">
            <h3 class="h4 text-dark">Games Per Day</h3>
        </div>
        <div class="col-12">
            <p class="mt-2">Insert the day to get the number of games in that day</p>
            <div class="row m-1">   
                <!--<input id="date" type="date" class="form-control col-md-10" v-model="date">-->
                <date-picker v-model="dates" :first-day-of-week="1" range lang="en" format="yyyy-MM-dd"></date-picker>
                <button @click="getNumber" class="btn btn-dark col-md-2 ml-2">Get number of games</button>
            </div>
            <chartjs-line v-if="hasResults && canShow" :beginzero="true" :fill="true" :linetension="0" :bind="true" :datalabel="'Games Per Day'" :option="myoption" :labels="labelChart" :data="dataChart"></chartjs-line>
            
        </div>
    </div>
</template>

<script type="text/javascript">

import DatePicker from 'vue2-datepicker'
var dateStart = new Date();
var dateFinish = new Date();

require('chart.js');
require('hchs-vue-charts');
Vue.use(VueCharts);

export default {
    components: {
        DatePicker
    },
    data: function (){
        return {
            dates: [dateStart, dateFinish],
            results: [], 
            numberOfGames: -1,
            isNumberOfGamesShowed: 0,
            shortcuts: [
            {
                text: 'Today',
                start: new Date(),
                end: new Date()
            }
            ],
            labelChart: [],
            dataChart: [],
            myoption: {
                responsive:true,
                maintainAspectRatio:true
            },
            errors: false,
            loading: true
        }
    },
    computed: {
        canShow: function() {
            return !this.errors && !this.loading;
        },
        hasResults: function() {
            return (this.results.length > 0);
        }
    },
    methods: {
        getNumber(){
            this.loading = true;
            this.errors = false;
            const data = {
                dateStart:  this.dates[0],
                dateFinish: this.dates[1]
            };

            axios.post('/api/statistics/date', data)
            .then(response=>{
                this.results = response.data.data;
                console.log("hey");
                if (this.hasResults) {
                    console.log("hey1");
                    this.labelChart = [];
                            this.dataChart = [];

                            var index;
                            for (index = 0; index < this.results.length; ++index) {
                                this.dataChart.push(this.results[index].numberOfGames);
                                this.labelChart.push(this.results[index].date);
                            }
                }
                this.loading = false;

            }).catch((error) => {
                this.errors = true;
                this.loading = false;
            });
        },
    }
}
</script>