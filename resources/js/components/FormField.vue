<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <multiselect
                v-model="value"
                :options="options"
                :searchable="true"
                track-by="value"
                label="label"
                placeholder="Pick a value"
                @input="onChange">
            </multiselect>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import Multiselect from "vue-multiselect";

export default {
    components: { Multiselect },
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            options: []
        };
    },

    created() {
        console.log()
        if (this.field.dependsOn) {
            this.field.dependsOn.forEach(function(item) {
                Nova.$on("nova-dynamic-select-changed-" + item, this.onDependencyChanged);
            }, this);
        }
    },

    beforeDestroy() {
        if (this.field.dependsOn) {
            this.field.dependsOn.forEach(function(item) {
                Nova.$off("nova-dynamic-select-changed-" + item, this.onDependencyChanged);
            }, this);
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.options = this.field.options;

            if(this.field.value) {
                this.value = this.options.find(item => item['value'] === this.field.value);
            }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            if(this.value) {
                formData.append(this.field.attribute, this.value.value)
            }
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        getDependValues(value, field) {
            this.field.dependValues[field] = value;
            return this.field.dependValues;
        },

        async onChange(row) {
            Nova.$emit("nova-dynamic-select-changed-" + this.field.attribute.toLowerCase(), {
                value: row.value,
                field: this.field
            });
        },

        async onDependencyChanged(dependsOnValue) {
            Nova.$emit("nova-dynamic-select-changed-" + this.field.attribute.toLowerCase(), {
                value: this.value,
                field: this.field
            });
            this.options = (await Nova.request().post("/nova-vendor/dynamic-select/options", {
                resource: this.resourceName,
                attribute: this.field.attribute,
                depends: this.getDependValues(dependsOnValue.value, dependsOnValue.field.attribute.toLowerCase())
            })).data.options;

            if(this.value) {
                this.value = this.options.find(item => item['value'] === this.value['value']);
            }
        }
    },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .multiselect {
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
        min-height: 36px !important;
        border-radius: 0.5rem;
    }
    .multiselect__tags {
        min-height: 36px !important;
        border: 1px solid var(--60) !important;
        color: var(--80);
        border-radius: 0.5rem !important;
    }
    .multiselect__select {
        background-repeat: no-repeat;
        background-size: 10px 6px;
        background-position: center right 0.75rem;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 6"><path fill="#35393C" fill-rule="nonzero" d="M8.293.293a1 1 0 0 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4A1 1 0 0 1 1.707.293L5 3.586 8.293.293z"/></svg>');
    }
    .multiselect__select:before {
        content: none !important;
    }
</style>
