Nova.booting((Vue, router) => {
    Vue.component('note-text', require('./components/FormField'));
    Vue.component('index-note-text', require('./components/IndexField'));
    Vue.component('note-item', require('./components/NoteItem'));
    Vue.component('note-list', require('./components/NoteList'));
    Vue.component('notes-wrapper', require('./components/NotesWrapper'));
    Vue.component('detail-notes-field', require('./components/DetailField'));
})
