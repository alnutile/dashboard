<template>
    <section>
        <div class="card-body">
                <b-form @submit="submit">
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
                    <b-form-group>
                        <label for="due-datepicker">Due Date</label>
                        <b-form-datepicker id="due-datepicker" v-model="form.due_date" class="mb-2">
                        </b-form-datepicker>
                    </b-form-group>
                    <b-form-group>
                        <label for="devs">Number of Devs</label>
                        <b-form-spinbutton id="devs" v-model="form.number_of_devs" min="0" max="100"></b-form-spinbutton>
                    </b-form-group>
                    <b-button @click="submit">import</b-button>
                </b-form>
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
        makeToast(variant = null, message) {
            this.$bvToast.toast(message, {
            variant: variant,
            solid: true,
            class: "position-fixed fixed-bottom m-0 rounded-0",
            style: "z-index: 2000;"
            })
        },
       addJiraShow() {
           this.add_jira = !this.add_jira;
       },
       submit(evt) {
           evt.preventDefault()
           this.makeToast("primary", `Importing ${this.form.jira_key}`)
           axios.post("/api/epic_stories", this.form).then(results => {
               this.$store.commit("addEpicStory", results.data);
                this.makeToast("success", `Imported ${this.form.jira_key}`)
                console.log(results)
           }).catch(e => {
               this.message_color = "success"
               console.log(e)
               this.status_message = `Error with import`
               this.makeToast("error", `Error with import`)
           });
       } 
    }
}
</script>
