Vue.component('add-new-journal', {
    template: `
    <form method="GET" @submit.prevent="addJournal">
        <input type="text" v-model="name">
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
            console.log(this.newJournal);
            axios.post('api/newJournal', { name: this.name })
                .then(response => console.log(response))
                .catch(function(error) { console.log('FUCK -> ' + error.message) });
        }
    }
})


Vue.component('journal-list', {
    template: `
    <div id="tasks-template">
        <ul>
            <li v-for="journal in list">
                <a href="#"><i class="fa fa-file-o journal-heading-icon" aria-hidden="true"></i>
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
            <a href="#">{{ journalEntry.name }}</a>
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
                .then(response => this.journalEntries = response.data);
        }
    }
})

new Vue({
    el: '#root'
})