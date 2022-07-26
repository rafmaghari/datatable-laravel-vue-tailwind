import UserTable from "./UserTable";

require('./bootstrap')

import { createApp } from 'vue'

const app = createApp({})

app.component('user-table', UserTable)

app.mount('#app')
