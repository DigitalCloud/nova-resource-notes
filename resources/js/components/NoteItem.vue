<template>
    <tr>
        <td>
            <div class="flex relative pt-4">
                <button
                        class="absolute pin-r pin-t mt-4 bg-40 p-2 rounded"
                        :data-testid="`${testId}-delete-button`"
                        :dusk="`${resource['id'].value}-delete-button`"

                        v-if="resource.authorizedToDelete && (! resource.softDeleted || viaManyToMany)"
                        @click.prevent="openDeleteModal"
                        :title="__(viaManyToMany ? 'Detach' : 'Delete')"
                >
                    <!--<icon />-->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current text-80"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                </button>
                <div class="pt-4 pb-8 pl-4 pr-6">
                    <div class="flex">
                        <img v-if="getField('creator') && getField('creator').value.avatar" :src="`/storage/${ getField('creator').value.avatar }`" class="rounded-full w-12 h-12 mr-3">
                        <img v-else src="https://secure.gravatar.com/avatar/901237802e51d4586aea82cfb5e447c9?size=512" class="rounded-full w-12 h-12 mr-3">

                        <div>
                            <h3 class="text-xl" >{{ getField('creator')? getField('creator').value.name : '' }} <span class="text-xs text-light text-info text-50">. {{ getField('time_ago')? getField('time_ago').value : '' }}</span></h3>
                            <pre class="mt-2">{{ getField('note')? getField('note').value : '' }}</pre>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
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
