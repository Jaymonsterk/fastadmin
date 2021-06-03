define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'config/config_yuebao/index' + location.search,
                    add_url: 'config/config_yuebao/add',
                    edit_url: 'config/config_yuebao/edit',
                    del_url: 'config/config_yuebao/del',
                    multi_url: 'config/config_yuebao/multi',
                    //import_url: 'config/config_yuebao/import',
                    table: 'config_yuebao',
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
                        {field: 'id', title: __('Id'),visible:false, operate: false},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'day', title: __('Day'), operate: 'LIKE'},
                        {field: 'minmoney', title: __('Minmoney'), operate: 'LIKE'},
                        {field: 'proportion', title: __('Proportion'),visible:false, operate: false},
                        {field: 'dayproportion', title: __('Dayproportion'),visible:false, operate: false},
                        {field: 'mindayproportion', title: __('Mindayproportion'),visible:false, operate: false},
                        {field: 'getmoney', title: __('Getmoney'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'sort', title: __('Sort'),visible:false, operate: false},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
                        {field: 'aid', title: __('Aid'),visible:false, operate: false},
                        {field: 'aname', title: __('Aname'),visible:false, operate: false},
                        {field: 'utime', title: __('Utime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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