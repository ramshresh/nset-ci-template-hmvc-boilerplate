/**
 * Created by RamS-NSET on 2/7/2017.
 */
'use strict'
var chk_grps, chkGrp_keys, chk_items, chk_remarks, bcs_rpt_masterData,bcs_rpt_masterCols;
$.when(
    checklists.data(),
    checklistGroups.data(),
    checklistItems.data(),
    checklistRemarks.data(),
    bcsReportsMaster.data(),
    bcsReportsMaster.cols()

).done(function (checklists, checklistGroups, checklistItems, checklistRemarks,bcsReportsMasterData,bcsReportsMasterCols) {
    //One time for a page
    chk_grps = checklistGroups;
    chk_items = checklistItems;
    chk_remarks = checklistRemarks;
    bcs_rpt_masterData = bcsReportsMasterData;
    bcs_rpt_masterCols = bcsReportsMasterCols;


    console.log('bcs_rpt_masterData');
    console.log(bcs_rpt_masterData);
    console.log('bcs_rpt_masterData');

    var filteredItems =chk_items.filter(function(el){
        return el.chk_grp_id == 9;
    });


    chkGrp_keys = [];
    chk_grps.forEach(function (chk_grp) {
        chkGrp_keys.push(chk_grp.key);
    });


    var subCtgColNames = ['yes', 'no', 'unknown', 'not_applicable'];
    var subCtgColNamesColors = ['yes', 'no', 'unknown', 'not_applicable'];

    //request everytime when query District, VDC, From Date, To Date etc  changes
    var chkCols = checklists.columns;
    var chkData = checklists.data;
    var chkLabels = checklists.labels;

    //group by chk_grp
    var chkGrpColName = 'chk_grp';
    var chkData_byChkGrp = groupChkDataByChkGroup(chkData, chkCols, chkGrpColName, chkLabels);
    //console.log(chkData_byChkGrp);

    //make hc_series
    var hc_series = getHcSeries_byItem(subCtgColNames, subCtgColNamesColors, chk_items, 'chk_item', chkData, chkCols);
    //console.log(hc_series);

    //make hc_series total applicable
    var col_names_for_total = ['yes', 'no', 'unknown'];
    var ctg_series_totals = getHcSeriesTotal_byItem(col_names_for_total, chk_items, hc_series);
    //console.log(ctg_series_totals);

    //percentage
    var subCtgColNames_percentage = ['yes', 'no', 'unknown'];
    var hcSeriesPercentage_byChkItem = getHcSeriesPercentage_byItem(subCtgColNames_percentage, subCtgColNames, subCtgColNamesColors, chkCols, 'chk_item', chk_items, chkData, ctg_series_totals);
    //console.log(hcSeriesPercentage_byChkItem);


    var hcSeriesPercentage_byChkGrp = getHcSeriesPercentage_byGroup('chk_grp', chk_grps, chkData_byChkGrp, subCtgColNames_percentage, subCtgColNames, subCtgColNamesColors, chkCols, 'chk_item', chk_items, chkData, ctg_series_totals);
    //console.log(hcSeriesPercentage_byChkGrp);

    //var btype_byChkGroup= getBuildingTypeByChkGrp();



});


// Functions
var groupChkDataByChkGroup = function (chkData, chkCols, chkGrpColName, chkLabels) {
    var chkData_byChkGrp = [];
    chkGrp_keys.forEach(function (grp_key, grp_idx) {
        var sub_item = {};
        sub_item['columns'] = chkCols;
        sub_item['chk_grp'] = grp_key;
        sub_item['data'] = [];
        sub_item['title_text_key'] = grp_key;
        sub_item['title_text_label'] = chkLabels[grp_key];
        sub_item['sub_title_text'] = 'Source BCS';
        chkData.forEach(function (row, i) {
            var grpIdx = chkCols.indexOf(chkGrpColName);
            var grpKey = row[grpIdx];

            if (grpKey == grp_key) {
                sub_item['data'].push(row);
            }
        });
        chkData_byChkGrp.push(sub_item);

    });
    return chkData_byChkGrp;
}

var getColumnData = function (cols, colName, data) {
    var arr = [];
    var idx = cols.indexOf(colName);
    for (var i = 0; i < data.length; i++) {
        arr.push(data[i][idx]);
    }
    return arr;
};

var getHcSeries_byItem = function (subCtgColNames, subCtgColNamesColors, chk_items, chk_item_colName, chkData, chkCols) {
    var series = [];
    var categories = chk_items;//chk_itm;
    var ctgColName = chk_item_colName;//chk_itm;
    var subCtgColNames = subCtgColNames;//['yes', 'no', 'unknown', 'not_applicable'];
    var subCtgColNamesColors = subCtgColNamesColors;//['yes', 'no', 'unknown', 'not_applicable'];
    var data = chkData;
    var cols = chkCols;
    var ctgDataColValues = getColumnData(cols, ctgColName, data);
    for (var i = 0; i < subCtgColNames.length; i++) {
        var subCtgColSortIndex = subCtgColNames.length - i;
        var subCtgColName = subCtgColNames[i];
        var subCtgIdx = cols.indexOf(subCtgColName);

        var ctgIdx = cols.indexOf(ctgColName);

        var dataItem = {};
        dataItem['name'] = subCtgColName;
        dataItem['data'] = [];
        dataItem['cols'] = cols;

        for (var j = 0; j < categories.length; j++) {
            var category = categories[j].key;
            var rIdx = ctgDataColValues.indexOf(category);
            //console.log([rIdx,ctgDataColValues,category,categories,subCtgIdx,]);
            var val =parseInt(data[rIdx][subCtgIdx]);
            //dataItem['data'].push({y:val,color:subCtgColNamesColors[i]});
            dataItem['data'].push(val);
            dataItem['cols'] = subCtgColNames;
            dataItem['color'] = subCtgColNamesColors[i];
            dataItem['index'] = subCtgColSortIndex;

        }
        series.push(dataItem);

    }
    return series;
}

