<template>
    <div>
        <b-row>
            <b-col lg="6" class="my-1">
                <b-form-group
                        label="Ordenar por"
                        label-for="sort-by-select"
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        class="mb-0"
                        v-slot="{ ariaDescribedby }"
                >
                    <b-input-group size="sm">
                        <b-form-select
                                id="sort-by-select"
                                v-model="sortBy"
                                :options="sortOptions"
                                :aria-describedby="ariaDescribedby"
                                class="w-75"
                        >
                            <template #first>
                                <option value="">-- none --</option>
                            </template>
                        </b-form-select>

                        <b-form-select
                                v-model="sortDesc"
                                :disabled="!sortBy"
                                :aria-describedby="ariaDescribedby"
                                size="sm"
                                class="w-25"
                        >
                            <option :value="false">Asc</option>
                            <option :value="true">Desc</option>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
                <b-form-group
                        class="mt-2"
                >
                    <b-input-group size="sm">
                        <b-form-input
                                id="filter-input"
                                v-model="filter"
                                type="search"
                                placeholder="Buscar"
                        ></b-form-input>

                        <b-input-group-append>
                            <b-button size="sm" :disabled="!filter" @click="filter = ''">Clear</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
                <div class="row">
                    <div class="col">
                        <button @click="reporteExcel" type="button" class="btn btn-sm btn-success">
                            <i class="mdi mdi-file-excel"></i>
                            <span class="align-self-center">Exportar Excel</span>
                        </button>
                        <button  @click="reportePdf" type="button" class="btn btn-sm btn-danger">
                            <i class="mdi mdi-file-pdf"></i>
                            <span class="align-self-center">Exportar PDF</span>
                        </button>
                    </div>
                </div>
            </b-col>



            <b-col lg="6" class="my-1">
                <b-form-group
                        v-model="sortDirection"
                        label="Filtrar por:"
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        class="mb-0"
                        v-slot="{ ariaDescribedby }"
                >
                    <b-form-checkbox-group
                            v-model="filterOn"
                            :aria-describedby="ariaDescribedby"
                            class="mt-1"
                            :options="filters"
                    >
                    </b-form-checkbox-group>
                </b-form-group>
                <b-row>
                    <b-col lg="4">
                        <b-form-group
                                class="mb-0"
                        >
                            <b-form-select
                                    id="per-page-select"
                                    v-model="pageSize"
                                    :options="pageOptions"
                                    size="sm"
                            ></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col lg="8">
                        <b-pagination
                                v-model="currentPage"
                                :total-rows="length"
                                :per-page="pageSize"
                                align="fill"
                                size="sm"
                                class="my-0"
                        ></b-pagination>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>


        <b-table striped hover
                 :items="items"
                 :per-page="pageSize"
                 :current-page="currentPage"
                 :fields="fields"
                 :filter="filter"
                 :filter-included-fields="filterOn"
                 :sort-by.sync="sortBy"
                 :sort-desc.sync="sortDesc"
                 :sort-direction="sortDirection"
                 stacked="md"
                 show-empty
                 small
                 @filtered="onFiltered"
                 ref="table"
        >
            <slot></slot>
            <template #cell(actions)="row">
                <button v-for="action in row.item.actions" @click="executeCallback(action)" type="button" :class="'btn btn-sm btn-'+action.color">
                    {{action.texto}}
                </button>
            </template>
            <template v-slot:custom-foot="row">
                <tr class="d-none">
                    <td :colspan="fields.length">

                        <download-excel :data="row.items">
                            <button id="reporte-excel" type="button" class="btn btn-sm btn-success">
                                <i class="mdi mdi-file-excel"></i>
                                <span class="align-self-center">Exportar Excel</span>
                            </button>
                        </download-excel>
                        <button  id="reporte-pdf" type="button" class="btn btn-sm btn-danger" @click="exportData(row.items)">
                            <i class="mdi mdi-file-pdf"></i>
                            <span class="align-self-center">Exportar PDF</span>
                        </button>
                    </td>
                </tr>

            </template>
        </b-table>
    </div>

</template>

<script>
    import jsPDF from "jspdf";
    import autoTable from 'jspdf-autotable';
    export default {
        props:[
            'items',
            'length',
            'fields',
            'filters'
        ],
        data(){
          return {
            currentPage:1,
            pageSize: 10,
            sizes:[
                5,10,25,50
            ],
            sortDirection: 'asc',
            sortBy: '',
            sortDesc: false,
            filter: null,
            filterOn: [],
            customize:[],
            pageOptions: [5, 10, 15, 50, 100, {text:"Todos", value: length}]
          }
        },computed: {
            sortOptions() {
                // Create an options list from our fields
                if(!this.fields) return [];

                return this.fields
                    //.filter(f => f.sortable)
                    .map(f => {
                        return { text: f.text, value: f.value, sortable: true }
                    })
            }
        },
        methods:{
            info(item, index, button) {
                this.infoModal.title = `Row index: ${index}`
                this.infoModal.content = JSON.stringify(item, null, 2)
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },
            resetInfoModal() {
                this.infoModal.title = ''
                this.infoModal.content = ''
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            exportData(data){
                const doc = new jsPDF();
                doc.o
                let head =this.fields.map(v => v.text);
                let body = data.map(v=>{
                    return head.map(h => v[h]);
                })
                autoTable(doc, {
                    head: [head],
                    body: body
                })
                doc.save('reporte.pdf')
            },
            reporteExcel(){
                document.getElementById('reporte-excel').click();
            },
            reportePdf(){
                document.getElementById('reporte-pdf').click()
            },
            async executeCallback(action){
                console.log('1');
                await action.callback();
                this.$refs.table.refresh();
            }
        },
        name: "table-index"
    }
</script>

<style scoped>

</style>