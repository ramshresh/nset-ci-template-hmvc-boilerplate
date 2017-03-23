<?php
/**
 * @var $choices
 * @var $checklists
 * @var $submissions
 *
 * $data['choices']['building_types']
 * $data['choices']['construction_statuses']
 * $data['choices']['districts']
 * $data['choices']['vdc_municipalities']
 * $data['choices']['districts_snm']
 * $data['choices']['vdc_municipalities_snm']
 *
 * $data['checklists']['chk_groups']
 * $data['checklists']['chk_items']
 *
 * $data['submissions']['oldest_date']
 * $data['submissions']['recent_date']
 */
?>
<script src="<?= base_url() ?>assets/hc/code/highcharts.js"></script>
<!--<script src="https://code.highcharts.com/highcharts.js"></script>-->
<!--<script src="https://code.highcharts.com/highcharts-more.js"></script>-->
<script src="<?= base_url() ?>assets/hc/code/js/highcharts-more.js"></script>
<script src="<?= base_url() ?>assets/hc/code/modules/exporting.js"></script>
<style>
	body {
		background-color: lightgrey;
	}

	.container {
		background-color: white;
	}

	.well {
		background-color: lightgoldenrodyellow;
	}

	.hc_spider {
		height: 300px;
		min-width: 200px;
		max-width: 300px;
	}

	.chart-panel {
		background-color: whitesmoke;
		border: 1px;
		margin: 10px;
		padding: 10px;
	}

	.highcharts-title {
		font-size: small;
	}
</style>
<div id="testc"></div>
<div class="row" style="text-align: center">
	<div class="col-md-12">

		<div id="filters" class="panel panel-info">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<label for="districts"> Select District</label>
						<select id="districts" class="save-prev-data">
							<option value="" selected> --------- All ---------</option>
						</select>
					</div>
					<div class="col-md-6">
						<label for="vdcs" class="save-prev-data"> Select VDC/Municipality</label>
						<select id="vdcs">
							<option value="" selected> --------- All ---------</option>
						</select>
					</div>
				</div>
				<br>
				Choose Date:
				<div class="row">
					<div class="col-md-6">
						<label for="from_date" class="save-prev-data"> From: </label>
						<input class="jui-date-picker save-prev-data" type="text" id="from_date">
					</div>
					<div class="col-md-6">
						<label for="to_date" class="save-prev-data"> To: </label>
						<input class="jui-date-picker save-prev-data" type="text" id="to_date">
					</div>
				</div>
				<div class="row">
					<button id="btn_gen_report">Generate Report</button>
				</div>
			</div>
		</div>

	</div>
</div>
<div id="reports" style="display: none">
	<div class="panel panel-info">
		<div class="panel-body container">
			<div id="title" style="font-size: small; font-weight: bolder"></div>
			<div id="btype_cstatus_reports" style="font-size: small; font-weight: bolder"></div>
		</div>
	</div>
</div>


