<template>
    <div class="query-panel">
        <li>
            <select id="qDistrict" class="form-control" v-on:change="onDistrictChange">
                <option v-for="district in optDistricts" v-bind:value="district.name">
                    {{ district.label }}
                </option>
            </select>
        </li>
        <li>
            <select id="qVdc" class="form-control" v-on:change="onVdcChange" disabled="true">
                <option v-for="vdc in optVdcs"
                        v-bind:selected="vdc.name == coverage_vdc">
                    {{ vdc.label }}
                </option>
            </select>
        </li>
        <li>
            <button id="btn_gen_report" class="btn btn-info form-control">Generate Report</button>
        </li>
    </div>
</template>
<script type="text/babel">
    export default{
        data(){
            return {
                districts: [{
                    "name": "22",
                    "label": "Dolakha"
                }, {"name": "28", "label": "Nuwakot"}, {"name": "30", "label": "Dhading"}],
                vdc_municipalities: [
                    {
                        "vdc_or_muni": "vdc",
                        "name": "30009",
                        "district": "30",
                        "label": "Darkha",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30012",
                        "district": "30",
                        "label": "Dhuwakot",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30019",
                        "district": "30",
                        "label": "Jyamrung",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30020",
                        "district": "30",
                        "label": "Kalleri",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30023",
                        "district": "30",
                        "label": "Khalte",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30026",
                        "district": "30",
                        "label": "Kumpur",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30030",
                        "district": "30",
                        "label": "Marpak",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30033",
                        "district": "30",
                        "label": "Nalang",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "municipality",
                        "name": "30035",
                        "district": "30",
                        "label": "Nilkantha"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30044",
                        "district": "30",
                        "label": "Semjong",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30045",
                        "district": "30",
                        "label": "Sertung",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "30049",
                        "district": "30",
                        "label": "Tipling",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22001",
                        "district": "22",
                        "label": "Alampu",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22002",
                        "district": "22",
                        "label": "Babare",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "municipality",
                        "name": "22004",
                        "district": "22",
                        "label": "Bhimeshwor Municipality"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22005",
                        "district": "22",
                        "label": "Bhirkot",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22007",
                        "district": "22",
                        "label": "Bigu",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22012",
                        "district": "22",
                        "label": "Chilankha",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22013",
                        "district": "22",
                        "label": "Chyama",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22021",
                        "district": "22",
                        "label": "Japhe",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22022",
                        "district": "22",
                        "label": "Jhule",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22028",
                        "district": "22",
                        "label": "Katakuti",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22031",
                        "district": "22",
                        "label": "Laduk",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22034",
                        "district": "22",
                        "label": "Lamidanda",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22036",
                        "district": "22",
                        "label": "Magapauwa",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "22038",
                        "district": "22",
                        "label": "Malu",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "municipality",
                        "name": "28008",
                        "district": "28",
                        "label": "Bidur Municipality"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28012",
                        "district": "28",
                        "label": "Chaturale",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28015",
                        "district": "28",
                        "label": "Chhap",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28037",
                        "district": "28",
                        "label": "Likhu",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28039",
                        "district": "28",
                        "label": "Mahakali",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28049",
                        "district": "28",
                        "label": "Samundradevi",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28052",
                        "district": "28",
                        "label": "Sikre",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28056",
                        "district": "28",
                        "label": "Talakhu",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28058",
                        "district": "28",
                        "label": "Thanapati",
                        "max_ward_no": "9"
                    }, {
                        "vdc_or_muni": "vdc",
                        "name": "28059",
                        "district": "28",
                        "label": "Thansing",
                        "max_ward_no": "9"
                    }
                ],

                defaultDistricts: [{"name": "", "label": "Select District (All)"}],
                defaultVdcs: [{
                    "name": "",
                    "label": "Select Vdc/Municipality (All)",
                    "district": "",
                    "max_ward_no": ""
                }],

                optVdcs: [],
                optDistricts: [],

                coverage_district: '',
                coverage_vdc: '',

            }
        },
        computed: {
            'q': function () {
                return {
                    districts: [this.sDistrict],
                    vdc_municipalities: [this.sVdc_municipality]
                };
            }
        },
        methods: {
            onDistrictChange: function (e) {
                var district = event.target.value;
                this.q.d = district
                this.setOptVdcs(this.filterVdc(district));
                if (district == '') {
                    this.disableSelectVdc();
                } else {
                    this.enableSelectVdc();
                }
                ;

            },
            onVdcChange: function (e) {
                this.enableSelectVdc();
            },
            enableSelectVdc: function () {
                document.getElementById('qVdc').disabled = false;
            },
            disableSelectVdc: function () {
                document.getElementById('qVdc').disabled = true;
            },
            filterVdc: function (district) {
                return this.vdc_municipalities.filter(function (el) {
                    return el.district == district;
                });
            },
            setOptVdcs: function (vdcs) {
                var vdcs = (typeof vdcs != 'undefined') ? vdcs : this.filterVdc('');
                this.optVdcs = this.defaultVdcs.concat(vdcs);
            },
            setOptDistricts: function (districts) {
                var districts = (typeof districts != 'undefined') ? districts : this.districts;
                this.optDistricts = this.defaultDistricts.concat(this.districts);
            }
        },
        created: function () {
            this.setOptDistricts();
            this.setOptVdcs();
        }
    }
</script>
<style>
</style>
