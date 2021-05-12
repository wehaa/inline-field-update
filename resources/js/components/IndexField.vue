<template>
    <div class="relative flex items-stretch">
        <input
            :id="field.name + resourceId"
            type="text"
            class="w-full form-control form-input form-input-bordered live-update"
            :placeholder="field.name"
            v-model="value"
            @blur="save"
            @keyup.enter="save"
            :style="'min-width:'+field.minWidth+'px;'"
        />
        <!-- thanks to https://github.com/epartment/nova-unique-ajax-field/blob/master/resources/js/components/FormField.vue -->
        <div class="absolute rotating text-80 flex justify-center items-center pin-y pin-r mr-3" v-show="loading">
            <svg class="w-4 h-4" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M457.373 9.387l-50.095 50.102C365.411 27.211 312.953 8 256 8 123.228 8 14.824 112.338 8.31 243.493 7.971 250.311 13.475 256 20.301 256h10.015c6.352 0 11.647-4.949 11.977-11.293C48.159 131.913 141.389 42 256 42c47.554 0 91.487 15.512 127.02 41.75l-53.615 53.622c-20.1 20.1-5.855 54.628 22.627 54.628H480c17.673 0 32-14.327 32-32V32.015c0-28.475-34.564-42.691-54.627-22.628zM480 160H352L480 32v128zm11.699 96h-10.014c-6.353 0-11.647 4.949-11.977 11.293C463.84 380.203 370.504 470 256 470c-47.525 0-91.468-15.509-127.016-41.757l53.612-53.616c20.099-20.1 5.855-54.627-22.627-54.627H32c-17.673 0-32 14.327-32 32v127.978c0 28.614 34.615 42.641 54.627 22.627l50.092-50.096C146.587 484.788 199.046 504 256 504c132.773 0 241.176-104.338 247.69-235.493.339-6.818-5.165-12.507-11.991-12.507zM32 480V352h128L32 480z" class=""></path></svg>
        </div>
    </div>
</template>

<script>
import { FormField } from 'laravel-nova'

export default {
    data() {
        return {
            loading: false,
        }
    },
    mixins: [FormField],
    props: ['resourceName', 'resourceId', 'field'],
    methods: {
        save() {
            var vm = this;
            if (this.value != this.field.value) {
                if (!this.loading) {
                    Nova.request().post(
                        `/live-update/update/${this.resourceName}`,
                        {
                            id: this.field.id,
                            attribute: this.field.attribute,
                            value: this.value
                        }
                    )
                    .then (function (response) {
                        vm.loading = false
                        vm.field.value = vm.value
                        vm.$toasted.show('Successfully updated!', { type: 'success' })
                    })
                    .catch (function (error) {
                        vm.loading = false
                        console.log(error);

                        if (error.response.status == 422) {
                            let validationErrors = [];

                            error.response.data.errors.value.forEach(function (errorMessage) {
                                validationErrors.push(errorMessage.replace('value', vm.field.name));
                            });

                            vm.$toasted.show(validationErrors.join('<br>'), { type: 'error' });
                        }
                    })
                }
                this.loading = true;
            }
        },
        setInitialValue() {
            this.value = this.field.value || ''
        },
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },
        handleChange(value) {
            this.value = value
        },
    },
}
</script>

<style lang="scss" scoped>
    .live-update {
        border: 0;
        box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
        &:focus {
            border: inherit;
            box-shadow: 0 0 8px var(--primary) !important;
        }
    }
    @-webkit-keyframes rotating {
        from{
            transform: rotate(0deg);
        }
        to{
            transform: rotate(360deg);
        }
    }
    .rotating {
        animation: rotating 2s linear infinite;
    }
</style>
