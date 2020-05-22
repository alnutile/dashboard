<template>
    <section>
        <div class="card-header">
           <b-button variant="outline-primary" @click="addJiraShow">Add Epic or Story</b-button> 
        </div>
        <div class="card-body">
            <transition name="fade">
                <b-form @submit="submit" v-if="add_jira">
                    <b-form-group
                        id="input-group-1"
                        label="Select Type of JIRA Ticket to Import:"
                        label-for="jira_type"
                        description="Epic or Story"
                    >
                        <b-form-select id="jira_type" v-model="form.jira_type" :options="type_options"
                        placeholder="select type"></b-form-select>
                    </b-form-group>                
                    <b-form-group label="The Key of Item:" label-for="key">
                        <b-form-input id="key" v-model="form.jira_key" placeholder="Enter Key eg FOO-4444"></b-form-input>
                    </b-form-group>
                    <b-button @click="submit">import</b-button>
                </b-form>
            </transition>
            <b-alert
                v-model="importing_message"
                class="position-fixed fixed-bottom m-0 rounded-0"
                style="z-index: 2000;"
                variant="primary"
                dismissible
                >
                Importing {{ form.jira_key }}
            </b-alert>

        </div>
    </section>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted.')
    },
    data() {
        return {
            add_jira: false,
            importing_message: false,
            form: {
                jira_type: null
            },
            jira_type: null,
            type_options: [
                { value: null, text: 'Please select one' },
                { value: 'epic', text: "Epic" },
                {value: 'story', text: "Story" }
            ]
        }
    },
    methods: {
       addJiraShow() {
           this.add_jira = !this.add_jira;
       },
       submit(evt) {
           evt.preventDefault()
           console.log("Submit")
           this.importing_message = true;
       } 
    }
}
</script>
