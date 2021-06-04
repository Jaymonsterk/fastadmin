define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'report/report_agency_day/index' + location.search,
                    // add_url: 'report/report_agency_day/add',
                    // edit_url: 'report/report_agency_day/edit',
                    // del_url: 'report/report_agency_day/del',
                    // multi_url: 'report/report_agency_day/multi',
                    //import_url: 'report/report_agency_day/import',
                    table: 'report_agency_day',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                //切换卡片视图和表格视图两种模式
                showToggle:false,
                //显示隐藏列可以快速切换字段列的显示和隐藏
                showColumns:true,
                //导出整个表的所有行导出整个表的所有行
                showExport:false,
                //搜索
                search: false,
                //搜索功能，
                commonSearch: true,
                //表格上方的搜索搜索指表格上方的搜索
                searchFormVisible: true,
                columns: [
                    [
                        //{checkbox: true},
                        {field: 'dates', title: __('Dates'), operate: false},
                        {field: 'id', title: __('Id'), visible:false,operate: false},
                        //{field: 'reportid', title: __('Reportid'), visible:false,operate: false},
                        {field: 'uid', title: __('Uid'), visible:false, operate: false},
                        {field: 'uname', title: __('Uname'), operate: 'LIKE'},
                        {field: 'upuid', title: __('Upuid'), visible:false,operate: false},
                        {field: 'upuname', title: __('Upuname'), visible:false,operate: false},
                        {field: 'firstuid', title: __('Firstuid'), visible:false,operate: false},
                        {field: 'firstuname', title: __('Firstuname'), visible:false,operate: false},
                        {field: 'path', title: __('Path'), visible:false,operate: false},
                        {field: 'downnum', title: __('Downnum'),operate: false, sortable:true},
                        {field: 'alldownnum', title: __('Alldownnum'),operate: false, sortable:true},
                        {field: 'isnew', title: __('Isnew'),operate: false, sortable:true},
                        {field: 'isfistdeposit', title: __('Isfistdeposit'), visible:false, operate: false, sortable:true},
                        {field: 'usemoney', title: __('Usemoney'), operate:'BETWEEN', sortable:true},
                        {field: 'reward', title: __('Reward'), operate:false, sortable:true},
                        {field: 'commission', title: __('Commission'), visible:false, operate: false, sortable:true},
                        {field: 'totalnum', title: __('Totalnum'), operate:false, sortable:true},
                        {field: 'successnum', title: __('Successnum'), operate:false, sortable:true},
                        {field: 'failnum', title: __('Failnum'), visible:false, operate: false, sortable:true},
                        {field: 'depositmoney', title: __('Depositmoney'), operate:'BETWEEN', sortable:true},
                        {field: 'depositnum', title: __('Depositnum'), operate:false, sortable:true},
                        {field: 'withdrawalsmoney', title: __('Withdrawalsmoney'), operate:'BETWEEN', sortable:true},
                        {field: 'withdrawalsnum', title: __('Withdrawalsnum'), operate:false, sortable:true},
                        {field: 'zcsmoney', title: __('Zcsmoney'), operate:'BETWEEN', sortable:true},
                        {field: 'qdmoney', title: __('Qdmoney'), operate:'BETWEEN', sortable:true},
                        {field: 'yqzdmoney', title: __('Yqzdmoney'), operate:'BETWEEN', sortable:true},
                        {field: 'yqczmoney', title: __('Yqczmoney'), operate:'BETWEEN', sortable:true},
                        {field: 'yebmoney', title: __('Yebmoney'), operate:'BETWEEN', sortable:true},
                        //{field: 'times', title: __('Times')},
                        {field: 'ctime', title: __('Ctime'), visible:false,operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});