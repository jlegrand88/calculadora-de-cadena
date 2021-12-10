<template>
    <table
        ref="smartTable"
        class="border-2"
    >
        <thead slot="head" >
            <th class="border-2">UUID</th>
            <th class="border-2" >Fecha</th>
            <th class="border-2">Argumento</th>
            <th class="border-2">Resultado</th>
        </thead>
        <tbody slot="body">
            <tr
                v-for="(row, index) in tableRows"
                v-bind:key="index"
                :row="row"
            >
                <td class="border-2">{{ row.uuid }}</td>
                <td class="border-2">{{ row.date }}</td>
                <td class="border-2">{{ row.argument }}</td>
                <td class="border-2">{{ row.result }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>

export default {
    name: 'CalculatedHistoryGrid',
    install: (app, options) => {
        app.use(SmartTable)
    },
    components: {
    },
    data() {
        return {
            tableRows: []
        };
    },
    mounted: function() {
        axios.get('/api/calculatorLog')
        .catch(error => {
            // eslint-disable-next-line
            var e = error;
        })
        .then((response) => {
            console.log('calculator logs');
            console.log(response.data);
            this.tableRows = response.data;
            axios.defaults.headers.common["Authorization"] = 'Bearer '+this.token;
        });
    }
}
</script>

<style>
</style>
