
window.Event = new Vue();

$journals = Vue.component('journals', {
    template: `
    <div>
        <div class="journal-heading-container">
            <p class="journal-heading">
                JOURNALS
            </p>
            <a class="new-journal" @click = "toggle()"> New Journal <i class="fa fa-plus-circle" style="padding-left: 4px" aria-hidden="true"></i></a>
        </div>
        <div>
            <form method="GET" @submit.prevent="createJournal" class="add-new-journal-container" v-bind:class="{ 'hide-add-button': hideAddButton}">
                <input type="text" v-model="name" class="add-new-journal-textbox">
                <button class="button">Add a new journal</button>
            </form>
            <div id="tasks-template">
                <ul>
                    <li v-for="journal in journals">
                        <a href="#" @click="onClickJournal(journal.id)" v-bind:class="{ active: journal.id == activeId }"><i class="fa fa-file-o journal-heading-icon" aria-hidden="true"></i>
                            {{ journal.name }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    `,
    data: function() {
        return {
            name: '',
            journals: {
                id: '',
                name: ''
            },
            hideAddButton: true,
            activeId: ''
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
        toggle: function () {
            this.hideAddButton = !this.hideAddButton
        },
        createJournal: function() {
            axios.post('api/newJournal', { name: this.name })
                .then(response => console.log(response))
                .catch(function (error) { console.log('JOURNAL -> ' + error.message) });
            this.fetchJournals();
        },
        onClickJournal: function(id) {
            activeJournal = id;
            this.activeId = id;
            this.journals.isActive = !this.journals.isActive;
            Event.$emit('journalClick', id);
            Event.$emit('entriesIsActive');

        }
    }
})


Vue.component('journal-entries', {
    template: `
    <div>
        <div class="journal-entry-tabs" v-if="showAllTabs">
            <a @click="showEntries('Entries')" :class="{ active : activeTab == 'Entries'}">Entries</a>
            <a @click="showHiddenEntries('Hidden')" :class="{ active : activeTab == 'Hidden'}">Hidden</a>
            <a @click="showDeletedEntries('Deleted')" :class="{ active : activeTab == 'Deleted'}">Deleted</a>
        </div>

        <div v-if="showAllTabs">
            <a class="new-journal entry" @click = "toggle()"> New Entry <i class="fa fa-plus-circle" style="padding-left: 4px" aria-hidden="true"></i></a>
            <form method="GET" @submit.prevent="createJournalEntry" class="add-new-journal-container"  v-bind:class="{ 'hide-add-button': hideAddButton}">
                <input type="text" v-model="title" class="add-new-journal-textbox">
                <button class="button">Add an Entry</button>
            </form>
        </div>

        <p v-if="showAllTabs === false" style="padding-left: 8px;"> No journal selected </p>
        <ul class="journal-entries-title" v-if="active">
            <li v-for="(journalEntry, index) in journalEntries">
                <a href="#" @click="onClickEntry(journalEntry.entry_id)">
                    {{ journalEntry.title }}
                    <p style="color: #AAA; font-size: 0.8rem; font-style:italic">Last updated at {{journalEntry.updated_at}}</p>
                </a>
            </li>
        </ul>
    </div>
    `,
    data: function () {
        return {
            title:'',
            journalEntries: {
                title: ''
            },
            activeJournal:'',
            active: false,
            activeTab: '',
            showAllTabs: false,
            hideAddButton: true
        }
    },
    created: function () {
        var that = this;
        Event.$on('journalClick', function (id) {
            Event.$emit('entriesIsActive');
            axios.post('api/journalEntries', { journal_id: id })
                .then(response => {
                    that.active = true;
                    that.showAllTabs = true;
                    that.activeTab = 'Entries';
                    that.journalEntries = response.data;
                    that.activeJournal = id
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('deletedIsActive', function () {
            that.active = false;
        })
        Event.$on('hiddenIsActive', function () {
            that.active = false;
        })

        
    },
    methods: {
        createJournalEntry: function () {
            axios.post('api/newJournalEntry', { journal_id: this.activeJournal, title: this.title })
            .then(response => console.log(response))
            .catch(function (error) { console.log('JOURNAL ENTRY -> ' + error.message) });
            this.hideAddButton = true;
            Event.$emit('journalClick', this.activeJournal);
        },
        toggle: function () {
            this.hideAddButton = !this.hideAddButton
        },
        onClickEntry(id) {
            Event.$emit('journalEntryClick', this.activeJournal, id );
        },
        showHiddenEntries(name) {
            this.activeTab = name;
            Event.$emit('hiddenIsActive', this.activeJournal);
        },
        showDeletedEntries(name) {
            this.activeTab = name;
            Event.$emit('deletedIsActive', this.activeJournal);
        },
        showEntries(name) {
            this.activeTab = name;
            Event.$emit('journalClick', this.activeJournal);
            Event.$emit('entriesIsActive');
        }
    }
})

Vue.component('hidden-entries', {
    template: `
    <div v-if="active">
        <ul class="journal-entries-title">
            <li v-for="(journalEntry, index) in journalEntries">
                <a href="#" @click="onClickEntry(journalEntry.entry_id)">
                    {{ journalEntry.title }} </i>
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
            activeJournal: '',
            active: false
        }
    },
    created: function () {
        var that = this;
        Event.$on('hiddenIsActive', function (id) {
            axios.post('api/hiddenEntries', { journal_id: id })
            .then(response => {
                that.active = true;
                that.journalEntries = response.data;
                that.activeJournal = id
            })
            .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('entriesIsActive', function () {
            that.active = false;
        })
        Event.$on('deletedIsActive', function () {
            that.active = false;
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
    <div v-if="active">
        <ul class="journal-entries-title">
                <li v-for="(journalEntry, index) in journalEntries">
                    <a href="#" @click="onClickEntry(journalEntry.entry_id)">
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
            activeJournal: '',
            active: false
        }
    },
    created: function () {
        var that = this;
        Event.$on('deletedIsActive', function (id) {
            axios.post('api/deletedEntries', { journal_id: id })
                .then(response => {
                    that.active = true;
                    that.journalEntries = response.data;
                    that.activeJournal = id;
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('entriesIsActive', function () {
            that.active = false;
        })
        Event.$on('hiddenIsActive', function () {
            that.active = false;
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
        <div style="margin-top: 64px;">
            <p v-if="showJournalEntry === false"> No journal entry selected </p>
            <div class="panel-entry" v-if="showJournalEntry">
            <input type="text" v-model="journalEntry.title" style="border:none; font-size: 20px" id="title" name="title" placeholder="Write your heading here...">
            <p class="journal-entry-date">Created at: {{ journalEntry.created_at }} | Last updated: {{ journalEntry.updated_at }}</p>
                <textarea v-model="journalEntry.body" style="border: none;" rows='20' placeholder="Write your entry here..."></textarea>
                <div v-if="!deleteFlag">
                    <button type="Submit" class="button" @click="entrySaveButtonClick(journalEntry.entry_id, journalEntry.id)">Save</button>
                    <button type="Submit" class="button" @click="entryHideButtonClick(journalEntry.entry_id)" v-if="!hiddenFlag">Hide</button>
                    <button type="Submit" class="button" @click="entryHideButtonClick(journalEntry.entry_id)" v-if="hiddenFlag">Unhide</button>
                    <button type="Submit" class="button" @click="entryDeleteButtonClick(journalEntry.entry_id)" v-if="!deleteFlag">Delete</button>
                </div>
            </div>
        </div>
    `,
    data: function() {
        return {
            journalEntry: {
                title:'',
                body:''
            },
            hiddenFlag: false,
            showJournalEntry: false,
            deleteFlag: false,
            activeJournal: '',
        }
    },
    created: function() {
        var that = this;
        Event.$on('journalEntryClick', function (journalId, id) {      
            axios.post('api/journalEntry', { journal_id: journalId, entry_id: id })
            .then(response => {
                    that.activeJournal = this.journal_id;
                    that.journalEntry = response.data;
                    that.showJournalEntry = true;
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('entryVersionClick', function (journalId, entryId, id) {      
                axios.post('api/version', { journal_id: journalId, entry_id: entryId, id: id })
                .then(response => {
                    that.journalEntry = response.data;
                    that.showJournalEntry = true;
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
        })
        Event.$on('journalClick', function () {
            that.deleteFlag = false;
            that.hiddenFlag = false;
            that.showJournalEntry = false;
        })
        Event.$on('hiddenIsActive', function () {
            that.deleteFlag = false;
            that.hiddenFlag = true;
            that.showJournalEntry = false;
        })
        Event.$on('deletedIsActive', function () {
            that.deleteFlag = true;
            that.hiddenFlag = false;
            that.showJournalEntry = false;
        })
    },
    methods: {
        entrySaveButtonClick(journalEntryId, versionId) {
            axios.post('api/newJournalEntryVersion', { entry_id: journalEntryId, title: this.journalEntry.title, body: this.journalEntry.body })
                .then(response => {
                    this.journalEntry = response.data;
                })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
            Event.$emit('journalEntryClick', activeJournal, journalEntryId);
        },
        entryDeleteButtonClick(journalEntryId) {
            axios.post('api/deleteJournalEntry', { id: journalEntryId, journal_id: activeJournal })
                .then(response => { })
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
                Event.$emit('journalClick', activeJournal);
            },
        entryHideButtonClick(journalEntryId) {
            this.hiddenFlag = !this.hiddenFlag;
            axios.post('api/toggleHideJournalEntry', {id: journalEntryId, journal_id: activeJournal})
                .then(response => {})
                .catch(function (error) { console.log('fetch me -> ' + error.message) });
            
            Event.$emit('journalClick', activeJournal);
            
        }
    }
})

Vue.component('versions', {
    template: `
        <div v-if="showVersions">
        <hr>
            <h5> Versions </h5>
            <ul style="list-style: none; margin: 0;">
                <li v-for="version in versions" >
                    <div class="callout secondary">
                        <a href="#" @click="onEntryVersionClick(version.entry_id, version.id)">{{ version.title }}</a>
                        <p style="font-size: 0.8rem"> Updated at: {{version.updated_at}}</p>
                    </div>
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
        Event.$on('hiddenIsActive', function () { that.showVersions = false })
        Event.$on('deletedIsActive', function () { that.showVersions = false })
    },
    methods: {
        onEntryVersionClick(entry_id, id) {
            Event.$emit('entryVersionClick', activeJournal, entry_id, id);
        }
    }
})

Vue.component('search', {
    template: `
        <form>
            <div style="padding-right:8px;">
                <input type="text" class="search-box" placeholder="Search" v-model="searchTerm">
            </div>
            <fieldset class="small-12 columns" style="display: none;">
                <input id="hidden" type="checkbox" v-model="hiddenFlag"><label for="hidden">Hidden</label>
                <input id="deleted" type="checkbox" v-model="deletedFlag"><label for="deleted">Deleted</label>
            </fieldset>
            <div class="grid-x grid-margin-x" style="display: none;">
                <div class="small-6 cell">
                    <label for="date-from"> Date From </label><input id="date-from" type="date">
                </div>
                <div class="small-6 cell">
                    <label for="date-upto"> Date Upto </label><input id="date-upto" type="date">
                </div>
            </div>
            <button class="button" @click="searchButtonClick()"> Search </button>
        </form>
    `,
    data: function() {
        return {
            searchTerm:'',
            hiddenFlag: false,
            deletedFlag: false
        }
    },
    created: function() {

    },
    methods: {
        searchButtonClick() {
            // alert(this.searchTerm);
            // alert(this.hiddenFlag);
            // alert(this.deletedFlag);
            axios.post('api/searchAllEntries', { searchTerm: this.searchTerm, hiddenFlag: this.hiddenFlag })
                .then(response => { console.log(response.data)})
                .catch(function (error) { console.log('fetch me -> ' + error.message) });

        }
    }
})


new Vue({
    el: '#xyz',
    data: {
    }
})


