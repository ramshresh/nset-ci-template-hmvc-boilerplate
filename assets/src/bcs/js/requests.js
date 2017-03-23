/**
 * Created by RamS-NSET on 2/7/2017.
 */
var urls_config = {
    checklist_groups: {url: base_url+'api/bcs/checklists/groups', q: {}},
    checklist_items: {url: base_url+'api/bcs/checklists/items', q: {}},
    checklist_remarks: {url: base_url+'api/bcs/checklists/remarks', q: {}},
    reports_master_data: {url: base_url+'api/bcs/reports/master-data', q: {}},
};

var failCallback = function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    $('#error').show();
    $('#loading').hide();
    console.log("Request Failed: " + err + "<br>" + jqxhr.responseText);
};

var checklistGroups = {
    data: function (q) {
        return $.getJSON(urls_config['checklist_groups']['url'], Object.assign({},urls_config['checklist_groups']['q'],q)).then(function (response) {
            return response;
        }).fail(failCallback);
    }
};
var checklistItems = {
    data: function (q) {
        return $.getJSON(urls_config['checklist_items']['url'], Object.assign({},urls_config['checklist_items']['q'],q)).then(function (response) {
            return response;
        }).fail(failCallback);
    }
};
var checklistRemarks = {
    data: function (q) {
        return $.getJSON(urls_config['checklist_remarks']['url'], Object.assign({},urls_config['checklist_remarks']['q'],q)).then(function (response) {
            return response;
        }).fail(failCallback);
    }
};

var bcsReportsMaster = {
    data: function (q) {
        return $.getJSON(urls_config['reports_master_data']['url'], Object.assign({},urls_config['reports_master_data']['q'],q)).then(
            function (response) {
                return response;
            }).fail(failCallback);
    },
    cols: function(q){
        return $.getJSON(urls_config['reports_master_data_cols']['url'], Object.assign({},urls_config['reports_master_data_cols']['q'],q)).then(
            function (response) {
                return response;
            }).fail(failCallback);
    }
};
