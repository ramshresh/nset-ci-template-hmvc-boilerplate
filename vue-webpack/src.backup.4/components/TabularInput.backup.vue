<template>
    <div id="tabularInput">
        <table class="table">
            <thead>
            <tr>
                <td><strong>Name</strong></td>
                <td><strong>Job</strong></td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in rows" :id="row.id" :data-row_type="row.row_type" :class="'tr-row_type-'+row.row_type">
                <td><input type="text" v-model="row.name"></td>
                <td><input type="text" v-model="row.job"></td>
                <td><a v-if="row.row_type!='db'" @click="removeRow(row)">Remove</a></td>
            </tr>
            </tbody>
        </table>

        <pre>{{rows | json}}</pre>

        <div>
            <button class="button btn-primary" @click="addRow">Add row</button>
        </div>
    </div>
</template>
<script type="text/babel">
    export default{
        data(){
            return {
                db_rows:[
                    {id:"1",name: "James Bond", job: "spy"},
                    {id:"2",name: "Goldfinger", job: "villain"}
                ],
                new_rows:[
                ]
            }
        },
        computed: {
            rows: function () {
                var dbRows = _.map(this.db_rows, function(element) {
                    return _.extend({}, element, {row_type: 'db', });
                });
                var newRows = _.map(this.new_rows, function(element) {
                    return _.extend({}, element, {row_type: 'new'});
                });
                return dbRows.concat(newRows);
            }
        },
        methods: {
            makeGUID:function(){
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                    return v.toString(16);
                });
            },
            addRow: function () {
                var newId=this.makeGUID();
                this.new_rows.push(
                        {row_type:"new",id:newId,name: "", job: ""}
                );
            },
            removeRow: function (row) {
                switch (row.row_type){
                    case 'db':
                        this.db_rows.splice(_.indexOf(this.db_rows, row),1);
                            alert('Are you sure!  This record is in database and will be permanently deleted');
                        break;
                    case 'new':
                        this.new_rows.splice(_.indexOf(this.new_rows, row),1);
                        break
                    default:
                        break;
                }

            }
        }
    }
</script>
<style>
    .tr-row_type-db{
        background-color: aliceblue;
    }
    .tr-row_type-new{
        background-color: lightyellow;
    }
</style>