var getHcSeriesTotal_byItem = function (col_names_for_total, chk_items, hc_series) {

    var col_names_for_total = col_names_for_total;//['yes', 'no', 'unknown'];
    //Calculating Totals
    var ctg_series_totals = new Array(chk_items.length).fill(0);
    var series = hc_series;

    chk_items.forEach(function (cd, cIdx) {
        series.forEach(function (sd, sIdx) {
            if (col_names_for_total.indexOf(sd['name']) != -1) {
                ctg_series_totals[cIdx] = ctg_series_totals[cIdx] + sd['data'][cIdx];
            }
        });
    });
    return ctg_series_totals;
};

var getHcSeriesPercentage_byItem = function (subCtgColNames_percentage, subCtgColNames, subCtgColNamesColors, chkCols, chk_item_colName, chk_items, chkData, ctg_series_totals) {
    var subCtgColNames_percentage = subCtgColNames_percentage;//['yes', 'no', 'unknown']
    var subCtgColNamesColors = subCtgColNamesColors;//['yes', 'no', 'unknown']
    var subCtgColNames = subCtgColNames;//['yes', 'no', 'unknown', 'not_applicable']
    var cols = chkCols;//
    var ctgColName = chk_item_colName;
    var data = chkData;
    var ctgDataColValues = getColumnData(cols, ctgColName, data);
    var ctg_series_totals = ctg_series_totals;
    var categories = chk_items;
    var series_percentage = [];
    //preparing percentage series
    for (var i = 0; i < subCtgColNames_percentage.length; i++) {
        var subCtgColSortIndex = subCtgColNames.length - i;
        var subCtgColName = subCtgColNames[i];
        var subCtgIdx = cols.indexOf(subCtgColName);

        var ctgIdx = cols.indexOf(ctgColName);

        var dataItemPercentage = {};
        dataItemPercentage['name'] = subCtgColName;
        dataItemPercentage['categories'] = [];
        dataItemPercentage['categories_sort_index']=[];
        dataItemPercentage['ids'] = [];
        dataItemPercentage['data'] = [];

        for (var j = 0; j < categories.length; j++) {
            var category = categories[j].key;
            var category_sort_index = categories[j].sort_index;
            var category_chk_id = categories[j].id;

            var rIdx = ctgDataColValues.indexOf(category);

            //console.log([data,category,rIdx,subCtgIdx]);
            var val = parseInt(data[rIdx][subCtgIdx]);
            var total = ctg_series_totals[j];
            var percentage = Math.round((val / total) * 100);
            dataItemPercentage['categories_sort_index'].push(category_sort_index);
            dataItemPercentage['ids'].push(category_chk_id);
            dataItemPercentage['categories'].push(category);
            dataItemPercentage['data'].push(percentage);
            dataItemPercentage['color'] = subCtgColNamesColors[i];
            dataItemPercentage['index'] = subCtgColSortIndex;
            dataItemPercentage['total'] = total;
        }
        series_percentage.push(dataItemPercentage);
    }
    return series_percentage;
}

var getHcSeriesPercentage_byGroup = function (chk_grp_colName, chk_grps, chkData_byChkGrp, subCtgColNames_percentage, subCtgColNames, subCtgColNamesColors, chkCols, chk_item_colName, chk_items, chkData, ctg_series_totals) {
    var arr = [];
    var chk_grp_colName = chk_grp_colName;
    var chk_grps = chk_grps;
    var chkData_byChkGrp = chkData_byChkGrp;
    var chk_items_all = chk_items;
    chk_grps.forEach(function (chk_grp) {
        var chk_grp_key = chk_grp.key;
        var chk_grp_id = chk_grp.chk_grp_id;
        var sort_index = chk_grp.sort_index;
        var id = chk_grp.id;
        var dataItem = {};
        dataItem['chk_grp'] = chk_grp_key;
        dataItem['chk_grp_id'] = chk_grp_id;
        dataItem['sort_index'] = sort_index;
        dataItem['id'] = id;
        var chk_items = chk_items_all.filter(function (el) {
            return el.chk_grp_id == chk_grp_id;
        });
        chkData_byChkGrp.forEach(function (chkDataItem_byChkGrp) {
            if (chkDataItem_byChkGrp['chk_grp'] == chk_grp_key) {
                var chkData = chkDataItem_byChkGrp.data;
                var chkDataInPercent = getHcSeriesPercentage_byItem(subCtgColNames_percentage, subCtgColNames, subCtgColNamesColors, chkCols, chk_item_colName, chk_items, chkData, ctg_series_totals);
                dataItem['data'] = chkDataInPercent;
            }
        });
        arr.push(dataItem);
    });
    return arr;
}

var getTotalsBy=function(masterData, config){
    var cfg = {};
    cfg=cfg.extend(config);
    console.log(cfg);
}