<script src="<?= base_url() ?>assets/src/bcs/js/utils.js"></script>
<script src="<?= base_url() ?>assets/src/bcs/js/requests.js"></script>
<script>
	'use strict';
	var ld, lh;
	$('input.jui-date-picker').datepicker({dateFormat: 'yy-mm-dd'});
	var validateDatesRangeCallback = function (jqFromSelector, jqToSelector) {
		var cur_from_date = $(jqFromSelector).val();
		var cur_to_date = $(jqToSelector).val();
		return (cur_to_date == '' || cur_from_date == '') ? true : cur_to_date >= cur_from_date;

	}
	$('#from_date').on('change', function (e) {
		if (!validateDatesRangeCallback('#from_date', '#to_date')) {
			alert('Invalid Date Range');
			var prev_from_date = $('#from_date').data('val');
			$('#from_date').val(prev_from_date);
		}
	});
	$('#to_date').on('change', function (e) {
		if (!validateDatesRangeCallback('#from_date', '#to_date')) {
			alert('Invalid Date Range');
			var prev_to_date = $('#to_date').data('val');
			$('#to_date').val(prev_to_date);

		}
	});

	$('.save-prev-data').on('focusin', function () {
		$(this).data('val', $(this).val());
	});

	function prepareQueryData() {
		var q = {};
		q['districts'] = [];
		q['vdc_municipalities'] = [];
		if ($("#districts").val() != '') {
			q['districts'].push(($("#districts").val()));
		}
		if (($("#vdcs").val()) != '') {
			q['vdc_municipalities'].push(($("#vdcs").val()));
		}

		q['filename'] = 'BCS_DEPLOYED_V1';
		q['from_date'] = $('#from_date').val();
		q['to_date'] = $('#to_date').val();
		return q;
	}
	function generateReport(q) {
		makeReport();
	}
	$('#btn_gen_report').on('click', function (e) {
		var element = this;
		e.preventDefault();
		var qData = prepareQueryData();
		$('#loading').show();
		generateReport(qData);
	});


	function createUI(uiData) {
		var districts = uiData.districts;
		//var districts_snm = uiData.districts_snm;
		var vdc_municipalities = uiData.vdc_municipalities;
		//var vdc_municipalities_snm = uiData.vdc_municipalities_snm;
		//var date_oldest = uiData.dates.oldest;
		//var date_latest = uiData.dates.recent;
		var date_from = uiData.from_date;
		var date_to = uiData.to_date;
		//var labels = uiData.labels;
		var coverage_district = uiData.coverage_district;
		var coverage_vdc = uiData.coverage_vdc;
		////console.log([districts, districts_snm, vdc_municipalities, vdc_municipalities_snm, date_oldest, date_latest, labels]);

		makeSelectOptions('#districts', districts);
		makeSelectOptions('#vdcs', vdc_municipalities);
		//function to populate child select box


		function makeSelectOptions(selector, array_list) {
			$(selector).html(""); //reset child options
			var defaultLabel = '--------- All ---------';
			var prev_data = $(selector).data('val');
			//console.log(prev_data);
			if (typeof prev_data != 'undefined') {
				$(selector).append("<option value=\"\">" + defaultLabel + "</option>");
			} else {
				$(selector).append("<option value=\"\" selected>" + defaultLabel + "</option>");
			}

			$(array_list).each(function (i) { //populate child options

				if (typeof prev_data != 'undefined' && prev_data == array_list[i].name) {
					$(selector).append("<option value=\"" + array_list[i].name + "\" selected>" + array_list[i].label + "</option>");
				} else {
					$(selector).append("<option value=\"" + array_list[i].name + "\">" + array_list[i].label + "</option>");
				}


			});
		}

		function filterVdcMunicipalities(vdcs, district) {
			var arr = [];
			if (typeof  district == 'undefined' || district == '') {
				arr = arr.concat(vdcs);
			} else if (district instanceof Array) {
				district.forEach(function (d) {
					arr = arr.concat(vdcs.filter(function (el) {
						return el.district == d;
					}));
				});
			} else {
				arr = arr.concat(vdcs.filter(function (el) {
					return el.district == district;
				}));
			}
			return arr;
		}

		$("#districts").change(function () {
			var district = $(this).val(); //get option value from parent
			var vdcs = filterVdcMunicipalities(vdc_municipalities, district);
			makeSelectOptions('#vdcs', vdcs);
		});
	}	;

	var districts = <?php echo json_encode($choices['districts'])?>;
	var vdc_municipalities =  <?php echo json_encode($choices['vdc_municipalities'])?>;
	var date_from =  <?php echo json_encode($submissions['oldest_date'])?>;
	var date_to =  <?php echo json_encode($submissions['recent_date'])?>;

	var uiData = {
		districts: districts,
		vdc_municipalities: vdc_municipalities,
		date_from: date_from,
		date_to: date_to,
		coverage_district: '',
		coverage_vdc: ''
	};
	createUI(uiData);

	///-------------------------------------------------------------------------------------------///
	function makeReport() {
		var qData = prepareQueryData();
		var chk_grps, chk_items, chk_remarks, bcs_rpt_masterData;
		$.when(
			checklistGroups.data(qData),
			checklistItems.data(qData),
			checklistRemarks.data(qData),
			bcsReportsMaster.data(qData)
		).done(function (checklistGroups, checklistItems, checklistRemarks, bcsReportsMasterData) {
			//One time for a page
			chk_grps = checklistGroups;
			chk_items = checklistItems;
			chk_remarks = checklistRemarks;
			bcs_rpt_masterData = bcsReportsMasterData;

			var data = bcsReportsMasterData.data;
			var dataCount_byBtypeCstatus = bcsReportsMasterData.data_count;
			var dataCountTotal = bcsReportsMasterData.data_count_total;

			var coverage_district = bcsReportsMasterData.coverage_district;
			var coverage_vdc = bcsReportsMasterData.coverage_vdc;
			var date_oldest = bcsReportsMasterData.dates.oldest;
			var date_latest = bcsReportsMasterData.dates.recent;
			var date_from = bcsReportsMasterData.dates.from_date;
			var date_to = bcsReportsMasterData.dates.to_date;

			var bTypes = bcs_rpt_masterData.building_types;
			var cStatuses = bcs_rpt_masterData.construction_statuses;

			console.log('bTypes');
			console.log(bTypes);
			console.log('bTypes');

			function countByBtype(dBTCS, bType) {
				if (dBTCS.constructor === Array) {
					var fD = dBTCS.filter(function (d) {
						return d.building_type == bType;
					});
					var total = 0;
					fD.forEach(function (d) {
						total += parseInt(d.count);
					});
					return total;
				}
			};
			function countByBtypeCstatus(dBTCS, bType, cStatus) {
				if (dBTCS.constructor === Array) {
					var fD = dBTCS.filter(function (d) {
						return d.building_type == bType && d.construction_status == cStatus;
					});
					var total = 0;
					fD.forEach(function (d) {
						total += parseInt(d.count);
					});
					return total;
				}
			};
			function countByCstatus(dBTCS, cStatus) {
				if (dBTCS.constructor === Array) {
					var fD = dBTCS.filter(function (d) {
						return d.construction_status == cStatus;
					});
					var total = 0;
					fD.forEach(function (d) {
						total += parseInt(d.count);
					});
					return total;
				}
			};
			function cStatusLabel(cStatus) {
				return cStatuses.filter(function (c) {
					return c.name == cStatus;
				})[0].label;
			};

			function bTypeLabel(bType) {
				return bTypes.filter(function (b) {
					return b.name == bType;
				})[0].label;
			};

			function getSummary_chk_itm_all(data, chk_items) {
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
					if (checkSum != 0) {
						itmS_arr.push(itmSi_obj);
					}

				});

				return itmS_arr;
			}

			function getSummary_chk_grp_all(data, chk_grps, chk_items) {

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
							var p = parseInt(r['percent'][c]);
							gSi_obj['percent_sum'][c] += p;

							if (typeof gSi_obj['percent_min'][c] == 'undefined') {
								gSi_obj['percent_min'][c] = p;
							} else {
								gSi_obj['percent_min'][c] = (p < gSi_obj['percent_min'][c]) ? p : gSi_obj['percent_min'][c];
							}

							if (typeof gSi_obj['percent_max'][c] == 'undefined') {
								gSi_obj['percent_max'][c] = p;
							} else {
								gSi_obj['percent_max'][c] = (p > gSi_obj['percent_max'][c]) ? p : gSi_obj['percent_max'][c];
							}
						});
					});


					percent_cols.forEach(function (pc) {
						var p = Math.round((gSi_obj['percent_sum'][pc] / gSi_obj['chk_itm_nos'][pc]));
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

			function getSummary_chkGrp_for_bType(data, bType, cStatus) {

				var f_chk_grps = chk_grps.filter(function (obj) {
					return obj['BT' + bType] == 1 && obj['CS' + cStatus] == 1;
				});

				var f_chk_items = chk_items.filter(function (obj) {
					return obj['BT' + bType] == 1 && obj['CS' + cStatus] == 1;
				});
				var fD = data.filter(function (d) {
					return d.building_type == bType && d.construction_status == cStatus;
				});

				var chk_grp_summary_arr = getSummary_chk_grp_all(fD, chk_grps, chk_items);

				var cntByBtype = {};
				cntByBtype['building_type'] = bType;
				cntByBtype['construction_status'] = cStatus;
				cntByBtype['building_type_label'] = bTypeLabel(bType);
				cntByBtype['construction_status_label'] = cStatusLabel(cStatus);
				cntByBtype['chk_grp_summary_arr'] = chk_grp_summary_arr;
				cntByBtype['total'] = countByBtypeCstatus(dataCount_byBtypeCstatus, bType, cStatus);


				chk_grp_summary_arr.forEach(function (cgs) {
					///////////////////////////////////////////
				});
				return cntByBtype;
			};

			function getSummary_chkGrp_for_bTypeAll(data, bTypes, cStatuses) {
				//var cnt_by_Btype = getSummary_chk_grp_all(data);

				var bType_ChkGrpSummary_arr = [];
				bTypes.forEach(function (b) {
					var bType = b.name;
					var chkGrp_for_bType = {};
					chkGrp_for_bType['building_type'] = bType;
					chkGrp_for_bType['label'] = bTypeLabel(bType);
					chkGrp_for_bType['total'] = countByBtype(dataCount_byBtypeCstatus, bType);
					chkGrp_for_bType['construction_statuses'] = [];

					cStatuses.forEach(function (c) {
						var cStatus = c.name;
						//console.log('bType: '+bType+' |  '+'cStatus:  '+ cStatus);
						var fD = data.filter(function (d) {
							return d.building_type == bType && d.construction_status == cStatus;
						});
						var chkGrp_for_bType_cStatus_summary = getSummary_chkGrp_for_bType(fD, bType, cStatus);

						chkGrp_for_bType['construction_statuses'].push(chkGrp_for_bType_cStatus_summary);


					});

					bType_ChkGrpSummary_arr.push(chkGrp_for_bType);
				});
				return bType_ChkGrpSummary_arr;
			}

			var summary_byBtype_arr = getSummary_chkGrp_for_bTypeAll(data, bTypes, cStatuses);

			console.log('summary_byBtype_arr');
			console.log(summary_byBtype_arr);
			console.log('summary_byBtype_arr');

			var coverage_district_label = '';
			var coverage_vdc_label = '';
			console.log(coverage_district);
			console.log(coverage_vdc);
			if (coverage_district == '') {
				coverage_district_label = 'All';
			} else {
				districts.forEach(function (d) {
					if (d.name == parseInt(coverage_district[0].replace('\'', ''))) {
						coverage_district_label = d.label;
					}
				});
			}

			if (coverage_vdc == '') {
				coverage_vdc_label = 'All';
			} else {
				vdc_municipalities.forEach(function (v) {
					if (v.name == parseInt(coverage_vdc[0].replace('\'', ''))) {
						coverage_vdc_label = v.label;
					}
				});
			}
			var title =
				'<div class="row" style="font-size: small">' +
				'<div class="col-md-6" style="float: left;"><p>' + 'District: ' + coverage_district_label + '</p>'
				+ '<p>' + 'Vdc/Municipality: ' + coverage_vdc_label + '</p></div>'
				+ '<div class="col-md-6" style="float: right"><p>' + 'From: ' + date_from + '</p>'
				+ '<p>' + 'To: ' + date_to + '</p></div>' +
				'</div>';

			$('#title').html(title);

			var chartDataArr = [];
			var legendDataArr = [];
			var legendChkItemDataArr = [];
			var btype_cstatus_reports_html = '<h5>Detail Reports</h5><hr>';

			//prepare
			summary_byBtype_arr.forEach(function (smry_btype) {
				var bType = smry_btype.building_type;
				var bType_total = smry_btype.total;
				var bType_label = smry_btype.label;
				var cStatuses = smry_btype.construction_statuses;

				cStatuses.forEach(function (cs) {
					var cStatus = cs.construction_status;
					var cStatus_label = cs.construction_status_label;
					var cStatus_total = cs.total;
					var spDivAvg = 'sp-avg-' + bType + '-' + cStatus;
					var spDivTable = 'sp-leg-' + bType + '-' + cStatus;
					var spDivTableChkItm = 'sp-itmtbl-' + bType + '-' + cStatus;
					var spDivMinMax = 'sp-min-max-' + bType + '-' + cStatus;
					btype_cstatus_reports_html += '<hr><div style="text-align: center"><h4><strong>' + bType_label + '</strong></h4> [' + bType_total + ' buildings]<br></div><br>';
					btype_cstatus_reports_html += '<h5>Construction Status<strong>' + cStatus_label + '</strong></h5>[' + cStatus_total + ' buildings]';
					btype_cstatus_reports_html += '<div class="row" >' +
						'<div id="' + spDivAvg + '" class="col-md-6 chart-panel" ></div>' +
						'<div id="' + spDivTable + '" class="col-md-5 chart-panel" ></div>' +
						'</div>';
					btype_cstatus_reports_html+='<div class="row">' +
					'<div id="' + spDivTableChkItm + '" class="col-md-11 chart-panel" ></div>' +
					'</div>';
					var categories = [];
					var legendHtmlItems = [];
					var legendHtmlChkItmItems = [];
					var grp_summary_arr = cs.chk_grp_summary_arr;
					grp_summary_arr.forEach(function (cg) {
						var shortCode = 'G' + cg.chk_grp_id;
						categories.push(shortCode);
						legendHtmlItems.push({
							short_code: shortCode,
							label: cg.label,
							key: cg.key,
							min: cg.percent_min.yes,
							max: cg.percent_max.yes,
							avg: cg.percent_avg.yes,
							chk_nos: cg.chk_itm_nos.yes,
							//min: cg.key,
							chk_grp_id: cg.chk_grp_id
						});

						var chk_itms=cg.children;
						chk_itms.forEach(function (c) {
							var shortCode = 'I-'+ c.chk_itm_id;
							legendHtmlChkItmItems.push(
								{
									short_code:shortCode,
									label: c.label,
									key: c.key,
									count_yes: c.count.yes,
									count_no: c.count.no,
									count_unknown: c.count.unknown,
									count_total: c.total,
									count_not_applicable: c.count.not_applicable,
									percent_yes: c.percent.yes,
									percent_no: c.percent.no,
									percent_unknown: c.percent.unknown
								}
							);
						});
					});


					var legendHtml = $('<table class="table table-bordered table-responsive table-striped"><caption>Checklist Group Details</caption></table>').addClass('datatable');
					legendHtml.append('<thead><tr><th>Code</th><th>Label</th><th>Min(%)</th><th>Max(%)</th></tr></thead>');
					legendHtmlItems.forEach(function (leg) {
						var rHtml = '<td>' + leg.short_code + '</td>' +
							'<td>' + leg.label + '<p style="font-size: small; font-style: italic">(' + leg.chk_nos + ' items)</p>' + '</td>' +
							'<td>' + leg.min + '</td>' +
							'<td>' + leg.max + '</td>';
						var row = $('<tr></tr>').addClass('table-row').html(rHtml);
						legendHtml.append(row);
					});
					console.log('legendHtml');
					console.log(legendHtml);
					console.log('legendHtml');
					legendDataArr.push([spDivTable, legendHtml]);


					var legendChkItmHtml = $('<table class="table table-bordered table-responsive table-striped"><caption>Checklist Details</caption></table>').addClass('datatable');
					legendChkItmHtml.append('<thead><tr><th>Code</th><th>Label</th><th>Yes</th><th>No</th><th>Unknown</th><th>Not Applicable</th><th>Total</th><th>Yes(%)</th><th>No(%)</th><th>Unknown(%)</th></tr></thead>');
					legendHtmlChkItmItems.forEach(function (leg) {
						var rHtml = '<td>' + leg.short_code + '</td>' +
							'<td>' + leg.label +  '</td>' +
							'<td>' + leg.count_yes + '</td>' +
							'<td>' + leg.count_no + '</td>' +
							'<td>' + leg.count_unknown + '</td>' +
							'<td>' + leg.count_not_applicable + '</td>' +
							'<td>' + leg.count_total + '</td>' +
							'<td>' + leg.percent_yes + '</td>' +
							'<td>' + leg.percent_no + '</td>' +
							'<td>' + leg.percent_unknown + '</td>';
						var row = $('<tr></tr>').addClass('table-row').html(rHtml);
						legendChkItmHtml.append(row);
					});
					console.log('legendChkItmHtml');
					console.log(legendChkItmHtml);
					console.log('legendChkItmHtml');
					legendChkItemDataArr.push([spDivTableChkItm, legendChkItmHtml]);




					var sp_series_names = ['yes'];
					var spider_series_all = [];
					var spider_series_average = [];
					var spider_series_min = [];
					var spider_series_max = [];
					sp_series_names.forEach(function (sp_name, i) {
						var spider_series_item = {};
						var spider_series_min_item = {};
						var spider_series_max_item = {};
						spider_series_item['name'] = sp_name + ' - average';
						spider_series_min_item['name'] = sp_name + ' - minimum';
						spider_series_max_item['name'] = sp_name + ' - maximum';

						spider_series_item['pointPlacement'] = 'on';
						spider_series_min_item['pointPlacement'] = 'on';
						spider_series_max_item['pointPlacement'] = 'on';

						spider_series_item['data'] = [];
						spider_series_min_item['data'] = [];
						spider_series_max_item['data'] = [];
						grp_summary_arr.forEach(function (gss) {
							spider_series_item['data'].push(gss.percent_avg[sp_name]);
							spider_series_min_item['data'].push(gss.percent_min[sp_name]);
							spider_series_max_item['data'].push(gss.percent_max[sp_name]);
						});
						spider_series_average.push(spider_series_item);
						spider_series_min.push(spider_series_min_item);
						spider_series_max.push(spider_series_max_item);

						spider_series_all.push(spider_series_item);
						spider_series_all.push(spider_series_min_item);
						spider_series_all.push(spider_series_max_item);
					});

					var chartDivClass = 'hc_spider';
					var chartDivId = spDivAvg;
					var $chSpAvg = $("<div>", {"id": chartDivId, "class": chartDivClass});
					$("#" + spDivAvg).append($chSpAvg);

					var $legSpAvg = $("<div>", {"id": spDivTable});
					$("#" + spDivTable).append($legSpAvg);


					//var cTitleHtml = '<div class="ch-title">Average Pass Percentage(' + bType_label + '-' + cStatus_label + ')</div>';
					var chartData = [chartDivId, 'Pass Percentage(' + bType_label + '-' + cStatus_label + ')', categories, spider_series_average];
					//var chartData = [chartDivId, 'Pass Percentage(' + bType_label + '-' + cStatus_label + ')', categories, spider_series_all];
					chartDataArr.push(chartData);


					console.log(cs);
					console.log(spider_series_average);
				});
			});

			///
			function makeSp(chartDivId, title, categories, series) {

				console.log('[chartDivId, title, categories, series]');

				console.log([chartDivId, title, categories, series]);
				return Highcharts.chart(chartDivId, {

					chart: {
						polar: true,
						type: 'line'
					},

					title: {
						text: title,
						x: 0,
						align: "center",
						style: {fontFamily: ' "Exo 2", sans-serif', fontSize: "small", width: "30px"},
						backgroundColor: "#FFFFFF"
					},

					pane: {
						size: '80%'
					},

					xAxis: {
						categories: categories,
						tickmarkPlacement: 'on',
						lineWidth: 0
					},

					yAxis: {
						gridLineInterpolation: 'polygon',
						lineWidth: 0,
						min: 0
					},

					tooltip: {
						shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
					},

					legend: {
						align: 'right',
						verticalAlign: 'top',
						y: 70,
						layout: 'vertical'
					},

					plotOptions: {
						line: {
							dataLabels: {
								useHTML: true,
								enabled: true,
								allowOverlap: true,
								//backgroundColor:'rgba(0,0,0,0.9)',
								formatter: function () {
									return '<span style="color:' + shadeColor(this.point.color, 0.2) + '">' + Math.round(this.point.y) + '</span>';

								}

							},
							enableMouseTracking: false
						}
					},
					series: series

				});

			}


			btype_cstatus_reports_html += '<hr>';

			$('#btype_cstatus_reports').html(btype_cstatus_reports_html);

			chartDataArr.forEach(function (cd) {
				try {
					makeSp(cd[0], cd[1], cd[2], cd[3]);
				} catch (e) {

					console.log(cd[0]);
					console.log($(cd[0]));

					$('#loading').hide();
					//alert('Error rendering chart');
					console.log('Error making chart');
					console.log(e);
				}

			});

			legendDataArr.forEach(function (leg) {
				$('#' + leg[0]).append(leg[1]);
			});
			legendChkItemDataArr.forEach(function (leg) {
				$('#' + leg[0]).append(leg[1]);
				console.log(leg[0]);
				console.log(leg);
				console.log(leg[1]);
			});
			$('#reports').show();
			$('#loading').hide();
		});
	}

</script>
