/**
 * Created by RamS-NSET on 2/7/2017.
 */
'use strict'



var chk_grps, chk_items, chk_remarks, bcs_rpt_masterData;
$.when(
    checklistGroups.data(),
    checklistItems.data(),
    checklistRemarks.data(),
    bcsReportsMaster.data()
).done(function (checklistGroups, checklistItems, checklistRemarks, bcsReportsMasterData) {
    //One time for a page
    chk_grps = checklistGroups;
    chk_items = checklistItems;
    chk_remarks = checklistRemarks;
    bcs_rpt_masterData = bcsReportsMasterData;

    var data = bcsReportsMasterData.data;
    var dataCount_byBtypeCstatus = bcsReportsMasterData.data_count;
    var dataCountTotal = bcsReportsMasterData.data_count_total;

    var bTypes = bcs_rpt_masterData.building_types;
    var cStatuses = bcs_rpt_masterData.construction_statuses;

    var countByBtype = function(dBTCS,bType){
        var fD=dBTCS.filter(function(d){
           return d.building_type == bType;
        });
        var total=0;
        fD.forEach(function(d){
            total += parseInt(d.count);
        });
        return total;
    };
    var countByBtypeCstatus = function(dBTCS,bType, cStatus){
        var fD = dBTCS.filter(function(d){
            return d.building_type == bType && d.construction_status == cStatus;
        });
        var total=0;
        fD.forEach(function(d){
            total += parseInt(d.count);
        });
        return total;
    };
    var countByCstatus=function(dBTCS,cStatus){
        var fD = dBTCS.filter(function(d){
            return d.construction_status == cStatus;
        });
        var total=0;
        fD.forEach(function(d){
            total += parseInt(d.count);
        });
        return total;
    };
    var cStatusLabel=function(cStatus){
        return cStatuses.filter(function(c){
            return c.name == cStatus;
        })[0].label;
    };

    var bTypeLabel=function(bType){
        return bTypes.filter(function(b){
            return b.name == bType;
        })[0].label;
    };

    var getSummary_chk_itm_all = function (data, chk_items) {
        var itmS_arr = [];
        chk_items.forEach(function (itm) {
            //console.log('checking for  chk_itm ['+ itm.chk_itm_id+'] '+ itm.key);
            var itmSi_obj = {}
            itmSi_obj['type'] = 'chk_itm';
            itmSi_obj['id'] = itm.id;
            itmSi_obj['sort_index'] = itm.sort_index;
            itmSi_obj['key'] = itm.key;
            itmSi_obj['label'] = itm.label;
            itmSi_obj['obj'] = itm;
            itmSi_obj['chk_grp_id'] = itm.chk_grp_id;
            itmSi_obj['chk_itm_id'] = itm.chk_itm_id;
            itmSi_obj['count'] = {};
            itmSi_obj['total'] = 0;
            itmSi_obj['not_applicable'] = 0;
            itmSi_obj['percent'] = {};

            /*  console.log('-------');
             console.log(itm);*/
            var fD = data.filter(function (d) {
                return d.chk_itm_id == itm.chk_itm_id;//&& d.chk_grp_id == itm.chk_grp_id;
            });

            /*console.log('the fD is:');
             console.log(fD);*/

            var count_cols = ['yes', 'no', 'unknown', 'not_applicable'];
            var cols_for_total = ['yes', 'no', 'unknown'];

            count_cols.forEach(function (c) {
                itmSi_obj['count'][c] = 0;
                itmSi_obj['count']['applicable'] = 0;
                //console.log('adding for  chk_itm ['+ itm.chk_itm_id+'] '+ '['+c+']');
                fD.forEach(function (r) {
                    /*
                     console.log('data item');
                     console.log(r);
                     console.log('adding for  chk_itm ['+ r.chk_itm_id+'] '+'of chk_grp_id['+ r.ckh_grp_id+']'+ '['+c+']'+' add '+ itmSi_obj['count'][c]+' + '+r[c]);
                     */
                    itmSi_obj['count'][c] += parseInt(r[c]);
                    itmSi_obj['count']['applicable'] += parseInt(r['applicable']);
                    if (typeof cols_for_total != 'undefined' && cols_for_total.length > 0) {
                        //The columns for total has been overridden
                        if (cols_for_total.indexOf(c) != -1) {
                            itmSi_obj['total'] += parseInt(r[c]);
                        }
                    } else {
                        //using default sets of column for total i.e ['yes','no','unknown']
                        itmSi_obj['total'] += parseInt(r['applicable']);
                    }

                });
            });


            //calculate percentage
            count_cols.forEach(function (c) {
                itmSi_obj['percent'][c] = Math.round((itmSi_obj['count'][c] / itmSi_obj['total']) * 100);
            });

            var checkSum_cols = ['yes', 'no', 'unknown'];
            var checkSum = 0;
            checkSum_cols.forEach(function (c) {
                checkSum += itmSi_obj['count'][c];
            });
            if (checkSum != 0){itmS_arr.push(itmSi_obj);}

        });

        return itmS_arr;
    }
    var getSummary_chk_grp_all = function (data,chk_grps, chk_items) {

        var chk_itm_summary_arr = getSummary_chk_itm_all(data, chk_items);
        var gS_arr = [];
        chk_grps.forEach(function (g) {
            //console.log('checking for  chk_grp ['+ g.chk_grp_id+'] '+ g.key);
            var gSi_obj = {}
            gSi_obj['type'] = 'chk_grp';
            gSi_obj['id'] = g.id;
            gSi_obj['chk_grp_id'] = g.chk_grp_id;
            gSi_obj['key'] = g.key;
            gSi_obj['label'] = g.label;
            gSi_obj['sort_index'] = g.sort_index;
            gSi_obj['count'] = {}
            gSi_obj['percent_sum'] = {};
            gSi_obj['percent_avg'] = {};
            gSi_obj['percent_min'] = {};
            gSi_obj['percent_max'] = {};
            gSi_obj['chk_itm_nos'] = {};


            var fD = chk_itm_summary_arr.filter(function (d) {
                return d.chk_grp_id == g.chk_grp_id;
            });

            gSi_obj['children'] = fD;
            gSi_obj['chk_itm_ids'] = [];
            fD.forEach(function (d) {
                gSi_obj['chk_itm_ids'].push(d.chk_itm_id);
            });


            var count_cols = ['yes', 'no', 'unknown'];
            var percent_cols = ['yes', 'no', 'unknown'];


            count_cols.forEach(function (c) {
                gSi_obj['percent_sum'][c] = 0;
                gSi_obj['chk_itm_nos'][c] = 0;
                //console.log('adding for  chk_grp ['+ g.chk_grp_id+'] '+ '['+c+']');
                var chkItmPercentSum = 0;
                fD.forEach(function (r) {
                    //console.log('adding for  chk_grp ['+ r.chk_grp_id+'] '+'chk_itm_id['+ r.ckh_itm_id+']'+ '['+c+']'+' add '+ gSi_obj['percent'][c]+' + '+r[c]);
                    gSi_obj['chk_itm_nos'][c] += 1;
                    var p=parseInt(r['percent'][c]);
                    gSi_obj['percent_sum'][c] += p;

                    if(typeof gSi_obj['percent_min'][c] == 'undefined'){
                        gSi_obj['percent_min'][c]=p;
                    }else{
                        gSi_obj['percent_min'][c]=(p<gSi_obj['percent_min'][c])?p:gSi_obj['percent_min'][c];
                    }

                    if(typeof gSi_obj['percent_max'][c] == 'undefined'){
                        gSi_obj['percent_max'][c]=p;
                    }else{
                        gSi_obj['percent_max'][c]=(p>gSi_obj['percent_max'][c])?p:gSi_obj['percent_max'][c];
                    }
                });
            });


            percent_cols.forEach(function (pc) {
                var p=Math.round((gSi_obj['percent_sum'][pc] / gSi_obj['chk_itm_nos'][pc]));
                gSi_obj['percent_avg'][pc] = p;
            });




            if (isNaN(gSi_obj['percent_avg']['yes']) || isNaN(gSi_obj['percent_avg']['no']) || isNaN(gSi_obj['percent_avg']['unknown'])) {
                //Skip
            } else {
                gS_arr.push(gSi_obj);
            }


        });
        return gS_arr;
    };

    /*console.log('bcs_rpt_masterData');
    console.log(bcs_rpt_masterData);
    console.log('bcs_rpt_masterData');*/
    var getSummary_chkGrp_for_bType = function (data, bType, cStatus) {

        var f_chk_grps = chk_grps.filter(function (obj) {
            return obj['BT' + bType] == 1 && obj['CS' + cStatus] == 1;
        });

        var f_chk_items = chk_items.filter(function (obj) {
            return obj['BT' + bType] == 1 && obj['CS' + cStatus] == 1;
        });
        var fD = data.filter(function (d) {
            return d.building_type == bType && d.construction_status == cStatus;
        });

        var chk_grp_summary_arr = getSummary_chk_grp_all(fD,chk_grps, chk_items);

        var cntByBtype = {};
        cntByBtype['building_type'] = bType;
        cntByBtype['construction_status'] = cStatus;
        cntByBtype['building_type_label']=bTypeLabel(bType);
        cntByBtype['construction_status_label']=cStatusLabel(cStatus);
        cntByBtype['chk_grp_summary_arr'] = chk_grp_summary_arr;
        cntByBtype['total']=countByBtypeCstatus(dataCount_byBtypeCstatus,bType,cStatus);



        chk_grp_summary_arr.forEach(function (cgs) {
            ///////////////////////////////////////////
        });
        return cntByBtype;
    };

    var getSummary_chkGrp_for_bTypeAll = function(data, bTypes, cStatuses){
        //var cnt_by_Btype = getSummary_chk_grp_all(data);

        var bType_ChkGrpSummary_arr =[];
        bTypes.forEach(function(b){
            var bType = b.name;
            var chkGrp_for_bType={};
            chkGrp_for_bType['building_type']=bType;
            chkGrp_for_bType['label']=bTypeLabel(bType);
            chkGrp_for_bType['total'] = countByBtype(dataCount_byBtypeCstatus, bType);
            chkGrp_for_bType['construction_statuses']=[];

            cStatuses.forEach(function(c){
                var cStatus = c.name;
                //console.log('bType: '+bType+' |  '+'cStatus:  '+ cStatus);
                var fD = data.filter(function (d) {
                    return d.building_type == bType && d.construction_status == cStatus;
                });
                var chkGrp_for_bType_cStatus_summary = getSummary_chkGrp_for_bType(fD, bType,cStatus);

                chkGrp_for_bType['construction_statuses'].push(chkGrp_for_bType_cStatus_summary);


            });

            bType_ChkGrpSummary_arr.push(chkGrp_for_bType);
        });
        return bType_ChkGrpSummary_arr;
    }




   var summary_byBtype_arr= getSummary_chkGrp_for_bTypeAll(data, bTypes, cStatuses);

    console.log('summary_byBtype_arr');
    console.log(summary_byBtype_arr);
    console.log('summary_byBtype_arr');


});

