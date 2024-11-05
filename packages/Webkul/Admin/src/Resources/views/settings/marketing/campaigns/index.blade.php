<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.marketing.campaigns.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header section -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="flex cursor-pointer items-center">
                    {!! view_render_event('admin.settings.marketing.campaigns.index.breadcrumbs.before') !!}

                    <!-- Bredcrumbs -->
                    <x-admin::breadcrumbs name="settings.marketing.campaigns" />

                    {!! view_render_event('admin.settings.marketing.campaigns.index.breadcrumbs.after') !!}
                </div>

                <div class="text-xl font-bold dark:text-gray-300">
                    @lang('admin::app.settings.marketing.campaigns.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">                
                <!-- Create button for Campaings -->
                <div class="flex items-center gap-x-2.5">
                    {!! view_render_event('admin.settings.marketing.campaigns.index.breadcrumbs.after') !!}

                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.marketingCampaigns.actionType = 'create';$refs.marketingCampaigns.toggleModal()"
                    >
                        @lang('admin::app.settings.marketing.campaigns.index.create-btn')
                    </button>

                    {!! view_render_event('admin.settings.marketing.campaigns.index.create_button.after') !!}
                </div>
            </div>
        </div>
        
        <v-campaigns ref="marketingCampaigns">
            <x-admin::shimmer.datagrid />
        </v-campaigns>
    </div>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-campaigns-template"
        >
            <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :src="route('admin.settings.marketing.campaigns.index')"
                    ref="datagrid"
                >
                    <template #body="{
                        isLoading,
                        available,
                        applied,
                        selectAll,
                        sort,
                        performAction
                    }">
                        <template v-if="isLoading">
                            <x-admin::shimmer.datagrid.table.body />
                        </template>
            
                        <template v-else>
                            <div
                                v-for="record in available.records"
                                class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                            >
                                <!-- Mass Actions, Title and Created By -->
                                <div class="flex select-none items-center gap-16">
                                    <input
                                        type="checkbox"
                                        :name="`mass_action_select_record_${record.id}`"
                                        :id="`mass_action_select_record_${record.id}`"
                                        :value="record.id"
                                        class="peer hidden"
                                        v-model="applied.massActions.indices"
                                    >

                                    <label
                                        class="icon-checkbox-outline peer-checked:icon-checkbox-select cursor-pointer rounded-md text-2xl text-gray-600 peer-checked:text-brandColor dark:text-gray-300"
                                        :for="`mass_action_select_record_${record.id}`"
                                    ></label>
                                </div>
                                
                                <!-- Campaings Id -->
                                <p>@{{ record.id }}</p>
            
                                <!-- Campaings Name -->
                                <p>@{{ record.name }}</p>

                                <!-- Campaings subject -->
                                <p>@{{ record.subject }}</p>

                                <!-- Actions -->
                                <div class="flex justify-end">
                                    <a @click.prevent="actionType = 'edit';edit(record)">
                                        <span
                                            :class="record.actions.find(action => action.index === 'edit')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>

                                    <a @click.prevent="performAction(record.actions.find(action => action.index === 'delete'))">
                                        <span
                                            :class="record.actions.find(action => action.index === 'delete')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </template>
                    </template>
                </x-admin::datagrid>

                <Teleport to="body">
                    {!! view_render_event('admin.settings.marketing.campaigns.index.form_controls.before') !!}
        
                    <x-admin::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                        ref="eventForm"
                    >
                        <form @submit="handleSubmit($event, createOrUpdate)">
                            {!! view_render_event('admin.settings.marketing.campaigns.index.form_controls.modal.before') !!}
        
                            <x-admin::modal ref="campaignModal">
                                <x-slot:header>
                                    {!! view_render_event('admin.settings.marketing.campaigns.index.form_controls.modal.header.dropdown.before') !!}
        
                                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                                        @{{ 
                                            actionType == 'create'
                                            ? "@lang('admin::app.settings.marketing.campaigns.index.create.title')"
                                            : "@lang('admin::app.settings.marketing.campaigns.index.edit.title')" 
                                        }}
                                    </p>

                                    {!! view_render_event('admin.settings.marketing.campaigns.index.form_controls.modal.header.dropdown.after') !!}
                                </x-slot>
        
                                <x-slot:content>
                                    {!! view_render_event('admin.settings.marketing.campaigns.index.form_controls.modal.content.controls.before') !!}
        
                                    <!-- Name -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.marketing.campaigns.index.create.name')
                                        </x-admin::form.control-group.label>
                                        
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                        />

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            rules="required"
                                            :label="trans('admin::app.settings.marketing.campaigns.index.create.name')"
                                        />
        
                                        <x-admin::form.control-group.error control-name="name" />
                                    </x-admin::form.control-group>
        
                                    <!-- Subject -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.marketing.campaigns.index.create.subject')
                                        </x-admin::form.control-group.label>
                                        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="subject"
                                            rules="required"
                                            rows="4"
                                            :label="trans('admin::app.settings.marketing.campaigns.index.create.subject')"
                                        />
        
                                        <x-admin::form.control-group.error control-name="subject" />
                                    </x-admin::form.control-group>

                                    <!-- Event -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.marketing.campaigns.index.create.event')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            class="cursor-pointer"
                                            name="marketing_event_id"
                                            rules="required"
                                            :label="trans('admin::app.settings.marketing.campaigns.index.create.event')"
                                        >
                                            <option
                                                v-for="event in campaigns.events"
                                                v-text="event.name"
                                                :value="event.id"
                                            ></option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="marketing_event_id" />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.marketing.campaigns.index.create.email-template')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            class="cursor-pointer"
                                            name="marketing_template_id"
                                            rules="required"
                                            :label="trans('admin::app.settings.marketing.campaigns.index.create.email-template')"
                                        >
                                            <option
                                                v-for="template in campaigns.emailTemplates"
                                                v-text="template.name"
                                                :value="template.id"
                                            ></option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="marketing_template_id" />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.marketing.campaigns.index.create.status')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="switch"
                                            name="status"
                                            value="1"
                                            :label="trans('admin::app.settings.marketing.campaigns.index.create.status')"
                                        />
            
                                        <x-admin::form.control-group.error control-name="status" />
                                    </x-admin::form.control-group>

                                    {!! view_render_event('admin.settings.marketing.campaigns.index.form_controls.modal.content.controls.after') !!}
                                </x-slot>
        
                                <x-slot:footer>
                                    {!! view_render_event('admin.components.activities.actions.activity.form_controls.modal.footer.save_button.before') !!}
        
                                    <x-admin::button
                                        type="submit"
                                        class="primary-button"
                                        :title="trans('admin::app.components.activities.actions.activity.save-btn')"
                                        ::loading="isStoring"
                                        ::disabled="isStoring"
                                    />
        
                                    {!! view_render_event('admin.components.activities.actions.activity.form_controls.modal.footer.save_button.after') !!}
                                </x-slot>
                            </x-admin::modal>
        
                            {!! view_render_event('admin.components.activities.actions.activity.form_controls.modal.after') !!}
                        </form>
                    </x-admin::form>
        
                    {!! view_render_event('admin.components.activities.actions.activity.form_controls.after') !!}
                </Teleport>
            </div>
        </script>

        <script type="module">
            app.component('v-campaigns', {
                template: '#v-campaigns-template',

                data() {
                    return {
                        isStoring: false,

                        actionType: 'create',

                        campaigns: {
                            events: [],
                            emailTemplates: [],
                        },
                    };
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;
        
                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }
        
                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }
        
                        return count;
                    },
                },

                mounted() {
                    this.getEvents();

                    this.getEmailTemplates();
                },

                methods: {
                    toggleModal() {
                        this.$refs.campaignModal.toggle();
                    },

                    getEvents() {
                        this.$axios.get('{{ route('admin.settings.marketing.campaigns.events') }}')
                            .then(response => this.campaigns.events = response.data.data)
                            .catch(error => {});
                    },

                    getEmailTemplates() {
                        this.$axios.get('{{ route('admin.settings.marketing.campaigns.email-templates') }}')
                            .then(response => this.campaigns.emailTemplates = response.data.data)
                            .catch(error => {});
                    },

                    createOrUpdate(paramas, { resetForm, setErrors }) {
                        this.isStoring = true;

                        const isUpdating = paramas.id && this.actionType === 'edit';

                        isUpdating && (paramas._method = 'PUT');

                        paramas.status = paramas.status ?? 0;

                        this.$axios.post(
                            isUpdating
                            ? `{{ route('admin.settings.marketing.campaigns.update', '') }}/${paramas.id}`
                            : '{{ route('admin.settings.marketing.campaigns.store') }}', 
                            paramas,
                        )
                            .then(response => {
                                this.$refs.campaignModal.toggle();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                setErrors(error.response.data.errors);
                            })
                            .finally(() => this.isStoring = false);
                    },

                    edit(record) {
                        this.$axios.get(`{{ route('admin.settings.marketing.campaigns.show', '') }}/${record.id}`)
                            .then(response => {
                                this.$refs.eventForm.setValues(response.data.data);

                                this.toggleModal();
                            })
                            .catch(error => {});
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
