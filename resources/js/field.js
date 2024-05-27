import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-nova-split-date-input', IndexField)
  app.component('detail-nova-split-date-input', DetailField)
  app.component('form-nova-split-date-input', FormField)
})
