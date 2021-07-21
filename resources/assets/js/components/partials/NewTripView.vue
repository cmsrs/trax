<template>
        <v-form ref="form" v-model="valid" lazy-validation>
            <date-picker
                @dateChanged="dateChanged"
                :rules="[v => !!v  || 'Item is required']"
            ></date-picker>

            <v-select
                    v-model="car"
                    :items="cars"
                    item-text="text"
                    item-value="value"
                    label="Car Driven"
                    :rules="[v => !!v  || 'Item is required']"
            ></v-select>

            <v-text-field
                    v-model="miles"
                    label="Miles Driven"
                    required
                    :rules="[v => !!v  || 'Item is required', v => (v && !isNaN(v)) || 'Must be a number']"
            ></v-text-field>

            <v-btn
                    :disabled="!valid"
                    @click="submit"
            >
                submit
            </v-btn>
            <v-btn @click="clear">clear</v-btn>

        </v-form>
</template>

<script>
    import {traxAPI} from "../../traxAPI";

    export default {
        props: [],
        mounted() {
            console.log('Component NewTripView mounted.');
            this.fetchCars();
        },
        created() {
            console.log('Component NewTripView created.')
        },
        data() {
            return {
                valid: true,
                cars: [],
                date: null,
                car: null,
                miles: null
            }
        },
        watch: {},
        computed: {},
        methods: {
            dateChanged(date) {
                this.date = date;
            },
            fetchCars() {
                axios.get(traxAPI.getCarsEndpoint())
                    .then(response => {
                        let cars = [];
                        for(let i = 0; i < response.data.data.length; i++) {
                            let car = response.data.data[i];
                            cars.push({
                                text: car.year + ' ' + car.make + ' ' + car.model,
                                value: car.id
                            });
                        }
                        this.cars = cars;
                    }).catch(e => {
                    console.log(e);
                });
            },
            submit() {
                if (this.$refs.form.validate()) {
                    axios.post(traxAPI.addTripEndpoint(), {
                        date: this.date.toISOString(),
                        car_id: this.car,
                        miles: this.miles
                    }).then(response => {
                        this.$router.push('/trips')
                    }).catch(e => {
                        console.log(e);
                    });
                }
            },
            clear () {
                this.$refs.form.reset()
            }
        },
        components: {
            'date-picker' : require('../common/DatePicker.vue')
        }
    }
</script>

<style scoped lang="scss">

</style>