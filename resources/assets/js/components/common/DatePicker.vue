<template>
    <v-menu ref="datePicker"
            :disabled="disabled"
            :close-on-content-click="false"
            v-model="showPicker"
            :nudge-right="40"
            lazy
            transition="scale-transition"
            offset-y
            full-width
    >
        <v-text-field
                :disabled="disabled"
                slot="activator"
                v-model="textFieldValue"
                :label="label"
                :clearable="!disabled"
                :readonly="true"
                persistent-hint
                append-icon="event"
                :rules="rules"
        ></v-text-field>
        <v-date-picker v-model="pickerValue" no-title @input="showPicker = false"></v-date-picker>
    </v-menu>
</template>

<script>
    import moment from 'moment';
    export default {
        props: {
            disabled: {
                default: false
            },
            label : {
                default: 'Select Date'
            },
            initialDate: {
                default: null
            },
            rules : {
                default: null
            }
        },
        mounted() {
            console.log('Component DatePicker mounted.')

            // don't emit events until after
            // this component is mounted
            this.$nextTick(function () {
                this.eventsEnabled = true;
            })
        },
        created() {
            console.log('Component DatePicker created.');
            if(this.initialDate) {
                this.date = this.initialDate;
            }
        },
        data() {
            return {
                showPicker: false,
                pickerValue: null,
                textFieldValue: null,
                date: null,
                eventsEnabled: false
            }
        },
        watch: {
            date() {
                if(this.date === null) {
                    return;
                }
                // any change in date we observed must
                // have happened from the outside world
                // hence no event generation is needed.
                this.eventsEnabled = false;
                if (this.date.isValid()) {
                    this.textFieldValue = this.date.format('MM/DD/YYYY');
                    this.pickerValue = this.date.format('YYYY-MM-DD');
                } else {
                    this.textFieldValue = null;
                    this.pickerValue = null;
                }
                // enable event generation now that we've set
                // all the values
                this.$nextTick(function () {
                   this.eventsEnabled = true;
                })

            },
            pickerValue(val) {
                this.date = moment(val, 'YYYY-MM-DD');
                if(this.date.isValid()) {
                    this.textFieldValue = this.date.format('MM/DD/YYYY');
                } else {
                    this.date = null;
                    this.textFieldValue = null;
                }
                if(this.eventsEnabled) {
                    this.$emit('dateChanged', this.date);
                }
            },
            textFieldValue(val) {
                this.date = moment(val, 'MM/DD/YYYY');
                if(this.date.isValid()) {
                    this.pickerValue =  this.date.format('YYYY-MM-DD');
                } else {
                    this.date = null;
                    this.textFieldValue = null;
                }
                if(this.eventsEnabled) {
                    this.$emit('dateChanged', this.date);
                }
            }

        },
        computed: {},
        methods: {},
        components: {}
    }
</script>

<style scoped lang="scss">

</style>