Vue.component('add-new-journal', {
    template: `
    <form method="GET" @submit.prevent="addJournal" class="add-new-journal-container">
        <input type="text" v-model="name" class="add-new-journal-textbox">
        <button class="button">Add a new journal</button>
    </form>
    `,
    data: function() {
        return {
            name: ''
        }
    },
    methods: {
        addJournal: function() {
            axios.post('api/newJournal', { name: this.name })
                .then(response => console.log(response))
                .catch(function(error) { console.log('JOURNAL -> ' + error.message) });
            fetchJournals();
        }
    }
})

Vue.component('add-new-journal-entry', {
    template: `
    <form method="GET" @submit.prevent="addJournalEntry" class="add-new-journal-container">
        <input type="text" v-model="name" class="add-new-journal-textbox">
        <button class="button">Add a new entry</button>
    </form>
    `,
    data: function() {
        return {
            name: ''
        }
    },
    methods: {
        addJournal: function() {
            axios.post('api/newJournalEntry', { name: this.name })
                .then(response => console.log(response))
                .catch(function(error) { console.log('JOURNAL -> ' + error.message) });
        }
    }
})


$journalList = Vue.component('journal-list', {
    template: `
    <div id="tasks-template">
        <ul>
            <li v-for="journal in list">
                <a href="#" @click="console.log('something');"><i class="fa fa-file-o journal-heading-icon" aria-hidden="true"></i>
                    {{ journal.name }}
                </a>
            </li>
        </ul>
    </div>`,

    data: function () {
        return {
            list: []
        };
    },

    created: function () {
        this.fetchJournals();
    },

    methods: {
        fetchJournals: function () {
            axios.get('api/journals')
                .then(response => this.list = response.data);
        }
    }
})



Vue.component('journal-entry-list', {
    template: `
    <ul>
        <li v-for="journalEntry in journalEntries">
            <a href="#">{{ journalEntry.title }}</a>
        </li>
    </ul>
    `,
    data: function() {
        return {
            journalEntries: []
        };
    },
    created: function() {
        this.fetchJournalEntries();
    },
    methods: {
        fetchJournalEntries: function() {
            axios.get('api/journalEntries')
                .then(response => this.journalEntries = response.data)
                // .then(response => console.log(response))
                .catch(function (error) { console.log('JOURNAL ENTRY -> ' + error.message) });
            // console.log(this.journalEntries);
        }
    }
}) 

new Vue({
    el: '#root', 
    data: {
        journalList: ''
    }
})