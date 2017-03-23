<template>
    <div id="tabularInput">
        <h3>Add Events</h3>     <!-- Button trigger modal -->

        <div class="row">
            <div class="col-sm-6">
                <div class="pull-left">
                    <button class="button btn-primary" data-toggle="modal" data-target="#myModalNorm">+ Add Event
                    </button>
                </div>
            </div>
            <div class="col-sm-6 ">
                <label for="fileInput" style="text-align: right">Import Data: </label><input id="fileInput" type="file"
                                                                                             name="fileInput" size="50"
                                                                                             @change="onFileChange"
                                                                                             style="display: inline"/>

            </div>
        </div>

        <form id="" action="">
            <table class="table  table-hover table-bordered">
                <thead>
                <tr>
                    <td><strong>Title</strong></td>
                    <td><strong>Start Date</strong></td>
                    <td><strong>End Date</strong></td>
                    <td>Actions</td>
                </tr>
                </thead>
                <tbody>
                <tr v-if="db_rows.length>0">
                    <td colspan="4" align="center">Saved Events</td>
                </tr>
                <tr v-for="row in db_rows" :id="row.uid" :data-row_type="row.row_type" class='tr-row_type-db'>
                    <!--<td><input type="text" v-model="row.name"></td>-->
                    <!--<td><input type="text" v-model="row.job"></td>-->
                    <td>{{row.title}}</td>
                    <td>{{row.start_date}}</td>
                    <td>{{row.end_date}}</td>
                    <td><a v-if="row.row_type!='db'" @click="editDbRow(row)">Edit</a></td>
                </tr>
                <tr v-if="new_rows.length>0">
                    <td colspan="4" align="center">New Events</td>
                </tr>
                <tr v-for="row in new_rows" :id="row.uid" :data-row_type="row.row_type" class='tr-row_type-new'>
                    <td><input type="text" v-model="row.title" required></td>
                    <td><input type="date" v-model="row.start_date" required></td>
                    <td><input type="date" v-model="row.end_date" required></td>
                    <td><a v-if="row.row_type!='db'" @click="removeNewRow(row)" class="pull-right">- Remove</a></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="pull-right">
                            <a @click="addRow">+ Add row</a>
                        </div>
                        <div>
                            <button type="submit" class="button btn-primary"
                                    >Save</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>

        <!--<pre>{{rows}}</pre>-->

        <!-- Modal -->
        <div class="modal fade" id="myModalNorm" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            Add new Event
                        </h4>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form role="form" >
                            <div class="form-group">
                                <label for="m_eventTitle">Title</label>
                                <input id="m_eventTitle" type="text" class="form-control" name="title"
                                       placeholder="Title Event"/>
                            </div>
                            <div class="form-group">
                                <label for="m_eventStartDate">Start Date</label>
                                <input type="text" class="form-control"
                                       id="m_eventStartDate" placeholder="yyyy-mm-dd"/>
                            </div>

                            <div class="form-group">
                                <label for="m_eventEndDate">End Date</label>
                                <input type="text" class="form-control"
                                       id="m_eventEndDate" placeholder="yyyy-mm-dd"/>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal">
                                Close
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/babel">
    var XLSX = require('xlsx');

    //var excel2json = require("excel-to-json");
    //xlsjs.readFile();
    export default{
        data(){
            return {
                db_rows: [
                    {
                        uid: "2cf538aa-8fb0-4354-8ffe-b993c12fd1b3",
                        id: "1",
                        title: "1st Mason Training",
                        start_date: "2016-01-01",
                        end_date: "2016-01-02"
                    },
                    {
                        uid: "f9966811-cec6-4eed-819b-3f173ca7756f",
                        id: "2",
                        title: "2nd Mason Training",
                        start_date: "2016-01-03",
                        end_date: "2016-01-05"
                    }
                ],
                new_rows: []
            }
        },
        computed: {
            rows: function () {
                var dbRows = _.map(this.db_rows, function (element) {
                    return _.extend({}, element, {row_type: 'db',});
                });
                var newRows = _.map(this.new_rows, function (element) {
                    return _.extend({}, element, {row_type: 'new'});
                });
                return dbRows.concat(newRows);
            },
            edited_rows: function () {
                _.isEqual()
            }
        },
        methods: {
            makeGUID: function () {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            },
            onFileChange: function (e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;

                this.parseExcel(files[0]);
            },
            parseExcel: function (file) {
                var reader = new FileReader();
                var vm = this;
                reader.onload = function (e) {
                    var data = e.target.result;
                    var workbook = XLSX.read(data, {type: 'binary'});

                    workbook.SheetNames.forEach(function (sheetName) {
                        // Here is your object
                        var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                        var json_object = JSON.stringify(XL_row_object);
                        console.log(json_object);

                        vm.importData(JSON.parse(json_object));
                    });
                };

                reader.onerror = function (ex) {
                    console.log(ex);
                };

                reader.readAsBinaryString(file);
            },
            importData: function (json) {
                var vm = this;
                var data = (typeof json == 'string') ? JSON.parse(json) : json;
                var newRows = _.map(data, function (element) {
                    return _.extend({}, element, {row_type: 'new', uid: vm.makeGUID()});
                });
                this.new_rows = _.concat(this.new_rows, newRows);

            },
            addRow: function () {
                var newId = this.makeGUID();
                this.new_rows.push(
                        {row_type: "new", uid: newId, title: "", start_date: "", end_date: ""}
                );
            },
            editDbRow: function (row) {
                //this.db_rows.splice(_.indexOf(this.db_rows, row), 1);
                alert('now editing');

            }, removeDbRow: function (row) {
                this.db_rows.splice(_.indexOf(this.db_rows, row), 1);
                alert('Are you sure!  This record is in database and will be permanently deleted');

            },
            removeNewRow: function (row) {
                this.new_rows.splice(_.indexOf(this.new_rows, row), 1);
            },
            saveToDb: function () {
                alert('saved');
                $.ajax('http://localhost/nci/api/mt/trainings/save-multiple', {
                    method: 'POST',
                    data: {
                        start_date: this.new_rows[0].start_date
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (jqXHR) {
                        console.log('ERROR: ');
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);

                    }
                })

                var savedRows = this.new_rows;
                this.new_rows = [];

                this.db_rows = _.concat(this.db_rows, savedRows);
            }
        }
    }
</script>
<style>
    .tr-row_type-db {
        background-color: aliceblue;
    }

    .tr-row_type-new {
        background-color: lightyellow;
    }
</style>

