<template>
    <notes-wrapper
        :field="field"
        :resource-name="field.resourceName"
        :via-resource="resourceName"
        :via-resource-id="resourceId"
        :via-relationship="field.hasManyRelationship"
        :relationship-type="'hasMany'"
        @actionExecuted="actionExecuted"
        :load-cards="false"
    />
</template>

<script>

export default {

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: function () {
        return {

        }
    },

    mounted: function () {

    },

    computed: {

    }
}
</script>
