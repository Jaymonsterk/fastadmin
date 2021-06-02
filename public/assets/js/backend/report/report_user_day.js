define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'report/report_user_day/index' + location.search,
                    add_url: 'report/report_user_day/add',
                    edit_url: 'report/report_user_day/edit',
                    del_url: 'report/report_user_day/del',
                    multi_url: 'report/report_user_day/multi',
                    //import_url: 'report/report_user_day/import',
                    table: 'report_user_day',
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
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'reportid', title: __('Reportid'), operate: 'LIKE'},
                        {field: 'uid', title: __('Uid')},
                        {field: 'uname', title: __('Uname'), operate: 'LIKE'},
                        {field: 'upuid', title: __('Upuid')},
                        {field: 'upuname', title: __('Upuname'), operate: 'LIKE'},
                        {field: 'firstuid', title: __('Firstuid')},
                        {field: 'firstuname', title: __('Firstuname'), operate: 'LIKE'},
                        {field: 'path', title: __('Path'), operate: 'LIKE'},
                        {field: 'level', title: __('Level')},
                        {field: 'isnew', title: __('Isnew')},
                        {field: 'isfistdeposit', title: __('Isfistdeposit')},
                        {field: 'usemoney', title: __('Usemoney'), operate:'BETWEEN'},
                        {field: 'reward', title: __('Reward'), operate:'BETWEEN'},
                        {field: 'commission', title: __('Commission'), operate:'BETWEEN'},
                        {field: 'totalnum', title: __('Totalnum')},
                        {field: 'successnum', title: __('Successnum')},
                        {field: 'failnum', title: __('Failnum')},
                        {field: 'depositmoney', title: __('Depositmoney'), operate:'BETWEEN'},
                        {field: 'depositnum', title: __('Depositnum')},
                        {field: 'withdrawalsmoney', title: __('Withdrawalsmoney'), operate:'BETWEEN'},
                        {field: 'withdrawalsnum', title: __('Withdrawalsnum')},
                        {field: 'zcsmoney', title: __('Zcsmoney'), operate:'BETWEEN'},
                        {field: 'qdmoney', title: __('Qdmoney'), operate:'BETWEEN'},
                        {field: 'yqzdmoney', title: __('Yqzdmoney'), operate:'BETWEEN'},
                        {field: 'yqczmoney', title: __('Yqczmoney'), operate:'BETWEEN'},
                        {field: 'yebmoney', title: __('Yebmoney'), operate:'BETWEEN'},
                        {field: 'times', title: __('Times')},
                        {field: 'dates', title: __('Dates'), operate: 'LIKE'},
                        {field: 'ctime', title: __('Ctime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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