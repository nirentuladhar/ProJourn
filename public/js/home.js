window.Event = new Vue();

$journals = Vue.component('journals', {
    template: `
    <div>
        <form method="GET" @submit.prevent="createJournal" class="add-new-journal-container">
            <input type="text" v-model="name" class="add-new-journal-textbox">
            <button class="button">Add a new journal</button>
        </form>
        <div id="tasks-template">
            <ul>
                <li v-for="journal in journals">
                    <a href="#" @click="onClickJournal(journal.id)"><i class="fa fa-file-o journal-heading-icon" aria-hidden="true"></i>
                        {{ journal.name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
    `,
    data: function() {
        return {
            name: '',
            journals: {
                id: '',
                name: ''
            }
        }
    },
    created: function() {
        this.fetchJournals();
    },
    methods: {
        fetchJournals: function () {
            axios.get('api/journals')
                .then(response => this.journals = response.data);
        },
        createJournal: function() {
            axios.post('api/newJournal', { name: this.name })
                .then(response => console.log(response))
                .catch(function (error) { console.log('JOURNAL -> ' + error.message) });
            this.fetchJournals();
        },
        onClickJournal: function(id) {
            activeJournal = id;
            Event.$emit('journalClick', id);
        }
    }
})


Vue.component('journal-entries', {
    template: `
    <div>
        <form method="GET" @submit.prevent="createJournalEntry" class="add-new-journal-container"  style="display: none;">
            <input type="text" v-model="name" class="add-new-journal-textbox">
            <button class="button">Add a new entry</button>
        </form>
        <ul class="journal-entries-title">
            
                <li v-for="(journalEntry, index) in journalEntries">
                    <a href="#" @click="onClickEntry(journalEntry.entry_id)" :class="{'dark': index % 2 !== 0}">
                        {{ journalEntry.title }}
                        <p style="color: #AAA; font-size: 0.8rem; font-style:italic">Last updated at {{journalEntry.updated_at}}</p>
                    </a>
                </li>
            
        </ul>
    </div>
    `,
    data: function () {
        return {
            name:'',
            journalEntries: {
                title: ''
            },
            activeJournal:''
        }
    },
    created: function () {
        var that = this;
        Event.$on('journalClick', function (id) {
            axios.post('api/journalEntries', { journal_id: id })
                .then(response => {
                    that.journalEntries = response.data;
                    that.activeJournal = id
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        
    },
    methods: {
        createJournalEntry: function () {
            axios.post('api/newJournalEntry', { name: this.name })
            .then(response => console.log(response))
            .catch(function (error) { console.log('JOURNAL -> ' + error.message) });
        },
        onClickEntry(id) {
            Event.$emit('journalEntryClick', this.activeJournal, id );
        }
    }
})

Vue.component('hidden-entries', {
    template: `
    <div>
        <h6> <b> Hidden </b> </h6>

        <ul class="journal-entries-title">
                <li v-for="(journalEntry, index) in journalEntries">
                    <a href="#" @click="onClickEntry(journalEntry.entry_id)" :class="{'dark': index % 2 !== 0}">
                        {{ journalEntry.title }}
                        <p style="color: #AAA; font-size: 0.8rem; font-style:italic">Last updated at {{journalEntry.updated_at}}</p>
                    </a>
                </li>
            
        </ul>
    </div>
    `,
    data: function () {
        return {
            name: '',
            journalEntries: {
                title: ''
            },
            activeJournal: ''
        }
    },
    created: function () {
        var that = this;
        Event.$on('journalClick', function (id) {
            axios.post('api/hiddenEntries', { journal_id: id })
                .then(response => {
                    that.journalEntries = response.data;
                    that.activeJournal = id
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })

    },
    methods: {
        createJournalEntry: function () {
            axios.post('api/newJournalEntry', { name: this.name })
                .then(response => console.log(response))
                .catch(function (error) { console.log('JOURNAL -> ' + error.message) });
        },
        onClickEntry(id) {
            Event.$emit('journalEntryClick', this.activeJournal, id);
        }
    }
})

Vue.component('deleted-entries', {
    template: `
    <div>
        <h6> <b> Deleted </b> </h6>

        <ul class="journal-entries-title">
                <li v-for="(journalEntry, index) in journalEntries">
                    <a href="#" @click="onClickEntry(journalEntry.entry_id)" :class="{'dark': index % 2 !== 0}">
                        {{ journalEntry.title }}
                        <p style="color: #AAA; font-size: 0.8rem; font-style:italic">Last updated at {{journalEntry.updated_at}}</p>
                    </a>
                </li>
            
        </ul>
    </div>
    `,
    data: function () {
        return {
            name: '',
            journalEntries: {
                title: ''
            },
            activeJournal: ''
        }
    },
    created: function () {
        var that = this;
        Event.$on('journalClick', function (id) {
            axios.post('api/deletedEntries', { journal_id: id })
                .then(response => {
                    that.journalEntries = response.data;
                    that.activeJournal = id
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })

    },
    methods: {
        createJournalEntry: function () {
            axios.post('api/newJournalEntry', { name: this.name })
                .then(response => console.log(response))
                .catch(function (error) { console.log('JOURNAL -> ' + error.message) });
        },
        onClickEntry(id) {
            Event.$emit('journalEntryClick', this.activeJournal, id);
        }
    }
})

Vue.component('journal-entry', {
    template: `
        <div>
            <p v-if="showJournalEntry === false"> No journal entry selected </p>
            <div class="panel-entry" v-if="showJournalEntry">
                <input type="text" v-model="journalEntry.title" style="border:none; font-size: 20px" id="title" name="title">
                <h6>{{ journalEntry.updated_at }}</h6>
                <textarea v-model="journalEntry.body" style="border: none;" rows='20'></textarea>
                <button type="Submit" class="button" @click="entrySaveButtonClick(journalEntry.entry_id, journalEntry.id)">Save</button>
                <button type="Submit" class="button" @click="entryHideButtonClick(journalEntry.entry_id)">Hide</button>
                <button type="Submit" class="button" @click="entryDeleteButtonClick(journalEntry.entry_id)">Delete</button>
            </div>
        </div>
    `,
    data: function() {
        return {
            journalEntry: {
                title:'',
                body:''
            },
            showJournalEntry: false
        }
    },
    created: function() {
        var that = this;
        Event.$on('journalEntryClick', function (journalId, id) {      
            axios.post('api/journalEntry', { journal_id: journalId, entry_id: id })
            .then(response => {
                    that.journalEntry = response.data
                    that.showJournalEntry = true
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('entryVersionClick', function (journalId, entryId, id) {      
                axios.post('api/version', { journal_id: journalId, entry_id: entryId, id: id })
                .then(response => {
                    that.journalEntry = response.data;
                    that.showJournalEntry = true
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('journalClick', function () { that.showJournalEntry = false })
    },
    methods: {
        entrySaveButtonClick(journalEntryId, versionId) {
            axios.post('api/newJournalEntryVersion', { entry_id: journalEntryId, title: this.journalEntry.title, body: this.journalEntry.body })
                .then(response => {
                    this.journalEntry = response.data
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        },
        entryDeleteButtonClick(journalEntryId) {
            axios.post('api/deleteJournalEntry', { id: journalEntryId, journal_id: activeJournal })
                .then(response => { })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        },
        entryHideButtonClick(journalEntryId) {
            axios.post('api/toggleHideJournalEntry', {id: journalEntryId, journal_id: activeJournal})
                .then(response => {})
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        }
    }
})

Vue.component('versions', {
    template: `
        <div v-if="showVersions">
            <h3> Versions </h3>
            <ul>
                <li v-for="version in versions">
                    <a href="#" @click="onEntryVersionClick(version.entry_id, version.id)">{{ version.title }}</a>
                    <p style="font-size: 0.8rem"> Updated at: {{version.updated_at}}</p>
                </li>
            </ul>
        </div>
    `,
    data: function() {
        return {
            versions: {}, 
            showVersions: false
        }
    },
    created: function() {
        var that = this;
        Event.$on('journalEntryClick', function (journalId, id) {  
            axios.post('api/versions', { journal_id: journalId, entry_id: id })
                .then(response => { that.versions = response.data; that.showVersions = true })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('journalClick', function () { that.showVersions = false })
    },
    methods: {
        onEntryVersionClick(entry_id, id) {
            Event.$emit('entryVersionClick', activeJournal, entry_id, id);
        }
    }
})


new Vue({
    el: '#xyz',
    data: {
    }
})


