<template>
    <script type="text/x-template" id="dropdown-template">
        <div class="dropdown" v-show="show" v-bind:class="originClass" transition="dropdown">
            <slot>No dropdown content!</slot>
        </div>
    </script>

    <script type="text/x-template" id="datagrid-template">
        <table id="{{ id }}" class="table-striped datagrid">
            <thead>
            <tr>
                <th class="datagrid-toggle-column" v-if="allowSelection">
                    <div class="toggle toggle-checkbox">
                        <input type="checkbox" id="allrows" name="allrows" v-model="selectAll">
                        <label for="allrows"></label>
                    </div>
                </th>
                <th v-for="(index, column) in columns" v-bind:style="{ width: getCellWidth(column) }">
                    <div class="control-group">
                        <div class="datagrid-header control control-fill" v-on:click="sortBy(column)">
                            <span>{{ column.name }}</span>
                            <span class="material-icons icon" v-show="sortingKey === column.key">{{ sortingDirection === 1 ? 'expand_more' : 'expand_less' }}</span>
                        </div>
                        <div class="button-group control" v-if="showOptions && index === (columns.length - 1)">
                            <a id="{{ id }}-options" class="icon-button">
                                <span class="material-icons icon">settings</span>
                            </a>
                            <dropdown v-bind:for="id + '-options'" origin="top left" v-bind:preserve-state="true">
                                <datagrid-options v-bind:grid-id="id" v-bind:columns="columns" v-bind:allow-selection.sync="allowSelection" v-bind:allow-edit.sync="allowEdit" v-bind:data-filter.sync="dataFilter" v-bind:grouping-column.sync="groupingColumn" v-bind:show-advanced-options="showAdvancedOptions">
                                </datagrid-options>
                            </dropdown>
                        </div>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody v-for="(groupName, groupData) in data | filterBy dataFilter | orderBy sortingKey sortingDirection | groupBy groupingColumn.key">
            <tr v-if="groupData.length === 0">
                <td class="text-centre" colspan="{{ columnSpan }}"><strong>No Results</strong></td>
            </tr>
            <tr class="table-group-header" v-if="groupingColumn">
                <td colspan="{{ columnSpan }}">{{ formatData(groupingColumn, groupName) }}</td>
            </tr>
            <tr v-for="(index, row) in groupData">
                <td class="datagrid-toggle-column" v-if="allowSelection">
                    <div class="toggle toggle-checkbox">
                        <input type="checkbox" id="{{ getControlId(groupName, index) }}" name="{{ getControlId(groupName, index) }}" v-bind:value="row" v-model="selectedRows">
                        <label for="{{ getControlId(groupName, index) }}"></label>
                    </div>
                </td>
                <td v-for="column in columns">
                    <partial v-bind:name="getCellTemplate(column)"></partial>
                </td>
            </tr>
            </tbody>
            <tfoot v-if="showFooter">
            <tr>
                <td colspan="{{ columnSpan }}">
                    <ul class="chips">
                        <li class="chip chip-removable" v-show="selectedRows.length > 0" transition="chip">
                            <span class="chip-title">Selection</span>
                            <span class="chip-subtitle">{{ selectedRows.length }} rows selected</span>
                            <a class="chip-remove-button" v-on:click="resetSelection()"></a>
                        </li>
                        <li class="chip chip-removable" v-show="dataFilter" transition="chip">
                            <span class="chip-title">Filtering on</span>
                            <span class="chip-subtitle">{{ dataFilter }}</span>
                            <a class="chip-remove-button" v-on:click="resetFilter()"></a>
                        </li>
                        <li class="chip chip-removable" v-show="groupingColumn" transition="chip">
                            <span class="chip-title">Grouping on</span>
                            <span class="chip-subtitle">{{ groupingColumn.name }}</span>
                            <a class="chip-remove-button" v-on:click="resetGrouping()"></a>
                        </li>
                    </ul>
                </td>
            </tr>
            </tfoot>
        </table>
    </script>

    <script type="text/x-template" id="datagrid-options-template">
        <div class="datagrid-options">
            <div class="datagrid-options-row">
                <input type="search" placeholder="Filter this dataset" v-model="dataFilter" />
            </div>
            <div class="datagrid-options-row" v-if="showAdvancedOptions">
                <div class="toggle toggle-switch">
                    <input type="checkbox" id="{{ gridId }}-allow-selection" name="{{ gridId }}-allow-selection" value="" v-model="allowSelection">
                    <label for="{{ gridId }}-allow-selection"></label>
                </div>
                <label for="{{ gridId }}-allow-selection">Allow Selection</label>
                <div class="toggle toggle-switch">
                    <input type="checkbox" id="{{ gridId }}-allow-edit" name="{{ gridId }}-allow-edit" value="" v-model="allowEdit">
                    <label for="{{ gridId }}-allow-edit"></label>
                </div>
                <label for="{{ gridId }}-allow-edit">Allow Edit</label>
            </div>
            <div class="table-wrapper datagrid-options-row">
                <table>
                    <thead>
                    <tr>
                        <th>Column</th>
                        <th>Group By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>All</td>
                        <td class="text-centre">
                            <div class="toggle toggle-radio">
                                <input type="radio" id="all" name="group-by" value="" v-model="groupingColumn">
                                <label for="all"></label>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="column in columns">
                        <td>{{ column.name }}</td>
                        <td class="text-centre">
                            <div class="toggle toggle-radio">
                                <input type="radio" id="{{ getControlName(column.key, 'grp') }}" name="group-by" v-bind:value="column" v-model="groupingColumn">
                                <label for="{{ getControlName(column.key, 'grp') }}"></label>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </script>

    <div id="index" class="container">
        <section>
            <h1>Vue.JS Datagrid</h1>
        </section>
        <section>
            <strong>Note:</strong> This datagrid uses version 1 of Vue. Version 2 has since been released and I have written a new datagrid component targeting version 2. See the updated version of this datagrid here:
            <a href="https://codepen.io/andrewcourtice/full/woQzpa/" target="_blank">https://codepen.io/andrewcourtice/full/woQzpa/</a>
        </section>
        <section>
            <h2>Features</h2>
            <ul>
                <li>Sorting (Click column header)</li>
                <li>Grouping (Use the advanced options)</li>
                <li>Filtering</li>
                <li>Toggle cell editing</li>
                <li>Toggle row selection</li>
                <li>Custom cell templates (override default for whole grid or just a specific column)</li>
                <li>Custom filters for cell content</li>
            </ul>
        </section>
        <section>
            <div class="table-wrapper">
                <datagrid id="customers-grid"
                          v-bind:columns="customers.columns"
                          v-bind:data="customers.data"
                          v-bind:show-advanced-options="true">

                </datagrid>
            </div>
        </section>
    </div>
</template>