<template>
    <tr :dusk="resource['id'].value + '-row'">


        <!-- Fields -->
        <!--<td v-for="field in resource.fields">-->
            <!--<component-->
                <!--:is="'index-' + field.component"-->
                <!--:class="`text-${field.textAlign}`"-->
                <!--:resource-name="resourceName"-->
                <!--:via-resource="viaResource"-->
                <!--:via-resource-id="viaResourceId"-->
                <!--:field="field"-->
            <!--/>-->
        <!--</td>-->

        <td v-if="note" class="">
            <div class="bg-teal-lightest border-teal text-teal-darkest px-4 py-3 shadow-md mb-2 mt-4 bg-40" role="alert">
                <div class="flex items-center">
                    <img :src="gravatar" class="rounded-full w-8 h-8 mr-3">
                    <p class="font-bold mr-3" v-html="getField('creator')? getField('creator').value.name : ''"></p>
                    <p class="text-sm" v-html="getField('note')? getField('note').value : ''"></p>
                </div>
            </div>
            <!--<div v-html="getField('creator')? getField('creator').value.name : ''"></div>-->
            <!--<component-->
            <!--:is="currentMode"-->
            <!--:class="`text-${note.textAlign}`"-->
            <!--:resource-name="resourceName"-->
            <!--:via-resource="viaResource"-->
            <!--:via-resource-id="viaResourceId"-->
            <!--:field="note"-->
            <!--/>-->
            <p class="text-sm text-right text-info my-2" v-html="getField('created_at')? getField('created_at').value : ''"></p>
        </td>
        <!--<td v-if="note && updateMode">-->
            <!--<component-->
                <!--is="note-text"-->
                <!--:class="`text-${note.textAlign}`"-->
                <!--:resource-name="resourceName"-->
                <!--:via-resource="viaResource"-->
                <!--:via-resource-id="viaResourceId"-->
                <!--:field="note"-->
            <!--/>-->
        <!--</td>-->

        <td class="td-fit text-right pr-6">


            <!--<span v-if="resource.authorizedToUpdate">-->
                <!--&lt;!&ndash; Edit Resource Link &ndash;&gt;-->
                <!--<button-->
                    <!--class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"-->
                    <!--@click="updateNote"-->
                <!--&gt;-->
                    <!--<icon type="edit" />-->
                <!--</button>-->
            <!--</span>-->

            <!-- Delete Resource Link -->
            <button
                :data-testid="`${testId}-delete-button`"
                :dusk="`${resource['id'].value}-delete-button`"
                class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                v-if="resource.authorizedToDelete && (! resource.softDeleted || viaManyToMany)"
                @click.prevent="openDeleteModal"
                :title="__(viaManyToMany ? 'Detach' : 'Delete')"
            >
                <icon />
            </button>

            <portal to="modals">
                <transition name="fade">
                    <delete-resource-modal
                        v-if="deleteModalOpen"
                        @confirm="confirmDelete"
                        @close="closeDeleteModal"
                        :mode="viaManyToMany ? 'detach' : 'delete'"
                    >
                        <div slot-scope="{ uppercaseMode, mode }" class="p-8">
                            <heading :level="2" class="mb-6">{{ __(uppercaseMode+' Resource') }}</heading>
                            <p class="text-80 leading-normal">{{__('Are you sure you want to '+mode+' this resource?')}}</p>
                        </div>
                    </delete-resource-modal>
                </transition>
            </portal>
        </td>
    </tr>
</template>

<script>
    import md5 from 'md5'
    import { Errors, InteractsWithResourceInformation } from 'laravel-nova'

    export default {

        mixins: [InteractsWithResourceInformation],

        props: [
            'testId',
            'deleteResource',
            'restoreResource',
            'resource',
            'resourcesSelected',
            'resourceName',
            'relationshipType',
            'viaRelationship',
            'viaResource',
            'viaResourceId',
            'viaManyToMany',
            'checked',
            'actionsAreAvailable',
            'shouldShowCheckboxes',
            'updateSelectionStatus',
        ],

        data: () => ({
            note: {},
            deleteModalOpen: false,
            restoreModalOpen: false,
            updateMode: false,
            lastRetrievedAt: null,
            value: null
        }),

        created() {
            this.note = this.resource.fields[0]
            this.value = this.resource.fields[0].value
            this.updateLastRetrievedAtTimestamp()
        },

        methods: {

            getField(name) {
                let fields = this.resource.fields.filter(field => field.attribute === name)
                return fields? fields[0] : null
            },



            updateNoteValue(g) {
                console.log('updateNoteValue');
            },

            async updateNote() {

                if(!this.updateMode) {
                    return this.updateMode = true
                }

                try {
                    const response = await this.updateRequest()
                    this.$nextTick(() => {
                        this.updateMode = false;
                    });

                } catch (error) {

                }
            },

            updateRequest() {
                return Nova.request().post(
                    `/nova-api/${this.resourceName}/${this.resource.id.value}`,
                    this.updateNoteFormData()
                )
            },

            updateNoteFormData() {
                var _this = this
                return _.tap(new FormData(), formData => {
                    this.note.fill(formData)
                    // formData.append('note', note.value)
                    formData.append('_method', 'PUT')
                    formData.append('_retrieved_at', Math.floor(new Date().getTime() / 1000))
                })
            },

            updateLastRetrievedAtTimestamp() {
                this.lastRetrievedAt = Math.floor(new Date().getTime() / 1000)
            },

            /**
             * Select the resource in the parent component
             */
            toggleSelection() {
                this.updateSelectionStatus(this.resource)
            },

            openDeleteModal() {
                this.deleteModalOpen = true
            },

            confirmDelete() {
                this.deleteResource(this.resource)
                this.closeDeleteModal()
            },

            closeDeleteModal() {
                this.deleteModalOpen = false
            },

            openRestoreModal() {
                this.restoreModalOpen = true
            },

            confirmRestore() {
                this.restoreResource(this.resource)
                this.closeRestoreModal()
            },

            closeRestoreModal() {
                this.restoreModalOpen = false
            },
        },

        computed: {
            gravatar() {
                return "https://secure.gravatar.com/avatar/" + md5(this.getField('creator').value.email)  + "?size=512"
            },
            currentMode() {
                return this.updateMode? 'note-text' : "index-note-text"
            }
        },
        watch: {
            note: function(val) {
                console.log('watch:note')
            }
        }
    }
</script>
