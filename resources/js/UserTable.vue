<template>
    <h1 class="text-2xl uppercase underline font-bold">User Datatable</h1>
    <div>
       <h2 class="text-2xl font-bold">Form</h2>
        <div class="mb-10">
            <input v-model="state.form.last_name" class="my-1 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" id="search" type="text" placeholder="First Name">
            <input v-model="state.form.first_name" class="my-1 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" id="search" type="text" placeholder="Last Name">
            <input v-model="state.form.email" class="my-1 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" id="search" type="email" placeholder="Email">
            <input v-model="state.form.age" class="my-1 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" id="search" type="number" placeholder="Age">
            <input v-model="state.form.hobby" class="my-1 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" id="search" type="text" placeholder="Hobby">
            <button @click="btnAddHandler"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                   type="button">Submit
            </button>
        </div>
    </div>
    <h2 class="text-2xl font-bold">List</h2>
    <DataTable
        ref="userTable"
        @clear="clearFilter"
        :columns="columns"
        :default_sort_direction="default_sort_direction"
        :default_sort_field="default_sort_field"
        :filter="filter"
        :show_entries="show_entries"
        :show_entries_default="show_entries_default"
        :url="url"
    >
        <template v-slot:filters>
            <div class="grid grid-flow-row-dense grid-cols-3 gap-1">
                <input v-model="filter.search.value" class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" id="search" type="text" placeholder="Search">
                <select class="block appearance-none w-full bg-white border  px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" v-model="filter.hobby.value">
                    <option value="">Select Hobby...</option>
                    <option v-for="(hobby, index) in hobbies" :key="index" :value="hobby.hobby"> {{hobby.hobby}} </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
                <button @click="filterHandler" id="btn-filter" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">Filter</button>
            </div>
        </template>
        <template v-slot:options="item">
            <div class="space-x-2">
                <button @click="deleteHandler(item)" id="btn-filter" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">DELETE</button>
                <button @click="editHandler(item)" id="btn-filter" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">EDIT</button>
            </div>
        </template>
    </DataTable>
</template>

<script setup>
import DataTable from "./components/DataTable";
import {ref, reactive} from 'vue';
const props = defineProps({
    show_entries: Array,
    columns: Array,
    hobbies: Array,
    url: String,
    show_entries_default: Number,
    default_sort_field: String,
    default_sort_direction: String,
});

const state = reactive({
    form: {
        first_name: null,
        last_name: null,
        age: null,
        hobby: null,
        email:null
    }
})

const filter = ref({
    search: {
        value: '',
        operator: '%'
    },
    hobby: {
        value: '',
        operator: '='
    }
});
const userTable = ref()
const filterHandler = () => {
    userTable.value.withFilter()
}

const deleteHandler = ({item}) => {
    alert(item.id)
}
const editHandler = ({item}) => {
    alert(item.id)
}
const clearFilter = () => {
    filter.value.search.value = ''
    filter.value.hobby.value = ""
}
const btnAddHandler = () => {
    axios.post('/users', state.form).then((response) => userTable.value.getData()).catch(err => console.log(err))
}
</script>

<style scoped>

</style>
