<template> <div class="flex flex-col mt-2"> <!-- filters   --> <div slot="filters" class="mb-2"> <slot name="filters"></slot> <p class="text-sm underline mt-2 mx-2 font-bold cursor-pointer" @click="clearFilter" v-if="search">Clear filter</p>
    </div>
    <!-- end filters   -->

    <!--  selected item indicator  -->
    <div v-if="checked.length > 0 && !selectPage" class="flex items-center justify-between space-x-1  w-full">
      <p class="text-sm px-2">Selected items <span class="font-bold">{{ checked.length }}</span></p>
      <slot name="customButton" :items="checked"></slot>
    </div>

    <!--  if selected all item in page  -->
    <div v-if="selectPage" class="flex item-center justify-between">
      <div v-if="selectAllPage">
        <p class="text-sm">You are currently selecting all <span class="font-bold">{{ checked.length }}</span> items</p>
      </div>
      <div v-else>
        <p class="text-sm">
          You have selected <span class="font-bold">{{ checked.length }}</span> items, Do you want to select all items
          {{ items.total }}?
          <a class="text-blue-600 font-bold" href="#" @click.prevent="selectAll">Select All</a>
        </p>
      </div>
      <slot name="customButton" :items="checked"></slot>
    </div>
    <!--  end selected item indicator  -->

    <div class="overflow-x-auto lg:-mx-8">
      <!--  loading  -->
      <div class="flex justify-center items-center space-x-2 text-sm text-gray-700" v-if="loading">
        <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
          </path>
        </svg>
        <span class="text-sm font-bold">Loading</span>
      </div>
      <!-- end loading -->

      <div class="py-2 min-w-full sm:px-6 lg:px-8" v-if="items">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-white border-b">
            <tr>
              <th class="text-xs text-blue-600 px-6 font-extrabold text-left" scope="col">
                <input v-model="selectPage" type="checkbox" value="">
              </th>
              <th v-for="(column, index) in table_columns" :key="index"
                  class="text-xs text-blue-600 px-6 font-extrabold text-left py-2" scope="col">
                <div class="flex space-x-1">
                  <p  v-if="column.sortable" class="underline cursor-pointer uppercase" @click="changeSort(column.field)">{{ column.label }}</p>
                  <div v-if="column.sortable">
                    <span v-if="sort_direction=='desc' && sort_field==column.field ">&uarr;</span>
                    <span v-if="sort_direction=='asc' && sort_field==column.field ">&darr;</span>
                  </div>
                  <div v-else>
                    <p class="uppercase">{{ column.label }}</p>
                  </div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(item, index) in items.data"
                :key="index"
                :class="rowClass(item.id)"
                class="border-b border-gray-400 transition duration-300 ease-in-out hover:bg-gray-100"
                :dusk="`row-${item.id}`"
            >
              <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                <input v-model="checked" :value="item.id" type="checkbox" :id="`checkbox${item.id}`"/>
              </td>
              <template v-for="(column, index) in table_columns" :key="index">
                <td class="text-sm text-gray-900 px-6 py-2 whitespace-nowrap">
                  <p v-if="typeof column.has_slot !== 'undefined' || column?.has_slot == 'false'">
                    <slot :item="item" :name="column.field"></slot>
                  </p>

                  <p v-else> {{ item[column.field] }}</p>
                </td>
              </template>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex justify-end text-xs items-center mx-10 space-x-2" v-if="items">
        <div class="flex space-x-1 items-center">
          <label class="text-sm">Show</label>
          <select id="per_page" v-model="paginate" class="text-sm px-2 py-1 border border-gray-200 rounded"
                  name="per_page">
            <option v-for="(entry, index) in showEntries" :key="index" :value="entry">{{ entry }}</option>
          </select>
          <span class="text-sm">entries</span>
        </div>
        <p class="font-bold" v-if="items">{{items.total}} item/s</p>
        <Pagination :data="items" :limit="5" @pagination-change-page="getData"/>
      </div>
    </div>
  </div>
</template>

<script setup>
  import Pagination from './LaravelVuePagination';
  import { ref, onMounted, watch } from 'vue';

  const props = defineProps({
    show_entries: Array,
    columns: Array,
    url: String,
    show_entries_default: Number,
    default_sort_field: String,
    default_sort_direction: String,
    filter: Object,
    showExport: {
      type: Boolean,
      default: false
    }
  });

  const table_columns = ref(props.columns);
  const items = ref([]);
  const paginate = ref(props.show_entries_default);
  const search = ref("");
  const showEntries = ref(props.show_entries);
  const checked = ref([]);
  const selectPage = ref(false);
  const selectAllPage = ref(false);
  const selectType = ref('');
  const sort_direction = ref(props.default_sort_direction);
  const sort_field = ref(props.sort_field);
  const loading = ref(false);

  const emit = defineEmits(['clear', 'checked'])

  onMounted(() => {
    getData();
  });

  watch(paginate, () => {
    getData();
  })

  watch(checked, (value) => {
    emit('checked')
  })

  watch(selectPage, (value) => {
    checked.value = [];
    if (value) {
      items.value.data.forEach(item => {
        checked.value.push(item.id)
      })
    } else {
      checked.value = [];
      selectAllPage.value = false
    }
  })

  const rowClass = (id) => {
    return isChecked(id) ? 'bg-blue-500' : 'bg-white';
  }

  const selectAll = () => {
    selectType.value = "all"
    getData()
  }

  const getData = (page = 1) => {
    loading.value = true;

    const params = {
      page: page,
      paginate: paginate.value,
      queries: search.value,
      selectType: selectType.value,
      sort_direction: sort_direction.value,
      sort_field: sort_field.value,
    }

    axios.post(props.url, params)
        .then(response => {
          if (selectType.value === 'all') {
            checked.value = response.data.data
            selectAllPage.value = true
            selectType.value = ''
          } else {
            items.value = response.data
          }
        })
        .catch(err => {
          console.log(err.response)
        })
        .finally(()=> {
          loading.value = false
        })
  }

  const withFilter = () => {
    const validateFilterKeys = Object.keys(props.filter)
    let hasValue = 0;
    validateFilterKeys.map(key => {
      if (props.filter[key].value !== '') {
        hasValue++
      }
    })
    if (hasValue <= 0) {
      alert('All filters has no value')
      return
    }
    search.value = props.filter
    getData()
  }

  const clearCheck = () => {
    checked.value = []
    selectPage.value = false;
    selectAllPage.value = false
  }

  const isChecked = (id) => {
    return checked.value.includes(id)
  }

  const clearFilter = () => {
    search.value = ""
    emit('clear')
    getData();
  }

  const changeSort = (field) => {
    if (field === '') {
      return;
    }
    if (sort_field.value === field) {
      sort_direction.value = sort_direction.value === "asc" ? "desc" : "asc";
    } else {
      sort_field.value = field
    }
    getData()
  }

  defineExpose({
    withFilter,
    clearCheck,
    getData
  })
</script>

<style scoped>

</style>
